<?php

namespace App\Controller;
use App\Repository\CategoryHebergementRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoryHebergement;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\CategoryHebergementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
class CategoryHebergementController extends AbstractController
{
    #[Route('/admin/CategoryHebergement', name: 'app_CategoryHebergement')]
    public function index(): Response
    {
        return $this->render('CategoryHebergement/index.html.twig', [
            'CategoryHebergement_name' => 'CategoryHebergementController',
        ]);
    }

    #[Route('/admin/listC', name: 'list_CategoryHebergement')]
    public function list(ManagerRegistry $doctrine, Request $request): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(CategoryHebergement::class);
        $CategoryHebergements = $repository->findAll();
        
        $CategoryHebergement = new CategoryHebergement();
        $form = $this->createForm(CategoryHebergementType::class, $CategoryHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($CategoryHebergement);
            $em->flush();
            return $this->redirectToRoute('list_CategoryHebergement');
        }

        return $this->render('CategoryHebergement/list.html.twig', [
            'controller_name' => 'CategoryHebergementController',
            'CategoryHebergements' => $CategoryHebergements,
            'formC' => $form->createView()
        ]);
    }

    #[Route('/showC/{id}', name: 'showC')]
    public function showC(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(CategoryHebergement::class);
        $CategoryHebergement = $repository->find($id);

        return $this->render('CategoryHebergement/detail.html.twig', [
            'controller_name' => 'CategoryHebergementController',
            'CategoryHebergement' => $CategoryHebergement,
        ]);
    }

    #[Route('/admin/deleteC/{id}', name: 'delete_CategoryHebergement')]
    public function deleteClass(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(CategoryHebergement::class);
        $CategoryHebergement = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($CategoryHebergement);
        $em->flush();
        return $this->redirectToRoute("list_CategoryHebergement");
    }
    #[Route('/admin/addC', name: 'addC')]
    public function addCategoryHebergement(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(CategoryHebergement::class);
        $CategoryHebergements = $repository->findAll();
        $CategoryHebergement = new CategoryHebergement();
        $form = $this->createForm(CategoryHebergementType::class, $CategoryHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($CategoryHebergement);
            $em->flush();
            return $this->redirectToRoute('list_CategoryHebergement');
        }
        // return $this->render('classroom/detail.html.twig', [
        //     'formC' => $form->createView()
        // ]);
        return $this->renderForm('CategoryHebergement/list.html.twig', [
            'formC' => $form,
            'CategoryHebergements' => $CategoryHebergements
        ]);
    }
    #[Route('/admin/{id}/edit', name: 'app_CategoryHebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryHebergement $CategoryHebergement, CategoryHebergementRepository $CategoryHebergementRepository): Response
    {
        $form = $this->createForm(CategoryHebergementType::class, $CategoryHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $CategoryHebergementRepository->save($CategoryHebergement, true);

            return $this->redirectToRoute('list_CategoryHebergement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CategoryHebergement/edit.html.twig', [
            'CategoryHebergement' => $CategoryHebergement,
            'formC' => $form,
        ]);
    }
    #[Route('/listCategoryHebergement', name: 'list_Type_Event')]
    public function listMatchs(CategoryHebergementRepository $repository, SerializerInterface $serializerintertface): Response
    {
        $CategoryHebergement =$repository->findAll();
        $serializedData = $serializerintertface->serialize($CategoryHebergement,'json',['groups' => 'CategoryHebergement']);
        return new Response($serializedData, 200, [
            'Content-Type' => 'application/json'
        ]);
       
    }

    #[Route('/addCategoryHebergement/{nomcategory}', name: 'addT', methods: ['GET'])]
    public function AddMatchF($nomcategory)
        {
    
        $CategoryHebergement = new CategoryHebergement();
        $CategoryHebergement->setNomcategory($nomcategory);
        
        
    
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($CategoryHebergement);
        $entityManager->flush();
        
        return new Response('Type added with ID: ' . $CategoryHebergement->getId());
    }

    #[Route('/supprimerCategoryHebergement/{id}', name: 'suppT', methods: ['GET'])]
    public function supprimerT($id, Request $request, CategoryHebergementRepository $repository, ManagerRegistry $doctrine): JsonResponse
    {

        $CategoryHebergement = $repository->find($id);
        $em = $doctrine->getManager();

        /* $idE = $request->get($id);
        $em = $this->getDoctrine()->getManager();
        $equipement = $em->getRepository(Equipement::class)->find($idE);*/
        
            $em->remove($CategoryHebergement);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("Type a ete supprimé avec succées ");
            return new JsonResponse($formated);
       
    }

    #[Route('/updateCategoryHebergement/{id}/{nomcategory}', name: 'updateT', methods: ['GET'])]
    public function updateCategoryHebergement($id, $nomcategory, CategoryHebergementRepository $repository)
        {
        $CategoryHebergement = $repository->find($id);
        $CategoryHebergement->setNomcategory($nomcategory);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($CategoryHebergement);
        $entityManager->flush();
        
        return new Response('Type modifié avec ID: ' . $CategoryHebergement->getId());
    }

}

