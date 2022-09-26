<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserSubscriber implements EventSubscriberInterface
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['beforeUserPersisted'],
            BeforeEntityUpdatedEvent::class => ['beforeUserUpdated'],
        ];
    }

    public function beforeUserPersisted(BeforeEntityPersistedEvent $event): void
    {
        $user = $event->getEntityInstance();

        if($user instanceof User){
            $this->hashPassword($user);
        }
    }

    public function beforeUserUpdated(BeforeEntityUpdatedEvent $event): void
    {
        $user = $event->getEntityInstance();

        if($user instanceof User){
            $this->hashPassword($user);
        }
    }


    private function hashPassword(User $user): void
    {
        if (null !== $user->getPlainPassword()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
        }
    }
}
