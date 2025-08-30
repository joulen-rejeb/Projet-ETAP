<?php

namespace App\Controller;

use App\Entity\Prelevement;
use App\Entity\PrelevementProduit;
use App\Form\PrelevementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/prelevement')]
class PrelevementController extends AbstractController
{
    #[Route('/new', name: 'prelevement_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // Rediriger vers la connexion si non connecté
            return $this->redirectToRoute('app_login');
        }

        $prelevement = new Prelevement();
        $prelevement->setUser($user);
        $form = $this->createForm(PrelevementType::class, $prelevement);
        $form->handleRequest($request);

   if ($form->isSubmitted() && $form->isValid()) {
        try {
            $prelevement->setRequestDate(new \DateTimeImmutable());

            foreach ($prelevement->getPrelevementProduits() as $pp) {
                $produit = $pp->getProduit();
                $qteDemandee = $pp->getQuantite();

                if ($produit->getQuantity() < $qteDemandee) {
                    $this->addFlash('danger', sprintf(
                        "Stock insuffisant pour %s — il reste seulement %d unités.",
                        $produit->getNameProduct(),
                        $produit->getQuantity()
                    ));
                    return $this->redirectToRoute('prelevement_new');
                }

                $produit->setQuantity($produit->getQuantity() - $qteDemandee);
                $pp->setPrelevement($prelevement);
                $em->persist($produit);
            }

            $em->persist($prelevement);
            $em->flush();

            $this->addFlash('success', 'Prélèvement effectué avec succès.');
            return $this->redirectToRoute('app_prelevement_index'); // Vérifiez le nom de la route

        } catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenue: '.$e->getMessage());
        }
    }

        return $this->render('prelevement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

/*#[Route('/', name: 'app_prelevement_index')]
public function index(EntityManagerInterface $em): Response
{
    $prelevements = $em->getRepository(Prelevement::class)->findAll();
    
    return $this->render('prelevement/index.html.twig', [
        'prelevements' => $prelevements,
    ]);
}*/

#[Route('/', name: 'app_prelevement_index')]
public function index(EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        $prelevements = $em->getRepository(Prelevement::class)->findAll();
    } else {
        $prelevements = $em->getRepository(Prelevement::class)->findBy(
            ['user' => $user],
            ['requestDate' => 'DESC']
        );
    }
    
    return $this->render('prelevement/index.html.twig', [
        'prelevements' => $prelevements,
    ]);
}


}