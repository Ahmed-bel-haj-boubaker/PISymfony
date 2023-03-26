<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Form\LocalisationType;
use App\Repository\LocalisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\validator\Constraints as Assert;

use Symfony\Component\Routing\Annotation\Route;


class LocalisationController extends AbstractController
{
    #[Route('/admin/localisation', name: 'app_localisation_index', methods: ['GET'])]
    public function index(LocalisationRepository $localisationRepository): Response
    {
        return $this->render('localisation/index.html.twig', [
            'localisations' => $localisationRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_localisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocalisationRepository $localisationRepository): Response
    {
        $localisation = new Localisation();
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localisationRepository->save($localisation, true);

            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localisation/new.html.twig', [
            'localisation' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/admin/localisation/{id}', name: 'app_localisation_show', methods: ['GET'])]
    public function show(Localisation $localisation): Response
    {
        return $this->render('localisation/show.html.twig', [
            'localisation' => $localisation,
        ]);
    }

    #[Route('/admin/{id}/editl', name: 'app_localisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localisation $localisation, LocalisationRepository $localisationRepository): Response
    {
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localisationRepository->save($localisation, true);

            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localisation/edit.html.twig', [
            'localisation' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/admin/del/{id}', name: 'app_localisation_delete', methods: ['POST'])]
    public function delete(Request $request, Localisation $localisation, LocalisationRepository $localisationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localisation->getId(), $request->request->get('_token'))) {
            $localisationRepository->remove($localisation, true);
        }

        return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
