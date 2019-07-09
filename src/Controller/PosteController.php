<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PosteController extends AbstractController
{
    /**
     * @Route("/poste", name="poste")
     */
    public function index()
    {
        return $this->render('poste/index.html.twig', [
            'controller_name' => 'PosteController',
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("new",name="poste_new",methods={"GET","POST"})
     */
    public function create(Request $request):Response
    {
        $poste= new Poste();
        $form= $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($poste);
            $entityManager->flush();

            return $this->redirectToRoute('');

        }
        return $this->render('poste/new.html.twig',[
            'poste'=> $poste,
            'form'=> $form->createView(),
        ]);
    }

}
