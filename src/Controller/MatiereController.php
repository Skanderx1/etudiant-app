<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/matiere', name: 'app_matiere_')]
class MatiereController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }

#[Route('/new', name: 'new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $matiere = new Matiere();
    $form = $this->createForm(MatiereType::class, $matiere);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($matiere);
        $em->flush();

        // Flash message for toast
        $this->addFlash('success', 'Matière créée avec succès !');

        // Redirect to main student page
        return $this->redirectToRoute('app_etudiant_index');
    }

    return $this->render('matiere/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
