<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/addR', name: 'reclamation_addR', methods: ['GET', 'POST'])]

    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/addR.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    #[Route('/listA', name: 'listReclamation')]
    public function listA(ReclamationRepository $repository,Request $request,PaginatorInterface $paginator):Response
    {
        $form=$this->createForm(NSCType::class);

        $Reclamations=$repository->findReclamationByemail();

        $Reclamations = $paginator->paginate(
            $Reclamations, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) { 
            $NSC=$form->get('NSC')->getData();
            $Reclamations=$repository->findReclamationByNSC($NSC);
            $Reclamations=$repository->findReclamationByNSC($NSC);

            $Reclamations = $paginator->paginate(
                $Reclamations, /* query NOT result */
                $request->query->getInt('page', 1),
                2
            );
        }
        return $this->render('Reclamation/test.html.twig', [
            'controller_name' => 'ReclamationController',
            'Reclamations' => $Reclamations,
            'form_NSC'=>$form->createView()
            ]);

    }
    
    
    #[Route('/{id}/edit', name: 'reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reclamation_deleteRec', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    

}
