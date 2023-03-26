<?php

namespace App\Controller;
use App\form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout( UserRepository $repository): void
    {       
        
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // #[Route('/changePass', name: 'changePass')]
    // public function ChangePassword(Request $request, UserPasswordHasherInterface $hasher): Response
    // {
    //     $user = $this->getUser();

    //     if (!$user) {
    //         return $this->redirectToRoute('app_login');
    //     }

    //     $formPass = $this->createform(ChangePasswordType::class);
    //  $formPass->add('Add',SubmitType::class);
    //     $formPass->handleRequest($request);

    //     if ($formPass->isSubmitted() && $formPass->isValid()) {
    //         $data = $formPass->getData();
    //         $newhashedPassword = $hasher->encodePassword($user, $data['newPassword']);

    //         $user->setPassword($newhashedPassword);
    //         $this->getDoctrine()->getManager()->flush();

    //         $this->addFlash('success', 'Password changed successfully!');

    //         return $this->redirectToRoute('admin');
    //     }

    //     return $this->render('admin/changePassword.html.twig', [
    //         'formPass' => $formPass->createView(),
    //     ]);
    // }
    
}
