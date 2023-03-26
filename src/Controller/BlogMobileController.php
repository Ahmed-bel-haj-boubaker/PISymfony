<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Blog;

class BlogMobileController extends AbstractController
{
    /**
     * @Route("/blogmobile", name="blogmobile")
     */
    public function blogmobileindex( NormalizerInterface  $normalizer)
    {

        $blog = $this->getDoctrine()->getRepository(Blog::class)->findAll();
        $json = $normalizer->normalize($blog, "json", ['groups' => ['blog','blog']]);
        return new JsonResponse($json);
    }

      /**
     * @Route("/SupprimerBlog/{id}", name="SupprimerBlog")
     */
    public function SupprimerBlog(Request $request,$id, ManagerRegistry $doctrine)
    {

        $blog = $doctrine
        ->getRepository(blog::class)
        ->find($id);
        if($blog != null)                         
        {
            $em = $doctrine->getManager();
            $em->remove($blog);
            $em->flush() ;
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("blog ete supprimer avec succées ");
            return new JsonResponse($formated);
        }

    }

    
       /**
     * @Route("/newblog_mobile/{titre}/{descreption}/{contenu}", name="newblog_mobile")
     */
    public function add($titre,$descreption,$contenu,NormalizerInterface  $normalizer,ManagerRegistry $doctrine )
    {

        $blog = new Blog();
        $blog->setTitre($titre);
        $blog->setDescreption($descreption);
        $blog->setContenu($contenu);



        $em = $doctrine->getManager();
        $em->persist($blog);
        $em->flush();
        $json = $normalizer->normalize($blog, "json", ['groups' => ['blog']]);
        return new JsonResponse($json);
    }

     /******************Modifier Blog*****************************************/
    /**
     * @Route("/updateBlog", name="updateBlog")
     */
    public function updateBlog(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $blog = $this->getDoctrine()->getManager()->getRepository(Blog::class)->find($request->get("id"));


        $blog->setTitre($request->get("tite"));
        $blog->setdescreption($request->get("descreption"));
        $blog->setContenu($request->get("contenu"));


        $em->persist($blog);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($blog);
        return new JsonResponse("blog a ete modifiee avec success.");

    }






}
