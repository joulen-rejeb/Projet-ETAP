<?php

namespace App\Controller;

use App\Entity\Familles;
use App\Form\FamillesType;
use App\Repository\FamillesRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmplacementRepository;
use App\Repository\LivraisonRepository;
use App\Repository\PrelevementRepository;

class FamillesController extends AbstractController
{
    

  /* #[Route('/familles', name: 'familles_index', methods: ['GET'])]
public function index(
    FamillesRepository $famillesRepository,
    ProduitsRepository $produitsRepository,
    EmplacementRepository $emplacementRepository // ✅ injection du repo
): Response {
    $familles = $famillesRepository->findAll();
    $produits = $produitsRepository->findAll();
    $emplacements = $emplacementRepository->findAll(); // ✅ récupération

    $nbProduitsParFamille = [];
    foreach ($familles as $famille) {
        $count = 0;
        $familleId = $famille->getIdFamilles();
        foreach ($produits as $produit) {
            $produitFamille = $produit->getFamilles();
            if ($produitFamille && $produitFamille->getIdFamilles() == $familleId) {
                $count++;
            }
        }
        $nbProduitsParFamille[$familleId] = $count;
    }

    return $this->render('home/index.html.twig', [
        'familles' => $familles,
        'produits' => $produits,
        'nbProduitsParFamille' => $nbProduitsParFamille,
        'emplacements' => $emplacements, // ✅ ajout ici
    ]);
}
*/
#[Route('/familles', name: 'familles_index', methods: ['GET'])]
public function index(
    FamillesRepository $famillesRepository,
    ProduitsRepository $produitsRepository,
    EmplacementRepository $emplacementRepository,
    LivraisonRepository $livraisonRepository,
    PrelevementRepository $prelevementRepository
): Response {
    $user = $this->getUser();
    $roles = $user ? $user->getRoles() : [];

    // Initialisation des variables avec des valeurs par défaut
    $familles = [];
    $showLivraison = false;
    $showPrelevement = false;
    $showHistoryLivraison = false;
    $showHistoryPrelevement = false;
    $livraisons = [];
    $prelevements = [];

    // Récupération des données selon le rôle
    if (in_array('ROLE_ADMIN', $roles)) {
        // Admin voit tout
        $familles = $famillesRepository->findAll();
        $showLivraison = true;
        $showPrelevement = true;
        $showHistoryLivraison = true;
        $showHistoryPrelevement = true;
        $livraisons = $livraisonRepository->findAll();
        $prelevements = $prelevementRepository->findAll();
    } elseif (in_array('ROLE_CONSOMMABLES', $roles)) {
        // Responsable Consommables
        $familles = $famillesRepository->findAll();
        $showPrelevement = true;
        $showHistoryPrelevement = true;
        $prelevements = $prelevementRepository->findBy(['user' => $user]);
    } elseif (in_array('ROLE_FOURNITURES', $roles)) {
        // Responsable Fournitures
        $familles = $famillesRepository->findAll();
        $showLivraison = true;
        $showHistoryLivraison = true;
        $livraisons = $livraisonRepository->findBy(['user' => $user]);
    } elseif (in_array('ROLE_NETTOYAGE', $roles)) {
        // Responsable Produits de Nettoyage
        $familles = $famillesRepository->findBy(['nameCategory' => 'Produits de Nettoyage']);
        $showLivraison = true;
        $showPrelevement = true;
        $showHistoryLivraison = true;
        $showHistoryPrelevement = true;
        $livraisons = $livraisonRepository->findBy(['user' => $user]);
        $prelevements = $prelevementRepository->findBy(['user' => $user]);
    } elseif (in_array('ROLE_INFORMATIQUE', $roles)) {
        // Responsable Consommables Informatiques
        $familles = $famillesRepository->findBy(['nameCategory' => 'Produits Informatiques']);
        $showLivraison = true;
        $showPrelevement = true;
        $showHistoryLivraison = true;
        $showHistoryPrelevement = true;
        $livraisons = $livraisonRepository->findBy(['user' => $user]);
        $prelevements = $prelevementRepository->findBy(['user' => $user]);
    } elseif (in_array('ROLE_BUREATIQUE', $roles)) {
        // Responsable Fournitures de Bureau
        $familles = $famillesRepository->findBy(['nameCategory' => 'Produits Bureautiques']);
        $showLivraison = true;
        $showPrelevement = true;
        $showHistoryLivraison = true;
        $showHistoryPrelevement = true;
        $livraisons = $livraisonRepository->findBy(['user' => $user]);
        $prelevements = $prelevementRepository->findBy(['user' => $user]);
    }

    // Calcul du nombre de produits par famille
    $nbProduitsParFamille = [];
    foreach ($familles as $famille) {
        $count = 0;
        $familleId = $famille->getIdFamilles();
        foreach ($produitsRepository->findAll() as $produit) {
            $produitFamille = $produit->getFamilles();
            if ($produitFamille && $produitFamille->getIdFamilles() == $familleId) {
                $count++;
            }
        }
        $nbProduitsParFamille[$familleId] = $count;
    }

    return $this->render('home/index.html.twig', [
        'familles' => $familles,
        'nbProduitsParFamille' => $nbProduitsParFamille,
        'emplacements' => $emplacementRepository->findAll(),
        'showLivraison' => $showLivraison,
        'showPrelevement' => $showPrelevement,
        'showHistoryLivraison' => $showHistoryLivraison,
        'showHistoryPrelevement' => $showHistoryPrelevement,
        'livraisons' => $livraisons,
        'prelevements' => $prelevements,
    ]);
}

#[Route('/familles/new', name: 'familles_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $famille = new Familles();
    $form = $this->createForm(FamillesType::class, $famille);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoFamille')->getData();
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move(
                $this->getParameter('familles_photos_directory'),
                $newFilename
            );
            $famille->setPhotoFamille($newFilename);
        }

        $em->persist($famille);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    return $this->render('familles/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

   #[Route('/famille/{id}', name: 'produits_par_famille')]
 public function produitsParFamille(int $id, ProduitsRepository $produitsRepo, FamillesRepository $famillesRepo): Response
 {
    $famille = $famillesRepo->find($id);

    // Accès aux produits via la relation
    $produits = $famille->getProduits();
    

    return $this->render('familles/produits.html.twig', [
        'famille' => $famille,
        'produits' => $produits,

    ]);
 }


    #[Route('/familles/{id}/edit', name: 'familles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Familles $famille, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FamillesType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoFamille')->getData();
            if ($photoFile) {
                // Supprimer l'ancienne photo
                if ($famille->getPhotoFamille()) {
                    $oldPath = $this->getParameter('familles_photos_directory') . '/' . $famille->getPhotoFamille();
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                $photoFile->move(
                    $this->getParameter('familles_photos_directory'),
                    $newFilename
                );
                $famille->setPhotoFamille($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('familles_index');
        }

        return $this->render('familles/edit.html.twig', [
            'form' => $form->createView(),
            'famille' => $famille,
        ]);
    }

    #[Route('/familles/{id}', name: 'familles_delete', methods: ['POST'])]
    public function delete(Request $request, Familles $famille, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$famille->getIdFamilles(), $request->request->get('_token'))) {
            $em->remove($famille);
            $em->flush();
        }

        return $this->redirectToRoute('familles_index');
    }
}
