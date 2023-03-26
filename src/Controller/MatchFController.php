<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\MatchFRepository;
use App\Repository\ReservationRepository;
use App\Entity\MatchF;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MatchFType;
use App\Form\MatchFilterType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class MatchFController extends AbstractController
{
    #[Route('/admin/readM', name: 'readMatch')]
    public function readM(MatchFRepository $repository): Response
    {
        $m =$repository->findAll();
        return $this->render('match_f/readM.html.twig', [
            'match' => $m,
        ]);
    }
    

    #[Route('/readMatch', name: 'read_Match')]
    public function readMatch(MatchFRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
       
       
        $donnees = $repository->findBy([], ['dateMatch' => 'DESC']);
        $matches = $paginator->paginate($donnees, $request->query->getInt('page', 1), 5 );

        return $this->render('match_f/readMatch.html.twig', [
            
            'match' => $matches,
        ]);
    }

    /**
    * @Route("/matches/{offset}", name="matches_load_more", requirements={"offset"="\d+"})
    */
    public function loadMoreMatches(MatchFRepository $matchRepository, Request $request, $offset)
    {
        $matches = $matchRepository->findBy([], ['id' => 'DESC']);
        
        return $this->json($matches);
    }

    
    

    #[Route('/admin/detailsM/{id}', name: 'detailsM')]
    public function detailsM(MatchFRepository $repository, $id): Response
    {
        $m =$repository->findByid($id);
        return $this->render('match_f/detailsM.html.twig', [
            'match' => $m,
        ]);
    }


/////////////// CREATE


    #[Route('/admin/createM', name: 'createMatch')]
    public function createM(ManagerRegistry $doctrine, MatchFRepository $repository, Request $request, SluggerInterface $slugger): Response
    {
        $match = new MatchF();
        $form = $this->createForm(MatchFType::class, $match);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())  {

            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

               
                try {
                    $brochureFile->move(
                        $this->getParameter('app.path.product_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $match->setImage($newFilename);
                }

                $brochureFile2 = $form->get('image2')->getData();
            if ($brochureFile2) {
                $originalFilename2 = pathinfo($brochureFile2->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename2 = $slugger->slug($originalFilename2);
                $newFilename2 = $safeFilename2.'-'.uniqid().'.'.$brochureFile2->guessExtension();

               
                try {
                    $brochureFile2->move(
                        $this->getParameter('app.path.product_images'),
                        $newFilename2
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $match->setImage2($newFilename2);
            }
            
        
            $em = $doctrine->getManager();
            $em->persist($match);
            $em->flush();

            return $this->redirectToRoute("readMatch");
        }         
        return $this->renderForm('match_f/createM.html.twig', array('f' => $form));
        
    }


//////////////////////// UPDATE



    #[Route('/admin/updateM/{id}', name: 'updateMatch')]
    public function updateM(ManagerRegistry $doctrine, MatchFRepository $repository, Request $request, $id, SluggerInterface $slugger): Response
    {
        $match = $repository->find($id);
        $form = $this->createForm(MatchFType::class, $match);
        $form->handleRequest($request);
        
        if ($form->isSubmitted())  {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

               
                try {
                    $brochureFile->move(
                        $this->getParameter('equipe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $match->setImage($newFilename);
                }

                $brochureFile2 = $form->get('image2')->getData();
            if ($brochureFile2) {
                $originalFilename2 = pathinfo($brochureFile2->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename2 = $slugger->slug($originalFilename2);
                $newFilename2 = $safeFilename2.'-'.uniqid().'.'.$brochureFile2->guessExtension();

               
                try {
                    $brochureFile2->move(
                        $this->getParameter('equipe_directory'),
                        $newFilename2
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $match->setImage2($newFilename2);
            }
            $em = $doctrine->getManager();
            $em->persist($match);
            $em->flush();
            return $this->redirectToRoute("readMatch");
        }         
        return $this->renderForm('match_f/updateM.html.twig', array('f' => $form));
        
    }


/////////////////////// DELETE


    #[Route('/admin/deleteM/{id}', name: 'deleteMatch')]
    public function deleteM(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, MatchFRepository $repository, ReservationRepository $rep, $id): Response
    {
        $m = $repository->find($id);
        $matchIdToDelete = $m->getId();
        $reservationToDelete = $rep->findBy([
            'matchF' => $matchIdToDelete,
        ]);
        foreach ($reservationToDelete as $reservationToDelete) {
            $entityManager->remove($reservationToDelete);
        }
        $em = $doctrine->getManager();
        $em->remove($m);
        $em->flush();
        return $this->redirectToRoute("readMatch");
        
    }


    

    #[Route('/readMatchFilter', name: 'read_Match_Filter')]
    public function readMatchFilter(MatchFRepository $matchRepository, Request $request): Response
    {
        
        $teamA = $request->query->get('teamA');
        $price = $request->query->get('price');
        $matches = $matchRepository->findByFilters($teamA, $price);
        return $this->render('match_f/readMatch.html.twig', [
            'matches' => $matches,
        ]);
    
    }


    

    ///////////////////////////////////////////////////////////// MOBILE
   
    #[Route('/listMatchs', name: 'list_Match')]
    public function listMatchs(MatchFRepository $repository, SerializerInterface $serializerintertface): Response
    {
        $matches =$repository->findAll();
        $serializedData = $serializerintertface->serialize($matches,'json',['groups' => 'matchF']);
        return new Response($serializedData, 200, [
            'Content-Type' => 'application/json'
        ]);
       
    }

    


   
    
   



    #[Route('/addMatchF/{equipeA}/{equipeB}/{type}/{tournoi}/{stade}/{resultatA}/{resultatB}/{prix}', name: 'addM', methods: ['GET'])]
    public function AddMatchF($equipeA,$equipeB,$type,$stade,$tournoi,$resultatA,$resultatB,$prix)
        {
    
        $match = new MatchF();
        $match->setEquipeA($equipeA);
        $match->setEquipeB($equipeA);
        
        $match->setType($type);
        $match->setStade($stade);
        $match->setTournoi($tournoi);
        $match->setResultatA($resultatA);
        $match->setResultatB($resultatB);
        $match->setPrix($prix);
        
    
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($match);
        $entityManager->flush();
        
        return new Response('Match added with ID: ' . $match->getId());
    }

    #[Route('/supprimerM/{id}', name: 'suppApi', methods: ['GET'])]
    public function supprimerM($id, Request $request, MatchFRepository $repository, ManagerRegistry $doctrine): JsonResponse
    {

        $match = $repository->find($id);
        $em = $doctrine->getManager();

        /* $idE = $request->get($id);
        $em = $this->getDoctrine()->getManager();
        $equipement = $em->getRepository(Equipement::class)->find($idE);*/
        
            $em->remove($match);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("Match a ete supprimé avec succées ");
            return new JsonResponse($formated);
       
    }

    #[Route('/updateMatch/{id}/{equipeA}/{equipeB}/{type}/{tournoi}/{stade}/{resultatA}/{resultatB}/{prix}', name: 'updateM', methods: ['GET'])]
    public function updateMatch($id,$equipeA,$equipeB,$type,$stade,$tournoi,$resultatA,$resultatB,$prix,MatchFRepository $repository)
        {
    
        $match = $repository->find($id);
        $match->setEquipeA($equipeA);
        $match->setEquipeB($equipeA);
        
        $match->setType($type);
        $match->setStade($stade);
        $match->setTournoi($tournoi);
        $match->setResultatA($resultatA);
        $match->setResultatB($resultatB);
        $match->setPrix($prix);
        
    
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($match);
        $entityManager->flush();
        
        return new Response('Match modified with ID: ' . $match->getId());
    }

   

    
}

