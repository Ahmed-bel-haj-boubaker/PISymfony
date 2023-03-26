<?php

namespace App\Controller;
use App\Repository\CategoryTransportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoryTransport;
use App\Form\CategoryTransportType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CategoryTransportController extends AbstractController
{
    #[Route('/CategoryTransport', name: 'app_CategoryTransport')]
    public function index(): Response
    {
        return $this->render('CategoryTransport/index.html.twig', [
            'CategoryTransport_name' => 'CategoryTransportController',
        ]);
    }
   

    #[Route('/admin/listcatt', name: 'list_CategoryTransport')]
    public function list(ManagerRegistry $doctrine, Request $request): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(CategoryTransport::class);
        $CategoryTransports = $repository->findAll();
        $CategoryTransport = new CategoryTransport();
        $form = $this->createForm(CategoryTransportType::class, $CategoryTransport);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($CategoryTransport);
            $em->flush();
            return $this->redirectToRoute('list_CategoryTransport');
        }

        return $this->render('CategoryTransport/list.html.twig', [
            'controller_name' => 'CategoryTransportController',
            'CategoryTransports' => $CategoryTransports,
            'form' => $form->createView()
        ]);
    }

    #[Route('/showC/{id}', name: 'showC')]
    public function showC(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(CategoryTransport::class);
        $CategoryTransport = $repository->find($id);

        return $this->render('CategoryTransport/detail.html.twig', [
            'controller_name' => 'CategoryTransportController',
            'CategoryTransport' => $CategoryTransport,
        ]);
    }
   
    #[Route('/admin/deletecatt/{id}', name: 'delete')]
    public function deleteClass(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(CategoryTransport::class);
        $CategoryTransport = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($CategoryTransport);
        $em->flush();
        return $this->redirectToRoute("list_CategoryTransport");
    }
    #[Route('/admin/addC', name: 'addC')]
    public function addCategoryTransport(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(CategoryTransport::class);
        $CategoryTransports = $repository->findAll();
        $CategoryTransport = new CategoryTransport();
        $form = $this->createForm(CategoryTransportType::class, $CategoryTransport);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($CategoryTransport);
            $em->flush();
            return $this->redirectToRoute('list_CategoryTransport');
        }
        // return $this->render('classroom/detail.html.twig', [
        //     'form' => $form->createView()
        // ]);
        return $this->renderForm('CategoryTransport/list.html.twig', [
            'form' => $form,
            'CategoryTransports' => $CategoryTransports
        ]);
    }
   
    #[Route('/admin/{id}/editc', name: 'app_CategoryTransport_edit')]
    public function editc(Request $request, CategoryTransport $CategoryTransport, CategoryTransportRepository $CategoryTransportRepository): Response
    {
        $form = $this->createForm(CategoryTransportType::class, $CategoryTransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $CategoryTransportRepository->save($CategoryTransport, true);

            return $this->redirectToRoute('list_CategoryTransport', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorytransport/edit.html.twig', [
            'CategoryTransport' => $CategoryTransport,
            'form' => $form,
        ]);

 
     }
     
    /* #[Route('/{id}/updatec', name: 'update_commentaire')]
     public function  update(ManagerRegistry $doctrine,$id,  Request  $request) : Response
     { $CategoryTransport = $doctrine
         ->getRepository(CategoryTransport::class)
         ->find($id);
         $form = $this->createForm(CategoryTransportType::class, $CategoryTransport);
         $form->add('update', SubmitType::class) ;
         $form->handleRequest($request);
         if ($form->isSubmitted())
         { $em = $doctrine->getManager();
             $em->flush();
             return $this->redirectToRoute('list_CategoryTransport');
         }
         return $this->renderForm("categorytransport/edit.html.twig",
             ["form"=>$form]) ;
     }*/
    
}