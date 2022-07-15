<?php

namespace App\Controller;

use App\Services\API\LOL\StatusApi;
use App\Services\API\LOL\SummonerApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(StatusApi $statusApi): JsonResponse
    {
        dd($statusApi->status());
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }
}
