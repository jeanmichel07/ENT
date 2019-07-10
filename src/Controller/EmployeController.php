<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Employe;
use App\Entity\Poste;
use App\Repository\EmployeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmployeController extends AbstractController
{
    /**
     * @var EmployeRepository
     */
    private $employeRepository;

    public function __construct(EmployeRepository $employeRepository)
    {

        $this->employeRepository = $employeRepository;
    }

    /**
     * @Route("/employe", name="employe")
     */
    public function index()
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/employe/new", name="nouveau_employe")
     */
    public function nouveau(){
        return $this->render('employe/new.html.twig');
    }
    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/new/employe", name="new_employe")
     */
    public  function create(Request $request){
        $employe=new Employe();
        $form=$this->createFormBuilder($employe)
        ->add('departement',EntityType::class,[
            'class'=>Departement::class,
            'query_builder'=>function(EntityRepository $entityRepository){
            return $entityRepository->createQueryBuilder('d')->orderBy('d.nom_dep','ASC');

            }

        ])
        ->add('poste',EntityType::class,[
                'class'=>Poste::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('p')->orderBy('p.nom_poste','ASC');

                }

            ])
        ->add('nom')
        ->add('num_matricule')
        ->add('username')
        ->add('password')
        ->getForm();
    $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('read_employe');
        }
        return $this->render('employe/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/read/employe",name="read_employe")
     */
    public  function read(){
     $employe=$this->employeRepository->findEmploye();
     return $this->render('employe/read.html.twig',[
         'employe'=>$employe
     ]);
    }
    /**
     * @Route("/employe/update/{id}", name="employe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Employe $employe): Response
    {

        $form=$this->createFormBuilder($employe)
            ->add('departement',EntityType::class,[
                'class'=>Departement::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('d')->orderBy('d.nom_dep','ASC');

                }

            ])
            ->add('poste',EntityType::class,[
                'class'=>Poste::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('p')->orderBy('p.nom_poste','ASC');

                }

            ])
            ->add('nom')
            ->add('num_matricule')
            ->add('username')
            ->add('password')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('read_employe');
        }
        return $this->render('employe/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}
