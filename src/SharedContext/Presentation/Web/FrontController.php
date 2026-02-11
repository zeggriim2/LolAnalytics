<?php

declare(strict_types=1);

namespace App\SharedContext\Presentation\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FrontController extends AbstractController
{
    #[Route('/{path}', name: 'front_home', requirements: ['path' => '^(?!api|admin).*'], defaults: ['path' => ''])]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }
}
