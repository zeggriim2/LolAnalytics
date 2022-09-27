<?php

namespace App\Tests;

use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpKernel\Profiler\Profile;

trait HelpersTrait
{
    private static function getClient(): KernelBrowser
    {
        $method = new ReflectionMethod(WebTestCase::class, 'getClient');

        return $method->invoke(null);
    }

    public static function assertIsAuthenticated(bool $isAuthenticated): void
    {
        /** @var Profile $profile */
        $profile = self::getClient()->getProfile();
        $collector = $profile->getCollector('security');
        self::assertInstanceOf(SecurityDataCollector::class, $collector);
        self::assertSame($isAuthenticated, $collector->isAuthenticated());
    }
}