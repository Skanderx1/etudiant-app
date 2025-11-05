<?php

namespace App\Controller;

use App\Entity\EtudiantMatiere;
use App\Form\EtudiantMatiere2Type;
use App\Repository\EtudiantMatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiant-matiere')]
final class EtudiantMatiereController extends AbstractController
{
    #[Route('/', name: 'app_etudiant_matiere_index', methods: ['GET'])]
    public function index(EtudiantMatiereRepository $etudiantMatiereRepository): Response
    {
        return $this->render('etudiant_matiere/index.html.twig', [
            'etudiant_matieres' => $etudiantMatiereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etudiant_matiere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etudiantMatiere = new EtudiantMatiere();
        $form = $this->createForm(EtudiantMatiere2Type::class, $etudiantMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etudiantMatiere);
            $entityManager->flush();

            return $this->redirectToRoute('app_etudiant_matiere_index');
        }

        return $this->render('etudiant_matiere/new.html.twig', [
            'etudiant_matiere' => $etudiantMatiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etudiant_matiere_show', methods: ['GET'])]
    public function show(EtudiantMatiere $etudiantMatiere): Response
    {
        return $this->render('etudiant_matiere/show.html.twig', [
            'etudiant_matiere' => $etudiantMatiere,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etudiant_matiere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EtudiantMatiere $etudiantMatiere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtudiantMatiere2Type::class, $etudiantMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_etudiant_matiere_index');
        }

        return $this->render('etudiant_matiere/edit.html.twig', [
            'etudiant_matiere' => $etudiantMatiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etudiant_matiere_delete', methods: ['POST'])]
    public function delete(Request $request, EtudiantMatiere $etudiantMatiere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiantMatiere->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etudiantMatiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etudiant_matiere_index');
    }
}
