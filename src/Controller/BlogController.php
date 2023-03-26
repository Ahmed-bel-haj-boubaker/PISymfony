<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Form\BlogFormType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
 use Knp\Component\Pager\PaginatorInterface;
 

use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use SebastianBergmann\CodeUnit\FunctionUnit;

class BlogController extends AbstractController
{ 

#[Route('/admin/readblog', name: 'read_blog')]
public function read(BlogRepository $repository, Request $request,PaginatorInterface $paginator ): Response
{
    $blogs = $repository->findAll();
     $length = intdiv(count($blogs),2);
     $pagination = $paginator->paginate(
        $blogs, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
       $length/*limit per page*/
    );
    return $this->render('blog/read.html.twig',
        ["blogs" => $pagination]);
}



#[Route('/readblogfront', name: 'readblogfront')]
public function readfront(BlogRepository $repository, Request $request,PaginatorInterface $paginator): Response
{
    $blogs = $repository->findAll();
    $length = intdiv(count($blogs),2);
    $pagination = $paginator->paginate(
       $blogs, /* query NOT result */
       $request->query->getInt('page', 1), /*page number*/
      $length/*limit per page*/
   );
    return $this->render('blog/read_front.html.twig',
        ["blogs" => $pagination]);
}



#[Route('/admin/update/{id}', name: 'update_blog')]
public function  update(ManagerRegistry $doctrine,$id,  Request  $request,SluggerInterface $slugger) : Response
{ $blog = $doctrine
    ->getRepository(blog::class)
    ->find($id);
    $form = $this->createForm(blogFormType::class, $blog);

    $form->handleRequest($request);
    if ($form->isSubmitted())
    {  $photo = $form->get('image')->getData();

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
            $blog->setImage($newFilename);
        }
        
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('read_blog');
    }
    return $this->renderForm("blog/update.html.twig",
        ["f"=>$form]) ;
}



#[Route("/blogdetails/{id}", name:'blogdetails')]
public function detail($id, BlogRepository $repository, CommentaireRepository $rep,EntityManagerInterface $doctrine,  Request  $request )
{    $blogs = $repository->findByid($id);

    // $resultOfSearch = $rep->findByExampleField($id);
    $commentaires = $rep->findBy(array('blog'=>$id));
    $commentaire = new Commentaire();
    $form = $this->createForm(CommentaireFormType::class, $commentaire);
    //$form->add('add',SubmitType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
     $commentaire->setUser($this->getUser());
        $commentaire->setDateC(new \DateTime("now"));
        $p = $doctrine->getRepository(Blog::class)->findBy(["id" => $request->get('id')])[0];
        $commentaire->setBlog($p);
        $doctrine->persist($commentaire);
        $doctrine->flush();

        return $this->redirectToRoute('readblogfront', ["id"=>$request->get("id")], Response::HTTP_SEE_OTHER);
    }

    return $this->render('blog/show_front.html.twig', [
        'blogs' => $blogs,
        'form' =>$form->createView(),
        'commentaire' => $commentaire,
        'commentaires' => $commentaires,
    ]);
    // return $this->render('blog/show_front.html.twig',
    //     ["blogs" => $blogs,"comments"=>$resultOfSearch]);
}


#[Route("/admin/deleteblog/{id}", name:'delete_blog')]
public function delete($id, ManagerRegistry $doctrine,EntityManagerInterface $entityManager,CommentaireRepository $rep )
{  $c = $doctrine
    ->getRepository(blog::class)
    ->find($id);
    $blogIdToDelete = $c->getId();
        $commentaireToDelete = $rep->findBy([
            'blog' => $blogIdToDelete,
        ]);
        foreach ($commentaireToDelete as $commentaireToDelete) {
            $entityManager->remove($commentaireToDelete);
        }

    $em = $doctrine->getManager();
    $em->remove($c);
    $em->flush() ;
    return $this->redirectToRoute('read_blog');
}



#[Route('/admin/Add', name: 'add_blog')]
public function  add(Request $request,SluggerInterface $slugger,ManagerRegistry $doctrine) : Response
{ $blog = new blog() ;
    $form = $this->createForm(blogFormType::class, $blog);
    // $form->add('ajouter', SubmitType::class) ;
    $form->handleRequest($request);
    if ($form->isSubmitted()&&$form->isValid())

    {
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
                $blog->setImage($newFilename);
            }
         $em = $doctrine->getManager();
        $em->persist($blog);
        $em->flush();
        return $this->redirectToRoute('read_blog');
    }
    return $this->renderForm("blog/add.html.twig",
        ["f"=>$form]) ;


}




#[Route('/findblog', name: 'find_blog')]
public function find(BlogRepository $rep, Request $request): Response
{   $blogs = $rep->findAll();
    if ($request->isMethod("post")) {
        $titre=$request->get('titre');
        $resultOfSearch = $rep->findByExampleField($titre);
        return $this->render('blog/serach.html.twig', [
            'blogs' => $resultOfSearch]);
    }

    return $this->render("blogs/read.html.twig",
        ["blogs"=>$blogs]);
}






    
}