<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'app_livraison')]
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonController',
        ]);
    }

    #[Route('/livraison/new', name:'app_livraison_new')]
public function new(Request $request, EntityManagerInterface $em, ProduitsRepository $prodRepo)
{
    $livraison = new Livraison();
    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->getConnection()->beginTransaction();
        try {
            // récupérer produit avec lock
            $product = $prodRepo->find($livraison->getProduct()->getId());
            $em->lock($product, \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE);

            $qty = $livraison->getQuantity();
            // Option recommandée: NE PAS modifier quantity_initial (garder valeur initiale).
            $product->setQuantity($product->getQuantity() + $qty);

            $livraison->setEntryDate(new \DateTime());
            $livraison->setUser($this->getUser());

            $em->persist($livraison);
            $em->flush();
            $em->getConnection()->commit();

            $this->addFlash('success', 'Livraison enregistrée avec succès.');
            return $this->redirectToRoute('app_livraison_index');
        } catch (\Throwable $e) {
            $em->getConnection()->rollBack();
            throw $e;
        }
    }

    return $this->render('livraison/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


/*#[Route('/livraison/historique', name: 'app_livraison_index')]
public function historique(EntityManagerInterface $em): Response
{
    $livraisons = $em->getRepository(Livraison::class)->findBy([], ['entryDate' => 'DESC']);

    return $this->render('livraison/index.html.twig', [
        'livraisons' => $livraisons,
    ]);
}*/

#[Route('/livraison/historique', name: 'app_livraison_index')]
public function historique(EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        $livraisons = $em->getRepository(Livraison::class)->findBy([], ['entryDate' => 'DESC']);
    } else {
        $livraisons = $em->getRepository(Livraison::class)->findBy(
            ['user' => $user],
            ['entryDate' => 'DESC']
        );
    }

    return $this->render('livraison/index.html.twig', [
        'livraisons' => $livraisons,
    ]);
}

}


