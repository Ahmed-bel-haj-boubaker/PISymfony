<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TypeEventRepository;
use App\Entity\TypeEvent;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TypeType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
class TypeEventController extends AbstractController
{

    #[Route('/admin/readT', name: 'readTypeEvent')]
    public function readR(TypeEventRepository $repository): Response
    {
        $te =$repository->findAll();
        return $this->render('type_event/readT.html.twig', [
            'TypeEvent' => $te,
        ]);
    }


/////////////// CREATE


    #[Route('/admin/createT', name: 'createTypeEvent')]
    public function createR(ManagerRegistry $doctrine, TypeEventRepository $repository, Request $request): Response
    {

        $typeEvent = new TypeEvent();
        $form = $this->createForm(TypeType::class, $typeEvent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())  {
            $em = $doctrine->getManager();
            $em->persist($typeEvent);
            $em->flush();
            return $this->redirectToRoute("readTypeEvent");
        }         
        return $this->renderForm('type_event/createT.html.twig', array('f' => $form));
        
    }


//////////////////////// UPDATE



    #[Route('/admin/updateT/{id}', name: 'updateTypeEvent')]
    public function updateR(ManagerRegistry $doctrine, TypeEventRepository $repository, Request $request, $id): Response
    {
        $typeEvent = $repository->find($id);
        $form = $this->createForm(TypeType::class, $typeEvent);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())  {
            $em = $doctrine->getManager();
            $em->persist($typeEvent);
            $em->flush();
            return $this->redirectToRoute("readTypeEvent");
        }         
        return $this->renderForm('type_event/updateT.html.twig', array('f' => $form));
        
    }


/////////////////////// DELETE


    #[Route('/admin/deleteT/{id}', name: 'deleteTypeEvent')]
    public function deleteR(ManagerRegistry $doctrine,EventRepository $rep, TypeEventRepository $repository, $id,EntityManagerInterface $entityManager ): Response
    {
        $m = $repository->find($id);
        
        
        $em = $doctrine->getManager();
        
        $em->remove($m);
        $em->flush();
        return $this->redirectToRoute("readTypeEvent");
        
    }

}

 

