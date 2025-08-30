<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FamillesRepository;
use App\Repository\EmplacementRepository;
use App\Entity\Emplacement;

class ProduitsController extends AbstractController
{
       /*#[Route('/Home', name: 'home')]
public function home(FamillesRepository $famillesRepo, EmplacementRepository $emplacementRepo): Response
{
    $familles = $famillesRepo->findAll();
    $emplacements = $emplacementRepo->findAll();

    return $this->render('home/index.html.twig', [
        'familles' => $familles,
        'emplacements' => $emplacements,
    ]);
}*/
/*#[Route('/Home', name: 'home')]
public function home(
    EmplacementRepository $emplacementRepo,
    FamillesRepository $famillesRepo
): Response {
    $user = $this->getUser();

    // --- Emplacements (inchangé) ---
    if ($this->isGranted('ROLE_ADMIN')) {
        $emplacements = $emplacementRepo->findAll();
    } else {
        $lieu = $user->getLieu();
        $emplacements = $emplacementRepo->findBy(['zone' => $lieu]);
    }

    // --- Familles (modifié selon rôle) ---
    if ($this->isGranted('ROLE_ADMIN') || 
        $this->isGranted('ROLE_CONSOMMABLES') || 
        $this->isGranted('ROLE_FOURNITURES')) {
        // Ces rôles voient toutes les familles
        $familles = $famillesRepo->findAll();

    } elseif ($this->isGranted('ROLE_NETTOYAGE')) {
        $familles = $famillesRepo->findBy(['nameCategory' => 'Produits de Nettoyage']);

    } elseif ($this->isGranted('ROLE_BUREATIQUE')) {
        $familles = $famillesRepo->findBy(['nameCategory' => 'Produits Bureautiques']);

    } elseif ($this->isGranted('ROLE_INFORMATIQUE')) {
        $familles = $famillesRepo->findBy(['nameCategory' => 'Produits Informatiques']);

    } else {
        $familles = []; // aucun accès si rôle inconnu
    }

    return $this->render('home/index.html.twig', [
        'emplacements' => $emplacements,
        'familles' => $familles,
    ]);
}
*/
#[Route('/Home', name: 'home')]
public function home(
    EmplacementRepository $emplacementRepo,
    FamillesRepository $famillesRepo
): Response {
    $user = $this->getUser();

    // ✅ ADDED CHECK: Redirect unauthenticated users to login
    if (!$user) {
        $this->addFlash('error', 'Veuillez vous connecter pour accéder à l\'accueil.');
        return $this->redirectToRoute('app_login'); // Assuming 'app_login' is your login route
    }

    // --- Emplacements (modifié) ---
    if ($this->isGranted('ROLE_ADMIN')) {
        $emplacements = $emplacementRepo->findAll();
    } else {
        $lieu = $user->getLieu();
        // Check if lieu is available to prevent further null/missing data errors
        if (!$lieu) {
             // Handle case where user has no 'lieu' (e.g., redirect or show a message)
             $emplacements = []; 
        } else {
             $emplacements = $emplacementRepo->findBy(['zone' => $lieu]);
        }
    }
    // ... rest of the method (Familles logic is fine as it relies on isGranted/getUser)

    // --- Familles (inchangé) ---
    if ($this->isGranted('ROLE_ADMIN') || 
        $this->isGranted('ROLE_CONSOMMABLES') || 
        $this->isGranted('ROLE_FOURNITURES')) {
        $familles = $famillesRepo->findAll();

    } elseif ($this->isGranted('ROLE_NETTOYAGE')) {
        $familles = $famillesRepo->findBy(['nameCategory' => 'Produits de Nettoyage']);

    } elseif ($this->isGranted('ROLE_BUREATIQUE')) {
        $familles = $famillesRepo->findBy(['nameCategory' => 'Produits Bureautiques']);

    } elseif ($this->isGranted('ROLE_INFORMATIQUE')) {
        $familles = $famillesRepo->findBy(['nameCategory' => 'Produits Informatiques']);

    } else {
        $familles = [];
    }

    return $this->render('home/index.html.twig', [
        'emplacements' => $emplacements,
        'familles' => $familles,
    ]);
}


       #[Route('/Dashboard', name: 'dashboard')]
    public function dashboard(ProduitsRepository $produitsRepository): Response
    {
        $produits = $produitsRepository->findAll();
        
        return $this->render('dashboard.html.twig', [
            'produits' => $produits,
        ]);
    }

