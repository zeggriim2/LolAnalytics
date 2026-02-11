<?php

declare(strict_types=1);

namespace App\SharedContext\Presentation\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/{path}', name: 'admin', requirements: ['path' => '.*'], defaults: ['path' => ''])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
