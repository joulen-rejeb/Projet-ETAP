<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use App\Repository\EmplacementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Produits;
use App\Form\ProduitsType;

class EmplacementController extends AbstractController
{
    #[Route('/emplacements', name: 'emplacement_index', methods: ['GET'])]
    public function index(EmplacementRepository $emplacementRepository): Response
    {
        $emplacements = $emplacementRepository->findAll();

        return $this->render('emplacement/index.html.twig', [
            'emplacements' => $emplacements,
        ]);
    }

#[Route('/emplacements/new', name: 'emplacement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $emplacement = new Emplacement();
    $form = $this->createForm(EmplacementType::class, $emplacement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoEmplacement')->getData();
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move(
                $this->getParameter('emplacements_photos_directory'),
                $newFilename
            );
            $emplacement->setPhotoEmplacement($newFilename);
        }

        $em->persist($emplacement);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    return $this->render('emplacement/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/emplacements/{id}/edit', name: 'emplacement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emplacement $emplacement, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EmplacementType::class, $emplacement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoEmplacement')->getData();
            if ($photoFile) {
                if ($emplacement->getPhotoEmplacement()) {
                    $oldPath = $this->getParameter('emplacements_photos_directory') . '/' . $emplacement->getPhotoEmplacement();
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $newFilename = uniqid().'.'.$photoFile->guessExtension();
                $photoFile->move(
                    $this->getParameter('emplacements_photos_directory'),
                    $newFilename
                );
                $emplacement->setPhotoEmplacement($newFilename);
            }

            $em->flush();
return $this->redirectToRoute('home');
        }

        return $this->render('emplacement/edit.html.twig', [
            'form' => $form->createView(),
            'emplacement' => $emplacement,
        ]);
    }

    #[Route('/emplacements/{id}', name: 'emplacement_delete', methods: ['POST'])]
    public function delete(Request $request, Emplacement $emplacement, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emplacement->getIdEmplacement(), $request->request->get('_token'))) {
            if ($emplacement->getPhotoEmplacement()) {
                $photoPath = $this->getParameter('emplacements_photos_directory') . '/' . $emplacement->getPhotoEmplacement();
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            $em->remove($emplacement);
            $em->flush();
        }

return $this->redirectToRoute('home');
    }




}
