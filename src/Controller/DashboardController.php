<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FamillesRepository;
use App\Repository\EmplacementRepository;

final class DashboardController extends AbstractController
{/*
 #[Route('/Home', name: 'home')]
public function home(FamillesRepository $famillesRepo, EmplacementRepository $emplacementRepo): Response
{
    $familles = $famillesRepo->findAll();
    $emplacements = $emplacementRepo->findAll();

    return $this->render('home/index.html.twig', [
        'familles' => $familles,
        'emplacements' => $emplacements,
    ]);
}*/


}
