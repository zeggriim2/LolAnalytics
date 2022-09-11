<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admine')]
class AdminEquipeController extends AbstractController
{

    /**
     * @return Response
     */
    #[Route('/equipe')]
    public function index(): Response
    {
        return $this->render('admin/equipe.html.twig');
    }
}