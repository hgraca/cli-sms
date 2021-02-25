<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used in a workshop to explain Architecture patterns.
 *
 * Most of it authored by Herberto Graca.
 */

namespace Acme\App\Infrastructure\Framework\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{yaml}';

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();
        $container->import($configDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $container->import($configDir . '/{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');

        if (is_file($configDir . '/services.yaml')) {
            $container->import($configDir . '/services' . self::CONFIG_EXTS, 'glob');
            $container->import($configDir . '/services_' . $this->environment . self::CONFIG_EXTS, 'glob');
        } elseif (is_file($path = $configDir . '/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();
        // Routes are not overridden, so we need to load the most fine grained ones first
        $routes->import($configDir . '/{routes}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');
        $routes->import($configDir . '/{routes}/*' . self::CONFIG_EXTS, 'glob');

        if (is_file($configDir . '/routes.yaml')) {
            $routes->import($configDir . '/routes.yaml');
        } elseif (is_file($path = $configDir . '/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    /**
     * Method to register compiler passes and manipulate the container during the building process.
     */
    protected function build(ContainerBuilder $containerBuilder): void
    {
        /** @var bool[][] $contents */
        $contents = require $this->getConfigDir() . '/compiler_pass.php';
        foreach ($contents as $compilerPass => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                $containerBuilder->addCompilerPass(new $compilerPass());
            }
        }
    }

    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }
}
