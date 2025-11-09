<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Etudiant;
use App\Entity\Matiere;
use App\Entity\Absence;
use App\Repository\EtudiantRepository;
use App\Repository\MatiereRepository;
use App\Repository\AbsenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/absence', name: 'app_absence_')]
class AbsenceController extends AbstractController
{
    #[Route('/add/{etudiantId}/{matiereId}', name: 'add', methods: ['GET'])]
    public function add(int $etudiantId, int $matiereId, EntityManagerInterface $em): RedirectResponse
    {
        $etudiant = $em->getRepository(Etudiant::class)->find($etudiantId);
        $matiere = $em->getRepository(Matiere::class)->find($matiereId);

        if (!$etudiant || !$matiere) {
            $this->addFlash('warning', 'Étudiant ou matière introuvable.');
            return $this->redirectToRoute('app_etudiant_index');
        }

        $absence = new Absence();
        $absence->setEtudiant($etudiant);
        $absence->setMatiere($matiere);

        $em->persist($absence);
        $em->flush();

        $this->addFlash('success', "Absence ajoutée pour {$matiere->getName()}.");

        return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
    }

    #[Route('/remove/{etudiantId}/{matiereId}', name: 'remove', methods: ['GET'])]
    public function remove(
        int $etudiantId,
        int $matiereId,
        AbsenceRepository $absenceRepo,
        EntityManagerInterface $em
    ): RedirectResponse {
        $lastAbsence = $absenceRepo->createQueryBuilder('a')
            ->andWhere('a.etudiant = :etudiant')
            ->andWhere('a.matiere = :matiere')
            ->setParameter('etudiant', $etudiantId)
            ->setParameter('matiere', $matiereId)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lastAbsence) {
            $em->remove($lastAbsence);
            $em->flush();
            $this->addFlash('success', 'Dernière absence supprimée.');
        } else {
            $this->addFlash('warning', 'Aucune absence à supprimer.');
        }

        return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
    }
#[Route('/test-email', name: 'app_test_email')]
public function testEmail(MailerInterface $mailer): Response
{
    $email = (new Email())
        ->from('9b1a9e001@smtp-brevo.com') 
        ->to('feresbhar123@gmail.com')
        ->subject('Test Email Brevo')
        ->text('This is a test email from Symfony using Brevo SMTP.');

    try {
        $mailer->send($email);
        return new Response('Email sent successfully!');
    } catch (\Exception $e) {
        return new Response('Error: ' . $e->getMessage());
    }
}

    #[Route('/notify/{etudiantId}/{matiereId}', name: 'notify', methods: ['POST'])]
    public function notifyStudent(
        int $etudiantId,
        int $matiereId,
        Request $request,
        EtudiantRepository $etudiantRepo,
        MatiereRepository $matiereRepo,
        MailerInterface $mailer
    ): RedirectResponse {
        $etudiant = $etudiantRepo->find($etudiantId);
        $matiere = $matiereRepo->find($matiereId);

        if (!$etudiant || !$matiere) {
            $this->addFlash('danger', 'Étudiant ou matière introuvable.');
            return $this->redirectToRoute('app_etudiant_index');
        }

        // Verify CSRF token
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('notify' . $etudiantId . $matiereId, $submittedToken)) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
        }

        // Count absences for this subject
        $absencesCount = count(array_filter(
            $etudiant->getAbsences()->toArray(),
            fn($a) => $a->getMatiere()->getId() === $matiere->getId()
        ));

        // Build email
        $email = (new Email())
            ->from('feresbhar123@gmail.com')
            ->to($etudiant->getEmail())
            ->subject('Notification d’absence')
            ->html("
                <p>Bonjour {$etudiant->getPrenom()} {$etudiant->getNom()},</p>
                <p>Vous avez actuellement <strong>{$absencesCount}</strong> absences pour la matière <strong>{$matiere->getName()}</strong>.</p>
                <p>Le maximum autorisé est : {$matiere->getMaxAbscences()} absences.</p>
                <p>Merci de faire attention à votre assiduité.</p>
            ");

        try {
            $mailer->send($email);
            $this->addFlash('success', "Email envoyé à {$etudiant->getPrenom()} !");
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de l’envoi de l’email : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_etudiant_show', ['id' => $etudiantId]);
    }
}
