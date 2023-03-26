<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Form\UserEditType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Transport;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
use App\Form\ForgotPaswordType;
use App\Form\ChangePasswordType;
use App\Form\ResetPassType;
use App\Service\Mailer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
#[Route('/client',methods: ['GET', 'POST'])]
class ClientController extends AbstractController
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
        $this->SerializerInterface = $SerializerInterface;
    
    }
    
    #[Route('/front', name: 'front',methods: ['GET', 'POST'])]
    public function Front(): Response
    {
        return $this->render('frontTest.html.twig');
    }

    

    #[Route('/frontuser', name: 'client',methods: ['GET', 'POST'])]
    public function Client(): Response
    {
        return $this->render('frontUser.html.twig');
    }

#[Route('/profile/modifier', name: 'clientProfileee',methods: ['GET', 'POST'])]

public function userProfile(ManagerRegistry $doctrine, Request $request, UserRepository $repository, SluggerInterface $slugger,SerializerInterface $SerializerInterface): response
{
    $user= $this->getUser();

    $form=$this->createForm(UserEditType::class,$user);
     
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

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
             $user->setImage($newFilename);
        }
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('client');
    }

    return $this->render('client/index.html.twig', [
        'form' => $form->createView(),
    ]);
}


 #[Route('/client/profile/modifier/{id}', name: 'deleteProfile',methods: ['GET', 'POST'])]
     
public function DeleteUser(EntityManagerInterface $entityManager,User $user, UserRepository $repository,$id,ManagerRegistry $doctrine,Request $request ){

    $session = $request->getSession();

        $user = $repository->find($id);
        $entityManager =$doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

      $session->remove($id);
        return $this->redirectToRoute('registration');

  }

    #[Route('/forgotpassword', name: 'forgot_password',methods: ['GET', 'POST'])]

    public function forgotPassword(UserRepository $repository,ManagerRegistry $doctrine,Request $request, Mailer $mailer,TokenGeneratorInterface $tokenGenerator)
    {   
        
        $form = $this->createForm(ForgotPaswordType::class);
      
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $formData = $form->getData();
              
            $user = $repository->findOneByEmail($formData->getEmail());
            
            // generate a unique token for the password reset link
           if($user){
            $token = $tokenGenerator->generateToken();
            $user ->setToken($token);   
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();  
            $mailer->sendPasswordResetEmail($user->getEmail(), $user->getToken(),$user->getFirstName());
           }
           return $this->redirectToRoute('app_login');
        }

        return $this->render('client/ForgotPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   

    #[Route('/resetPassword/{token}', name: 'resetPass',methods: ['GET'])]
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneBy(["token" => $token]);
        
        if($user){
            $formV = $this->createForm(ChangePasswordType::class);
         
            $formV->handleRequest($request);

            if($formV->isSubmitted() && $formV->isValid()){
                // On efface le token
                $user->setToken(null);
                $user->setPassword($this->passwordHasher->hashPassword($user, $formV->get('password')->getData()));
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('client/changePassword.html.twig', [
                'formV' => $formV->createView(),
                
            ]);
        }
      
        return $this->redirectToRoute('app_login');
    }
}








