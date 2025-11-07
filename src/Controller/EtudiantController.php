<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\EtudiantMatiere;
use App\Entity\Matiere;
use App\Form\EtudiantType;
use App\Form\EtudiantMatiereType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/etudiant', name: 'app_etudiant_')]
class EtudiantController extends AbstractController
{
    private string $photosDir;
    private string $photosBasepath;
    private EntityManagerInterface $em;
    private SluggerInterface $slugger;

    public function __construct(
        string $photosDir,
        string $photosBasepath,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ) {
        $this->photosDir = $photosDir;
        $this->photosBasepath = $photosBasepath;
        $this->em = $em;
        $this->slugger = $slugger;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move($this->photosDir, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur lors de l’upload de la photo.');
                    return $this->redirectToRoute('app_etudiant_new');
                }

                $etudiant->setPhoto($newFilename);
            }

            $this->em->persist($etudiant);
            $this->em->flush();

            $this->addFlash('success', 'Étudiant créé avec succès.');
            return $this->redirectToRoute('app_etudiant_index');
        }

        return $this->render('etudiant/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/show.html.twig', [
            'etudiant' => $etudiant,
            'photos_basepath' => '/uploads/photos', 
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        $oldPhoto = $etudiant->getPhoto();

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move($this->photosDir, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('warning', 'Erreur lors de l’upload de la photo.');
                    return $this->redirectToRoute('app_etudiant_edit', ['id' => $etudiant->getId()]);
                }

                if ($oldPhoto && file_exists($this->photosDir . '/' . $oldPhoto)) {
                    @unlink($this->photosDir . '/' . $oldPhoto);
                }

                $etudiant->setPhoto($newFilename);
            }

            $this->em->flush();
            $this->addFlash('success', 'Étudiant mis à jour.');
            return $this->redirectToRoute('app_etudiant_index');
        }

        return $this->render('etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }
#[Route('/{id}/add-matiere', name: 'add_matiere', methods: ['GET', 'POST'])]
public function addMatiere(Request $request, Etudiant $etudiant): Response
{
    $etudiantMatiere = new EtudiantMatiere();
    $etudiantMatiere->setEtudiant($etudiant);

    $form = $this->createForm(EtudiantMatiereType::class, $etudiantMatiere);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $etudiantMatiere->setAbsences(0);
        $this->em->persist($etudiantMatiere);
        $this->em->flush();
        $this->addFlash('success', 'Matière ajoutée à l’étudiant.');
        return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiant->getId()]);
    }

    return $this->render('etudiant/add_matiere.html.twig', [
        'etudiant' => $etudiant,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
            if ($etudiant->getPhoto() && file_exists($this->photosDir . '/' . $etudiant->getPhoto())) {
                @unlink($this->photosDir . '/' . $etudiant->getPhoto());
            }

            $this->em->remove($etudiant);
            $this->em->flush();
            $this->addFlash('success', 'Étudiant supprimé.');
        }

        return $this->redirectToRoute('app_etudiant_index');
    }
}
