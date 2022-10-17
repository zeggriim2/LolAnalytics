<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    /**
     * @return RedirectResponse
     */
    #[Route('/home', name: 'app_home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('admin');
    }
}
