<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Repository\PosteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PosteController extends AbstractController
{
    /**
     * @var PosteRepository
     */
    private $posteRepository;

    public function __construct(PosteRepository $posteRepository)
    {

        $this->posteRepository = $posteRepository;
    }

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
     * @Route("poste/create",name="poste_new",methods={"GET","POST"})
     */
    public function create(Request $request)
    {
        $poste=new Poste();
        $form=$this->createFormBuilder($poste)
            ->add('nom_poste')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($poste);
            $em->flush();
            return $this->redirectToRoute('show_poste');

        }
        return $this->render('poste/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @return Response
     * @Route("/show/poste",name="show_poste")
     */
    public function show(){
        $poste=$this->posteRepository->findPoste();
        return $this->render('poste/show.html.twig',[
            'poste'=>$poste
        ]);
    }
    /**
     * @Route("/poste/update/{id}", name="poste_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Poste $poste): Response
    {
        $form=$this->createFormBuilder($poste)
            ->add('nom_poste')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($poste);
            $em->flush();
            return $this->redirectToRoute('show_poste');

        }
        return $this->render('poste/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}
