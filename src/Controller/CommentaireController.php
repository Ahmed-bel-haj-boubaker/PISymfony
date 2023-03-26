<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Blog;
use App\Form\CommentaireFormType;
use App\Repository\CommentaireRepository;
use App\Repository\BlogRepository;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

#[Route('/admin/readcommentaire', name: 'read_commentaire')]
public function read(commentaireRepository $repository): Response
{
    $commentaires = $repository->findAll();
    return $this->render('commentaire/read.html.twig',
        ["commentaires" => $commentaires]);
}




#[Route('/admin/updatec/{id}', name: 'update_commentaire')]
public function  update(ManagerRegistry $doctrine,$id,  Request  $request) : Response
{ $commentaire = $doctrine
    ->getRepository(commentaire::class)
    ->find($id);
    $form = $this->createForm(commentaireFormType::class, $commentaire);
    $form->add('update', SubmitType::class) ;
    $form->handleRequest($request);
    if ($form->isSubmitted()&&$form->isValid())

    { $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('read_commentaire');
    }
    return $this->renderForm("commentaire/update.html.twig",
        ["f"=>$form]) ;
}



#[Route("/admin/deletec/{id}", name:'delete_commentaire')]
public function delete($id, ManagerRegistry $doctrine)
{$c = $doctrine
    ->getRepository(commentaire::class)
    ->find($id);
    $em = $doctrine->getManager();
    $em->remove($c);
    $em->flush() ;
    return $this->redirectToRoute('read_commentaire');
}



#[Route('/admin/addc', name: 'add_commentaire')]
public function  add(ManagerRegistry $doctrine, Request  $request) : Response
{ $commentaire = new commentaire() ;
    $form = $this->createForm(commentaireFormType::class, $commentaire);
    $form->add('ajouter', SubmitType::class) ;
    $form->handleRequest($request);
    if ($form->isSubmitted()&&$form->isValid()
    )
    { $em = $doctrine->getManager();
        $em->persist($commentaire);
        $em->flush();
        return $this->redirectToRoute('read_commentaire');
    }
    return $this->renderForm("commentaire/add.html.twig",
        ["f"=>$form]) ;


}

#[Route('/addtest', name: 'add_commentairetest')]
public function  add_commentairetest(ManagerRegistry $doctrine, Request  $request) : Response
{ $commentaire = new commentaire() ;
    $form = $this->createForm(commentaireFormType::class, $commentaire);
    $form->add('ajouter', SubmitType::class) ;
    $form->handleRequest($request);
    if ($form->isSubmitted())
    { $em = $doctrine->getManager();
        $em->persist($commentaire);
        $em->flush();
        return $this->redirectToRoute('readblogfront');
    }
    return $this->renderForm("commentaire/add_front.html.twig",
        ["f"=>$form]) ;


}

#[Route('/addfont/{id}', name: 'add_commentairefront')]
public function  addfont(ManagerRegistry $doctrine,$id,  Request  $request) : Response
{ $commentaire = $doctrine
    ->getRepository(blog::class)
    ->find($id);
    $form = $this->createForm(commentaireFormType::class, $commentaire);
    $form->add('ajouter', SubmitType::class) ;
    $form->handleRequest($request);
    if ($form->isSubmitted())
    { $em = $doctrine->getManager();
        $em -> persist($commentaire);
        $em->flush();
        return $this->redirectToRoute('read_commentaire');
    }
    return $this->renderForm("commentaire/update.html.twig",
        ["f"=>$form]) ;
}


}
