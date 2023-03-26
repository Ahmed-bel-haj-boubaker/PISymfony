<?php

namespace App\Controller;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\TransportType;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoryTransport;
use App\Entity\Transport;
use App\Form\CategoryTransportType;
use App\Entity\Hebergement;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Form\HebergementType;
use App\Form\NSCType;
use App\Repository\TransportRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\HebergementRepository;
use Doctrine\ORM\Mapping\Id;

use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
class TransportController extends AbstractController
{
    #[Route('/transport', name: 'transport')]
    public function index(): Response
    {
        return $this->render('transport/index.html.twig', [
            'controller_name' => 'TransportController',
        ]);
    }

#[Route('/admin/AddP', name: 'AddP')]
    public function AddTransport(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine): Response
    {
         $Transport=new Transport();
         $form=$this->createForm(TransportType::class,$Transport);
         $form->add('AddP',SubmitType::class);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
            $photo = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
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
                $Transport->setImageTransport($newFilename);
            }
            $em=$doctrine->getmanager();
            $em->persist($Transport);
            $em->flush();
            return $this->RedirectToRoute('list_Transport');
         }

        return $this->render('transport/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/admin/showS/{id}', name: 'showS')]
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
   #[Route('/admin/listt', name: 'list_Transport')]
    public function listt(TransportRepository $repository,Request $request):Response
    {
        $form=$this->createForm(NSCType::class);
        $Transports=$repository->findTransportByemail();
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()) { 
            $NSC=$form->get('NSC')->getData();
            $Transports=$repository->findTransportByNSC($NSC);
        }
        return $this->render('Transport/list.html.twig', [
            'controller_name' => 'TransportController',
            'Transports' => $Transports,
            'form'=>$form->createView()
            ]);

    }
    #[Route('/listk', name: 'listTransport')]
    public function listA(TransportRepository $repository,Request $request,PaginatorInterface $paginator):Response
    {
        $form=$this->createForm(NSCType::class);

        $Transports=$repository->findTransportByemail();

        $Transports = $paginator->paginate(
            $Transports, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) { 
            $NSC=$form->get('NSC')->getData();
            $Transports=$repository->findTransportByNSC($NSC);
            $Transports=$repository->findTransportByNSC($NSC);

            $Transports = $paginator->paginate(
                $Transports, /* query NOT result */
                $request->query->getInt('page', 1),
                2
            );
        }
        return $this->render('Transport/test.html.twig', [
            'controller_name' => 'TransportController',
            'Transports' => $Transports,
            'form'=>$form->createView()
            ]);

    }
    #[Route('/admin/deleteT/{id}', name: 'deleteTransport')]

    public function deleteTransport(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Transport::class);
        $Transport = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($Transport);
        $em->flush();
        return $this->redirectToRoute("list_Transport");
    }
   /* #[Route('/{id}/editt', name: 'app_Transport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transport $Transport, TransportRepository $TransportRepository): Response
    {
        $form = $this->createForm(TransportType::class, $Transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $TransportRepository->save($Transport, true);

            return $this->redirectToRoute('list_Transport', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Transport/edit.html.twig', [
            'Transport' => $Transport,
            'form' => $form,
        ]);
    }*/
    #[Route('/admin/{id}/editt', name: 'app_Transport_edit')]
    public function edit($id, TransportRepository $TransportRepository, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $Transport = $TransportRepository->find($id);
        $form = $this->createForm(TransportType::class, $Transport);
            $form->add('update', SubmitType::class) ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $TransportRepository->save($Transport, true);

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
                $Transport->setImageTransport($newFilename);
            }
            $em = $doctrine->getManager();
            $em->flush();
            $this->redirectToRoute("list_Transport");
        }
        return $this->renderForm("Transport/edit.html.twig", array("form" => $form));
    }

}