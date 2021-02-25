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

namespace Acme\App\Core\Port\Router;

use Acme\PhpExtension\Enum\AbstractEnum;

/**
 * @method static UrlType absoluteUrl()
 * @method bool   isAbsoluteUrl()
 * @method static UrlType absolutePath()
 * @method bool   isAbsolutePath()
 * @method static UrlType relativePath()
 * @method bool   isRelativePath()
 * @method static UrlType networkPath()
 * @method bool   isNetworkPath()
 */
final class UrlType extends AbstractEnum
{
    /**
     * An absolute URL, e.g. "http://example.com/dir/file".
     */
    protected const ABSOLUTE_URL = 0;

    /**
     * An absolute path, e.g. "/dir/file".
     */
    protected const ABSOLUTE_PATH = 1;

    /**
     * A relative path based on the current request path, e.g. "../parent-file".
     *
     * @see UrlGenerator::getRelativePath()
     */
    protected const RELATIVE_PATH = 2;

    /**
     * A network path, e.g. "//example.com/dir/file".
     * Such reference reuses the current scheme but specifies the host.
     */
    protected const NETWORK_PATH = 3;
}
