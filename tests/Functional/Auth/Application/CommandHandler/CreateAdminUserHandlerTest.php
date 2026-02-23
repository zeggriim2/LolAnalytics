<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Application\CommandHandler;

use App\Auth\Application\Command\CreateAdminUserCommand;
use App\Auth\Domain\Repository\AdminUserRepositoryInterface;
use App\Auth\Domain\ValueObject\Email;
use App\Tests\Factory\AdminUserEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

final class CreateAdminUserHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $commandBus;
    private AdminUserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->commandBus = $container->get('command.bus');
        $this->repository = $container->get(AdminUserRepositoryInterface::class);
    }

    public function testCreateAdminUserPersistsToDatabase(): void
    {
        // Given
        $command = new CreateAdminUserCommand('admin@lol.local', 'plainpassword');

        // When
        $this->commandBus->dispatch($command);

        // Then
        $user = $this->repository->findByEmail(Email::fromString('admin@lol.local'));

        $this->assertNotNull($user);
        $this->assertSame('admin@lol.local', $user->email()->value());
        $this->assertNotEmpty($user->hashedPassword());
        $this->assertStringNotContainsString('plainpassword', $user->hashedPassword());
    }

    public function testCreatedUserPasswordIsHashed(): void
    {
        // Given
        $plainPassword = 'mysecretpassword';
        $command = new CreateAdminUserCommand('admin@lol.local', $plainPassword);

        // When
        $this->commandBus->dispatch($command);

        // Then
        $user = $this->repository->findByEmail(Email::fromString('admin@lol.local'));

        $this->assertNotNull($user);
        $this->assertStringStartsWith('$', $user->hashedPassword());
        $this->assertNotSame($plainPassword, $user->hashedPassword());
    }

    public function testCreateAdminUserWithDuplicateEmailThrowsException(): void
    {
        // Given: an existing user with the same email
        AdminUserEntityFactory::createOne([
            'email' => 'admin@lol.local'
        ]);

        // Then
        $this->expectException(\Throwable::class);

        // When
        $command = new CreateAdminUserCommand('admin@lol.local', 'anotherpassword');
        $this->commandBus->dispatch($command);
    }
}
