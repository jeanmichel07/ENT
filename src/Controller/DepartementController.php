<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    /**
     * @var DepartementRepository
     */
    private $departementRepositiry;

    public function __construct(DepartementRepository $departementRepositiry)
    {

        $this->departementRepositiry = $departementRepositiry;
    }

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
     * @Route("/departement/nouveau",name="departement_new",methods={"GET","POST"})
     */
    public function create(Request $request)
    {
        $departement=new Departement();
        $form=$this->createFormBuilder($departement)
            ->add('nom_dep')
            ->add('description')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($departement);
            $em->flush();
            return $this->redirectToRoute('liste_departement');
            
        }
        return $this->render('departement/nouveau.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @return Response
     * @Route("/liste/departement",name="liste_departement")
     */
    public function liste(){
        $departement=$this->departementRepositiry->findDepartement();
        return $this->render('departement/liste.html.twig',[
            'departement'=>$departement
        ]);
    }
    /**
     * @Route("/departement/update/{id}", name="departement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Departement $departement): Response
    {

        $form=$this->createFormBuilder($departement)
            ->add('nom_dep')
            ->add('description')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($departement);
            $em->flush();
            return $this->redirectToRoute('liste_departement');

        }
        return $this->render('departement/update.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    }