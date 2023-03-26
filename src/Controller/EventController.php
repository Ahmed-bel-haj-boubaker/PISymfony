<?php

namespace App\Controller;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\EventRepository;
use App\Repository\EventLikeRepository;
use App\Entity\Event;
use App\Entity\EventLike;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\Bridge\Toastr\ToastrOptions;
use Symfony\Component\Notifier\Bridge\Toastr\ToastrTransportFactory;
use Symfony\Component\Security\Core\UserInterface;
use Symfony\Component\Security\Core\Security;
class EventController extends AbstractController
{

    #[Route('/admin/readE', name: 'readEvent')]
    public function readE(EventRepository $repository): Response
    {
        $e =$repository->findAll();
        return $this->render('event/readE.html.twig', [
            'event' => $e,  
        ]);
    }
    #[Route('/listeE', name: 'listeE')]
    public function listeE(EventRepository $repository,SerializerInterface $SerializerInterface)
    {
        $event =$repository->findAll();
        $json=$SerializerInterface->Serialize($event,'json',['groups'=>'event'] );
        dump($json);
        die;
        
    }


    #[Route('/admin/detailsE/{id}', name: 'detailsE')]
    public function detailsE(EventRepository $repository, $id): Response
    {
        $event = $repository->findByid($id);
        return $this->render('event/detailsE.html.twig', [
            'event' => $event,
        ]);
    }
  


/////////////// CREATE


    #[Route('/admin/createE', name: 'createEvent')]
    
    public function createE(ManagerRegistry $doctrine, EventRepository $repository, Request $request, SluggerInterface $slugger): Response
    {

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())  {
        //    dd($form->get('video')->getData());
        
   

        $photo = $form->get('image')->getData();

        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($photo) {
            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

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
            $event->setImage($newFilename);
        }
            $em = $doctrine->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("readEvent");
        }         
        
        return $this->renderForm('event/createE.html.twig', array('f' => $form));
        
    }
    // public function addEvent(Request $request, NotifierInterface $notifier): Response
    // {
    //     // ...

    //     // Create a new event entity
    //     $event = new Event();
    //     // Set the event properties
    //     // ...

    //     // Save the event to the database
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->persist($event);
    //     $entityManager->flush();

    //     // Send a notification about the new event
    //     $notification = new Notification(
    //         'New event created',
    //         new ToastrOptions(
    //             'success', 
    //             'New event "'.$event->getNomEvent().'" added'
    //         )
    //     );
    //     $notifier->send($notification, new Recipient('admin@example.com'));

    //     // ...

    //     // Return a response
    //     return $this->redirectToRoute('event_index');
    // }


//////////////////////// UPDATE



    #[Route('/admin/updateE/{id}', name: 'updateEvent')]
    public function updateE(ManagerRegistry $doctrine, EventRepository $repository, Request $request, $id): Response
    {
        $event = $repository->find($id);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())  {
            $em = $doctrine->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("readEvent");
        }         
        return $this->renderForm('event/updateE.html.twig', array('f' => $form));
        
    }


/////////////////////// DELETE


    #[Route('/admin/deleteE/{id}', name: 'deleteEvent')]
    public function deleteE(ManagerRegistry $doctrine, EventRepository $repository, $id): Response
    {
        $event = $repository->find($id);
        $em = $doctrine->getManager();
        
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute("readEvent");
        
    }
 

    #[Route('/readEvent', name: 'read_Event')]
    public function readEvent(EventRepository $repository): Response
    {
        $e =$repository->findAll();
        return $this->render('event/readEvent.html.twig', [
            'event' => $e,
        ]);
    }
    /**
    * @Route("/events", name="event_list")
    */
    public function list(Request $request,ManagerRegistry $doctrine)
    {
        $start = $request->query->get('start');

        $events = $doctrine->getRepository(Event::class)->findAll();

        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'title' => $event->getNomEvent(),
                'start' => $event->getDateEvent()->format('Y-m-d H:i:s')
            ];
        }

    return new JsonResponse($data);
    }

    #[Route('/detailsEvent/{id}', name: 'detailsEvent')]
    public function detailsEvent(EventRepository $repository, $id): Response
    {
        $e =$repository->findByid($id);

        return $this->render('event/detailsEvent.html.twig', [
            'event' => $e,
            
        ]);
    }


    #[Route('/calendrier', name: 'calendrier')]
    public function index(EventRepository $repository): Response
    {
        
        return $this->render('event/calendrier.html.twig');
    }

    #[Route('/addEv', name: 'addEv')]
    public function addEv(Request $request,SerializerInterface $SerializerInterface,EntityManagerInterface $em)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,Event::class,'json');
        $em->persist($data);
        $em->flush();
        return new Response('event added successfully');
    }
    #[Route('/event/{id}/like', name: 'event_like', methods: ['GET', 'POST'])]
    public function like(Event $event, ObjectManager $manager, EventLikeRepository $likeRepo): Response
    {
        $like = $likeRepo->findOneBy([
            'event' => $event,
            'user' => null // replace null with the id of the current user
        ]);

        if ($like) {
            $manager->remove($like);
            $manager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'like removed',
                'likes' => $likeRepo->count()
            ], 200);
        }

        $like = new EventLike();
        $like->setEvent($event);
        $like->setUser(null); // replace null with the id of the current user

        $manager->persist($like);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'like added',
            'likes' => $likeRepo->count(['event' => $event])
        ], 200);
    }


    
 

}

 