   #[Route('/produits', name: 'app_produits_index', methods: ['GET'])]
public function index(ProduitsRepository $produitsRepository, FamillesRepository $famillesRepository): Response
{
    $produits = $produitsRepository->findAll();
    $familles = $famillesRepository->findAll(); // ✅ on définit la variable ici

    return $this->render('produits/index.html.twig', [
        'produits' => $produits,
        'familles' => $familles, // ✅ maintenant elle est bien définie
    ]);
}

/*#[Route('/produits/new', name: 'app_produits_new')]
public function new(
    Request $request,
    EntityManagerInterface $em,
    EmplacementRepository $emplacementRepo,
    FamillesRepository $famillesRepo
): Response {
    $produit = new Produits();

    // Récupération du contexte
    $from = $request->query->get('from'); // "emplacement" ou "famille"
    $parentId = $request->query->get('parent_id');

    // Pré-remplir emplacement si fourni
    if ($emplacementId = $request->query->get('emplacement_id')) {
        if ($emplacement = $emplacementRepo->find($emplacementId)) {
            $produit->setEmplacement($emplacement);
        }
    }

    // Pré-remplir famille si fourni
    if ($familleId = $request->query->get('famille_id')) {
        if ($famille = $famillesRepo->find($familleId)) {
            $produit->setFamilles($famille);
        }
    }

    $form = $this->createForm(ProduitsType::class, $produit, [
        'emplacement_disabled' => (bool) $produit->getEmplacement(),
        'famille_disabled' => (bool) $produit->getFamilles(),
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoProduct')->getData();
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('produits_photos_directory'), $newFilename);
            $produit->setPhotoProduct($newFilename);
        }

        if ($user = $this->getUser()) {
            $produit->setUserId($user->getId());
        } else {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un produit');
            return $this->redirectToRoute('app_login');
        }

        $produit->setDateAdded(new \DateTime());
        $produit->setQuantity($produit->getQuantityInitial());

        $em->persist($produit);
        $em->flush();

        // Redirection dynamique
        if ($from === 'emplacement') {
            return $this->redirectToRoute('produits_par_emplacement', ['id' => $parentId]);
        } elseif ($from === 'famille') {
            return $this->redirectToRoute('produits_par_famille', ['id' => $parentId]);
        }
        return $this->redirectToRoute('home');
    }

    return $this->render('produits/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
*/

#[Route('/produits/new', name: 'app_produits_new')]
public function new(
    Request $request,
    EntityManagerInterface $em,
    EmplacementRepository $emplacementRepo,
    FamillesRepository $famillesRepo
): Response {
    $produit = new Produits();
    $user = $this->getUser();

    if (!$user) {
        $this->addFlash('error', 'Vous devez être connecté pour ajouter un produit');
        return $this->redirectToRoute('app_login');
    }

    // --- Emplacement auto ---
    $userLieu = $user->getLieu();
    $emplacement = $emplacementRepo->findOneBy(['zone' => $userLieu]);
    if (!$emplacement) {
        $this->addFlash('error', 'Aucun emplacement ne correspond à votre lieu');
        return $this->redirectToRoute('home');
    }
    $produit->setEmplacement($emplacement);

    // --- Famille auto selon rôle ---
    $familles = [];
    $famillePreselectionnee = null;

    if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CONSOMMABLES') || $this->isGranted('ROLE_FOURNITURES')) {
        $familles = $famillesRepo->findAll(); // admin-like → toutes
    } elseif ($this->isGranted('ROLE_NETTOYAGE')) {
        $famillePreselectionnee = $famillesRepo->findOneBy(['nameCategory' => 'Produits de Nettoyage']);
    } elseif ($this->isGranted('ROLE_BUREATIQUE')) {
        $famillePreselectionnee = $famillesRepo->findOneBy(['nameCategory' => 'Produits Bureautiques']);
    } elseif ($this->isGranted('ROLE_INFORMATIQUE')) {
        $famillePreselectionnee = $famillesRepo->findOneBy(['nameCategory' => 'Produits Informatiques']);
    }

    // Si on a trouvé une famille pour ce rôle
    if ($famillePreselectionnee) {
        $produit->setFamilles($famillePreselectionnee);
        $familles = [$famillePreselectionnee]; // limiter le choix à cette famille
    }

    // --- Formulaire ---
    $form = $this->createForm(ProduitsType::class, $produit, [
        'emplacement_disabled' => true,
        'famille_disabled' => (bool) $produit->getFamilles(), // champ désactivé si auto-détecté
        'emplacements' => [$emplacement],
        'familles' => $familles,
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoProduct')->getData();
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('produits_photos_directory'), $newFilename);
            $produit->setPhotoProduct($newFilename);
        }

        $produit->setUserId($user->getId());
        $produit->setDateAdded(new \DateTime());
        $produit->setQuantity($produit->getQuantityInitial());

        $em->persist($produit);
        $em->flush();

        // Redirection
        $from = $request->query->get('from');
        $parentId = $request->query->get('parent_id');

