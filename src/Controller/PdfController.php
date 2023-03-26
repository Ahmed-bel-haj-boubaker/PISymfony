<?php

namespace App\Controller;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(): Response
    {
        return $this->render('pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }
    #[Route("/pdf/event/{id}", name: "pdf_event")]
    public function pdf_event($id)
    {
        // Récupérer la liste des utilisateurs depuis la base de données
        $events = $this->getDoctrine()->getRepository(Event::class)->findByid($id);

        // Générer le contenu du PDF avec la liste des utilisateurs
        $html = $this->renderView('pdf/event.html.twig', [
            'events' => $events,
        ]);


        // Récupérer l'heure actuelle
        

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Définir les propriétés du document PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Mon application');
        $pdf->SetTitle('invitation');
        $pdf->SetSubject('Liste des equipements');
        $pdf->SetKeywords('Liste, equipements');



        // Ajouter une page au document PDF
        $pdf->AddPage();



        // Écrire le contenu HTML dans le document PDF
        $pdf->writeHTML($html, true, false, true, false, '');



        // Ajouter l'heure en bas de la dernière page
        $pdf->SetY(260);
        $pdf->SetFont('helvetica', 'I', 12);
        
        // Générer le fichier PDF et l'envoyer au navigateur
        return new Response($pdf->Output('Liste des equipements.pdf', 'I'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Liste des equipements.pdf"',
        ]);
    }
}
