<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Hebergement;

class HebergementmobileController extends AbstractController
{
    /**
     * @Route("/Hebergementmobile", name="Hebergementmobile")
     */
    public function Hebergementmobileindex( NormalizerInterface  $normalizer)
    {

        $Hebergement = $this->getDoctrine()->getRepository(Hebergement::class)->findAll();
        $json = $normalizer->normalize($Hebergement, "json", ['groups' => ['Hebergement','Hebergement']]);
        return new JsonResponse($json);
    }

      /**
     * @Route("/SupprimerHebergement", name="SupprimerHebergement")
     */
    public function SupprimerHebergement(Request $request)
    {

        $idE = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Hebergement = $em->getRepository(Hebergement::class)->find($idE);
        if($Hebergement != null)                         
        {
            $em->remove($Hebergement);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("Hebergement ete supprimer avec succées ");
            return new JsonResponse($formated);
        }

    }

    
       /**
     * @Route("/newHebergement_mobile/{nomHeberg}/{deschebergement}", name="newHebergement_mobile", methods={"GET","POST"})
     */
    public function newHebergement($tite,$descreption,$contenu,NormalizerInterface  $normalizer )
    {

        $Hebergement = new Hebergement();
        $Hebergement->setNomHeberg($nomHeberg);
        $Hebergement->setDescHebergement($deschebergement);
       // $Hebergement->setContenu($contenu);



        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Hebergement);
        $entityManager->flush();
        $json = $normalizer->normalize($Hebergement, "json", ['groups' => ['Hebergement']]);
        return new JsonResponse($json);
    }

     /******************Modifier Hebergement*****************************************/
    /**
     * @Route("/updateHebergement", name="updateHebergement")
     */
    public function updateHebergement(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Hebergement = $this->getDoctrine()->getManager()->getRepository(Hebergement::class)->find($request->get("id"));


        $Hebergement->setNomHeberg($request->get("nomHeberg"));
        $Hebergement->setDescHebergement($request->get("deschebergement"));
       // $Hebergement->setContenu($request->get("contenu"));


        $em->persist($Hebergement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Hebergement);
        return new JsonResponse("Hebergement a ete modifiee avec success.");

    }






}
