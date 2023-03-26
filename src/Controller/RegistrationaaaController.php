<?php

namespace App\Controller;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Transport;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Marvin255\RandomStringGenerator\Generator\RandomStringGenerator;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Service\Mailer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class RegistrationaaaController extends AbstractController
{
    private $passwordHasher;
    private $randomStringGenerator;
    private $mailer;
    private $SerializerInterface;
    public function __construct(UserPasswordHasherInterface $passwordHasher,RandomStringGenerator  $randomStringGenerator ,Mailer $mailer,SerializerInterface $SerializerInterface)
    {
        $this->passwordHasher = $passwordHasher;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->mailer =$mailer;
        $this->SerializerInterface =$SerializerInterface;
    }

    #[Route('/mailer', name: 'app_mailer')]
    public function Email(): Response
    {  
        return $this->render('mailer/VerifierEmail.html.twig');
    }

    #[Route('/registration', name: 'registration',methods: ['GET', 'POST'])]

    public function index(Request $request,ManagerRegistry $doctrine, TokenGeneratorInterface $tokenGenerator,SerializerInterface $SerializerInterface )
    { 
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        // $form->add('Add',SubmitType::class);
 

        $form->handleRequest($request);
        $errors = $form->getErrors();
        $user->setDateJoin(new \DateTime('now'));
        $user->setBanned(0);
        $content = $request->getContent();
        // $user=$serializer->deserialize($content,User::class,'json');
        // $json =$SerializerInterface->serialize($user,'json',['groups'=>'user']);
        //       dump($json);
        //         die;
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);
            //  $token = $request->get('_token');
            //  if (!$csrfTokenManager->isTokenValid(new CsrfToken('registration', $token))) {
            //      throw $this->createAccessDeniedException('Invalid CSRF token.');
            // }
            //   $user->setToken($token);
            // Save
            $token = $tokenGenerator->generateToken();
            $user ->setToken($token);
            // $this->randomStringGenerator->alphanumeric(10)
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
           
          $this->mailer->sendEmail($user->getEmail(), $user->getToken());

            
           return $this->redirectToRoute('app_mailer');
           

        }
     
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
           
        ]);
    }

 
    #[Route('/confirmer-mon-compte/{token}', name: 'confirm_account')]
    public function confirmAccount(string $token,ManagerRegistry $doctrine, Request $request, UserRepository $repository)
    {
        $user = $repository->findOneBy(["token" => $token]);
        if($user) {
           
            $user->setToken(null);
            $user->setIsVerified(true);
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $user= $this->getUser();
            $this->addFlash("success", "Compte actif !");
            return $this->redirectToRoute("client");
        } else {
            $this->addFlash("error", "Ce compte n'exsite pas !");
            return $this->redirectToRoute('front');
        }
    }
}
