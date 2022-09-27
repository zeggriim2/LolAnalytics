<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Tests\HelpersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class LoginTest extends WebTestCase
{
    use HelpersTrait;


    public function testShouldLoggedUserAndRedirectToIndex()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/admin/login');
        $client->submitForm('Connexion', self::createFormData());

        self::assertResponseRedirects('http://localhost/admin');
        self::assertIsAuthenticated(true);

    }


    /**
     * @dataProvider provideInvalidFormData
     *
     * @return void
     */
    public function testShouldNotLoggedUserAndRedirectToLogin(array $formData)
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/admin/login');
        $client->submitForm('Connexion', $formData);

        self::assertIsAuthenticated(false);
        self::assertResponseRedirects('http://localhost/admin/login');
    }

    /**
     * @return \Generator<string, array<array-key, array{username: string, password: string}>>
     */
    public function provideInvalidFormData():\Generator
    {
        yield 'invalid username' => [self::createFormData(usermane: 'toto')];
        yield 'invalid password' => [self::createFormData(password: '147852')];
    }

    /**
     * @return array{username: string, password: string}
     */
    private static function createFormData(string $usermane = 'zeggriim', string $password = '123456'): array
    {
        return ['_username' => $usermane, '_password' => $password];
    }
}