<?php

declare(strict_types=1);

namespace Acme\App\Core\Component\User\Application\Query;

use Acme\App\Core\Port\Persistence\ResultCollectionInterface;

interface UserListQueryInterface
{
    public function execute(array $orderBy = ['id' => 'DESC'], int $limit = null): ResultCollectionInterface;
}
