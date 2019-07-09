<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    /**
     * @Route("/departement", name="departement")
     */
    public function index()
    {
        return $this->render('departement/index.html.twig', [
            'controller_name' => 'DepartementController',
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new",name="departement_new",methods={"GET","POST"})
     */
    public function create(Request $request):Response
    {
        $departement=new Departement();
        $form = $this->createForm(DepartementType::class,$departement);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($departement);
            $entityManager->flush();

            return $this->redirectToRoute('');
        }
       return $this->render('departement/nouveau.html.twig',[
           'departement'=>$departement,
           'form'=>$form->createView(),
       ]);
    }

}
