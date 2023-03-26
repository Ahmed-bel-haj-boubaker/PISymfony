<?php

namespace App\Controller;
use App\Entity\Hebergement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Form\HebergementType;
use App\Form\NSCType;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\HebergementRepository;
use Doctrine\ORM\Mapping\Id;

use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class HebergementController extends AbstractController
{
    #[Route('/hebergement', name: 'app_hebergement')]
    public function index(): Response
    {
        return $this->render('hebergement/index.html.twig', [
            'controller_name' => 'HebergementController',
        ]);
    }

 
    #[Route('/admin/AddH', name: 'AddHebergement')]
    public function AddHebergement(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine): Response
    {
         $hebergement=new Hebergement();
         $form=$this->createForm(HebergementType::class,$hebergement);
         $form->add('Add',SubmitType::class);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
             $photo = $form->get('image')->getData();

            // // this condition is needed because the 'brochure' field is not required
            // // so the PDF file must be processed only when a file is uploaded
             if ($photo) {
               $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
             $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

            //     // Move the file to the directory where brochures are stored
                try {
                   $photo->move(
                       $this->getParameter('app.path.product_images'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }

                // updates the 'brochureFilename' property to store the PDF file name
             // instead of its contents
                 $hebergement->setImage($newFilename);
             }
            $em=$doctrine->getmanager();
            $em->persist($hebergement);
            $em->flush();
            return $this->RedirectToRoute('list_Hebergement');
         }

        return $this->render('hebergement/Addhebergement.html.twig', [
            'form' => $form->createView()
        ]);
    
}
#[Route('/admin/deleteS/{id}', name: 'delete_Hebergement')]
    public function deleteHebergement(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Hebergement::class);
        $Hebergement = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($Hebergement);
        $em->flush();
        return $this->redirectToRoute("list_Hebergement");
    }
#[Route('/showS/{id}', name: 'showS')]
    public function showS(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Hebergement::class);
        $Hebergement = $repository->find($id);
        return $this->redirectToRoute("showS");

        return $this->render('Hebergement/detail.html.twig', [
            'controller_name' => 'HebergementController',
            'Hebergement' => $Hebergement,
        ]);
    }
   #[Route('/admin/listS', name: 'list_Hebergement')]
    public function listS(HebergementRepository $repository,Request $request):Response
    {
        $form=$this->createForm(NSCType::class);

        $Hebergements=$repository->findHebergementByemail();
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) { 
            $NSC=$form->get('NSC')->getData();
            $Hebergements=$repository->findHebergementByNSC($NSC);

        }
        return $this->render('Hebergement/list.html.twig', [
            'controller_name' => 'HebergementController',
            'Hebergements' => $Hebergements,
            'form_NSC'=>$form->createView()
            ]);

    }
    #[Route('/listA', name: 'listHebergement')]
    public function listA(HebergementRepository $repository,Request $request,PaginatorInterface $paginator):Response
    {
        $form=$this->createForm(NSCType::class);

        $Hebergements=$repository->findHebergementByemail();

        $Hebergements = $paginator->paginate(
            $Hebergements, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) { 
            $NSC=$form->get('NSC')->getData();
            $Hebergements=$repository->findHebergementByNSC($NSC);
            $Hebergements=$repository->findHebergementByNSC($NSC);

            $Hebergements = $paginator->paginate(
                $Hebergements, /* query NOT result */
                $request->query->getInt('page', 1),
                2
            );
        }
        return $this->render('Hebergement/test.html.twig', [
            'controller_name' => 'HebergementController',
            'Hebergements' => $Hebergements,
            'form_NSC'=>$form->createView()
            ]);

    }
  
    #[Route('/map', name: 'map')]
    public function map(): Response
    {
        return $this->render('Hebergement/map.html.twig', [
            'controller_name' => 'HebergementController',

        ]);
    }
#[Route('/recherche/{id}', name: 'recherche')]
public function afficherListeEtudiant_Class(HebergementRepository $repository,ManagerRegistry $doctrine,$id){
    $Hebergements=$repository->ListHebergementByClass($id);
    $repositoryC = $doctrine->getRepository(Hebergement::class);
    $Hebergement = $repositoryC->find($id);
    return $this->render('Hebergement/list.html.twig', [
        'controller_name' => 'HebergementController',
        'Hebergements' => $Hebergements,
        'class'=>$Hebergement]);

}

/*#[Route('/update/{id}', name: 'app_Hebergementedit')]
public function  update(ManagerRegistry $doctrine,$id,  Request  $request) : Response
{ $blog = $doctrine
    ->getRepository(blog::class)
    ->find($id);
    $form = $this->createForm(blogFormType::class, $blog);
    $form->add('update', SubmitType::class) ;
    $form->handleRequest($request);
    if ($form->isSubmitted())
    { $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('read_blog');
    }
    return $this->renderForm("blog/update.html.twig",
        ["f"=>$form]) ;
}
*/
#[Route('/admin/{id}/editH', name: 'app_Hebergementedit')]
    public function edit($id, HebergementRepository $HebergementRepository, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $Hebergement = $HebergementRepository->find($id);
        $form = $this->createForm(HebergementType::class, $Hebergement);
            $form->add('update', SubmitType::class) ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $HebergementRepository->save($Hebergement, true);

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('app.path.product_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $Hebergement->setImage($newFilename);
            }
            $em = $doctrine->getManager();
            
            $em->flush();
           return $this->redirectToRoute('list_Hebergement');
        }
        return $this->renderForm("Hebergement/edit.html.twig", array("form_NSC" => $form));
    }

}