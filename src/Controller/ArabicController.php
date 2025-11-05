<?php

namespace App\Controller;

use App\Entity\EtudiantMatiere;
use App\Form\EtudiantMatiere1Type;
use App\Repository\EtudiantMatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/arabic')]
final class ArabicController extends AbstractController
{
    #[Route(name: 'app_arabic_index', methods: ['GET'])]
    public function index(EtudiantMatiereRepository $etudiantMatiereRepository): Response
    {
        return $this->render('arabic/index.html.twig', [
            'etudiant_matieres' => $etudiantMatiereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_arabic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etudiantMatiere = new EtudiantMatiere();
        $form = $this->createForm(EtudiantMatiere1Type::class, $etudiantMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etudiantMatiere);
            $entityManager->flush();

            return $this->redirectToRoute('app_arabic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('arabic/new.html.twig', [
            'etudiant_matiere' => $etudiantMatiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_arabic_show', methods: ['GET'])]
    public function show(EtudiantMatiere $etudiantMatiere): Response
    {
        return $this->render('arabic/show.html.twig', [
            'etudiant_matiere' => $etudiantMatiere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_arabic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EtudiantMatiere $etudiantMatiere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtudiantMatiere1Type::class, $etudiantMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_arabic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('arabic/edit.html.twig', [
            'etudiant_matiere' => $etudiantMatiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_arabic_delete', methods: ['POST'])]
    public function delete(Request $request, EtudiantMatiere $etudiantMatiere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiantMatiere->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($etudiantMatiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_arabic_index', [], Response::HTTP_SEE_OTHER);
    }
}
