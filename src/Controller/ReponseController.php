<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SendMailService;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'reponse_add', methods: ['GET', 'POST'])]
    public function new(Request $request, ReponseRepository $reponseRepository ): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $transport = Transport::fromDsn('smtp://localhost');
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('cherifrayenbs@gmail.com')
    ->to('cherifrayen05@gmail.com')
    //->cc('cc@example.com')
    //->bcc('bcc@example.com')
    //->replyTo('fabien@example.com')
    //->priority(Email::PRIORITY_HIGH)
    ->subject('Time for Symfony Mailer!')
    ->text('Sending emails is fun again!')
    ->html('<p>See Twig integration for better HTML integration!</p>');

$mailer->send($email);
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/add.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponseRepository->save($reponse, true);
            
            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'Repdelete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $reponseRepository->remove($reponse, true);
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }





#[Route('/listReponse', name: 'list_Type_Event')]
    public function listMatchs(ReponseRepository $repository, SerializerInterface $serializerintertface): Response
    {
        $Reponse =$repository->findAll();
        $serializedData = $serializerintertface->serialize($Reponse,'json',['groups' => 'Reponse']);
        return new Response($serializedData, 200, [
            'Content-Type' => 'application/json'
        ]);
       
    }

    #[Route('/addReponse/{reponse}', name: 'addT', methods: ['GET'])]
    public function AddMatchF($reponse)
        {
    
        $Reponse = new Reponse();
        $Reponse->setReponse($reponse);
        
        
    
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Reponse);
        $entityManager->flush();
        
        return new Response('Type added with ID: ' . $Reponse->getId());
    }

    #[Route('/supprimerReponse/{id}', name: 'suppT', methods: ['GET'])]
    public function supprimerT($id, Request $request, ReponseRepository $repository, ManagerRegistry $doctrine): JsonResponse
    {

        $Reponse = $repository->find($id);
        $em = $doctrine->getManager();

        /* $idE = $request->get($id);
        $em = $this->getDoctrine()->getManager();
        $equipement = $em->getRepository(Equipement::class)->find($idE);*/
        
            $em->remove($Reponse);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("Type a ete supprimé avec succées ");
            return new JsonResponse($formated);
       
    }

    #[Route('/updateReponse/{id}/{reponse}', name: 'updateT', methods: ['GET'])]
    public function updateReponse($id, $reponse, ReponseRepository $repository)
        {
        $Reponse = $repository->find($id);
        $Reponse->setReponse($reponse);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Reponse);
        $entityManager->flush();
        
        return new Response('Type modifié avec ID: ' . $Reponse->getId());
    }

}
