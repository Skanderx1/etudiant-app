<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Etudiant;
use App\Entity\Matiere;
use App\Repository\AbsenceRepository;
use App\Repository\EtudiantRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/absence', name: 'app_absence_')]
final class AbsenceController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AbsenceRepository $absenceRepository): Response
    {
        return $this->render('absence/index.html.twig', [
            'absences' => $absenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $absence = new Absence();
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($absence);
            $em->flush();

            $this->addFlash('success', 'Absence ajoutée avec succès.');
            $this->checkMaxAbsences($absence, $mailer);

            return $this->redirectToRoute('app_absence_index');
        }

        return $this->render('absence/new.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Absence $absence): Response
    {
        return $this->render('absence/show.html.twig', [
            'absence' => $absence,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Absence $absence, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Absence mise à jour.');
            $this->checkMaxAbsences($absence, $mailer);

            return $this->redirectToRoute('app_absence_index');
        }

        return $this->render('absence/edit.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Absence $absence, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$absence->getId(), $request->request->get('_token'))) {
            $em->remove($absence);
            $em->flush();
            $this->addFlash('success', 'Absence supprimée.');
        }

        return $this->redirectToRoute('app_absence_index');
    }

    // Add one absence for a student and matiere
    #[Route('/add/{etudiantId}/{matiereId}', name: 'add', methods: ['POST', 'GET'])]
    public function addAbsence(int $etudiantId, int $matiereId, EntityManagerInterface $em, EtudiantRepository $etudiantRepo, MatiereRepository $matiereRepo, MailerInterface $mailer): Response
    {
        $etudiant = $etudiantRepo->find($etudiantId);
        $matiere = $matiereRepo->find($matiereId);

        if (!$etudiant || !$matiere) {
            $this->addFlash('danger', 'Étudiant ou matière introuvable.');
            return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
        }

        $absence = new Absence();
        $absence->setEtudiant($etudiant);
        $absence->setMatiere($matiere);
        $absence->setNbreAbsences(1);

        $em->persist($absence);
        $em->flush();

        $this->checkMaxAbsences($absence, $mailer);
        return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
    }

    // Remove one absence for a student and matiere
    #[Route('/remove/{etudiantId}/{matiereId}', name: 'remove', methods: ['POST', 'GET'])]
    public function removeAbsence(int $etudiantId, int $matiereId, EntityManagerInterface $em, AbsenceRepository $absenceRepo): Response
    {
        $absences = $absenceRepo->findBy([
            'etudiant' => $etudiantId,
            'matiere' => $matiereId
        ], ['id' => 'DESC']); // remove latest first

        if (!empty($absences)) {
            $em->remove($absences[0]);
            $em->flush();
        }

        return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
    }

    private function checkMaxAbsences(Absence $absence, MailerInterface $mailer): void
    {
        $etudiant = $absence->getEtudiant();
        $matiere = $absence->getMatiere();

        $totalAbsences = $etudiant->getAbsences()
            ->filter(fn($a) => $a->getMatiere() === $matiere)
            ->count();

        if ($totalAbsences > $matiere->getMaxAbsences()) {
            $email = (new Email())
                ->from('admin@school.com')
                ->to($etudiant->getEmail())
                ->subject('Attention : Abandonné pour ' . $matiere->getNom())
                ->text('Vous avez dépassé le nombre maximum d\'absences pour cette matière.');

            $mailer->send($email);
        }
    }
}