        if ($from === 'emplacement') {
            return $this->redirectToRoute('produits_par_emplacement', ['id' => $parentId]);
        } elseif ($from === 'famille') {
            return $this->redirectToRoute('produits_par_famille', ['id' => $parentId]);
        }
        return $this->redirectToRoute('home');
    }

    return $this->render('produits/new.html.twig', [
        'form' => $form->createView(),
        'emplacement_nom' => $produit->getEmplacement() ? $produit->getEmplacement()->getZone() : '',
    ]);
}







/*#[Route('/produits/{id}/edit', name: 'app_produits_edit')]
public function edit(Request $request, Produits $produit, EntityManagerInterface $em): Response
{
    $from = $request->query->get('from');
    $parentId = $request->query->get('parent_id');

    $form = $this->createForm(ProduitsType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoProduct')->getData();
        if ($photoFile) {
            if ($produit->getPhotoProduct()) {
                $oldPhoto = $this->getParameter('produits_photos_directory').'/'.$produit->getPhotoProduct();
                if (file_exists($oldPhoto)) {
                    unlink($oldPhoto);
                }
            }
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('produits_photos_directory'), $newFilename);
            $produit->setPhotoProduct($newFilename);
        }

        $em->flush();

        // Redirection dynamique
        if ($from === 'emplacement') {
            return $this->redirectToRoute('produits_par_emplacement', ['id' => $parentId]);
        } elseif ($from === 'famille') {
            return $this->redirectToRoute('produits_par_famille', ['id' => $parentId]);
        }
        return $this->redirectToRoute('app_produits_index');
    }

    return $this->render('produits/edit.html.twig', [
        'form' => $form->createView(),
        'produit' => $produit,
    ]);
}
*/
#[Route('/produits/{id}/edit', name: 'app_produits_edit')]
public function edit(
    Request $request, 
    Produits $produit, 
    EntityManagerInterface $em,
    EmplacementRepository $emplacementRepo,
    FamillesRepository $famillesRepo
): Response
{
    $user = $this->getUser();
    $userLieu = $user->getLieu();
    
    // Filtrer les emplacements
    $emplacements = $emplacementRepo->findBy(['zone' => $userLieu]);
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        $emplacements = $emplacementRepo->findAll();
    }
    
    $familles = $famillesRepo->findAll();

    $form = $this->createForm(ProduitsType::class, $produit, [
        'emplacement_disabled' => false,
        'famille_disabled' => false,
        'emplacements' => $emplacements,
        'familles' => $familles,
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoProduct')->getData();
        if ($photoFile) {
            if ($produit->getPhotoProduct()) {
                $oldPhoto = $this->getParameter('produits_photos_directory').'/'.$produit->getPhotoProduct();
                if (file_exists($oldPhoto)) {
                    unlink($oldPhoto);
                }
            }
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('produits_photos_directory'), $newFilename);
            $produit->setPhotoProduct($newFilename);
        }

        $em->flush();

        // Redirection dynamique
        if ($from === 'emplacement') {
            return $this->redirectToRoute('produits_par_emplacement', ['id' => $parentId]);
        } elseif ($from === 'famille') {
            return $this->redirectToRoute('produits_par_famille', ['id' => $parentId]);
        }
        return $this->redirectToRoute('app_produits_index');
    }

    return $this->render('produits/edit.html.twig', [
        'form' => $form->createView(),
        'produit' => $produit,
    ]);
}



#[Route('/produits/{id}', name: 'app_produits_delete', methods: ['DELETE', 'POST'])]
public function delete(Request $request, Produits $produit, EntityManagerInterface $em): Response
{
    $from = $request->query->get('from');
    $parentId = $request->query->get('parent_id');

if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
    if ($produit->getPhotoProduct()) {
        $photoPath = $this->getParameter('produits_photos_directory').'/'.$produit->getPhotoProduct();
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }
    $em->remove($produit);
    $em->flush();

    // 🔥 Message de succès
    $this->addFlash('success', 'Produit supprimé avec succès');
}


    // Redirection dynamique
    if ($from === 'emplacement') {
        return $this->redirectToRoute('produits_par_emplacement', ['id' => $parentId]);
    } elseif ($from === 'famille') {
        return $this->redirectToRoute('produits_par_famille', ['id' => $parentId]);
    }
    return $this->redirectToRoute('app_produits_index');
}



#[Route('/emplacement/{id}/produits', name: 'produits_par_emplacement')]
public function produitsParEmplacement(Emplacement $emplacement, ProduitsRepository $produitsRepository): Response
{
    $produits = $produitsRepository->findBy(['emplacement' => $emplacement]);

    return $this->render('emplacement/produits.html.twig', [
        'emplacement' => $emplacement,
        'produits' => $produits,
    ]);
}




    

}