<?php

declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\ValueObject\Email;

interface AdminUserRepositoryInterface
{
    public function findByEmail(Email $email): ?AdminUser;

    public function save(AdminUser $user): void;
}
