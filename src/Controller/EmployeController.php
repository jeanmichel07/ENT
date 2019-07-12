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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmployeController extends AbstractController
{
    /**
     * @var EmployeRepository
     */
    private $employeRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(EmployeRepository $employeRepository, SessionInterface $session)
    {

        $this->employeRepository = $employeRepository;
        $this->session = $session;
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
            $pass=sha1($employe->getPassword());
            $employe->setPassword($pass);
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/login/employe", name="login_employe")
     */
    public function loginEmploye(Request $request)
    {
        $formLogin=$this->createFormBuilder()
            ->add('username',TextType::class,[
                'label'=>' ',
                'attr'=>[
                    'placeholder'=>'Utilisateur'
                ]
            ])
            ->add('password',PasswordType::class,[
                'label'=>' ',
                'attr'=>[
                    'placeholder'=>'Mot de passe'
                ]
            ])
            ->getForm();

        $formLogin->handleRequest($request);

        if($formLogin->isSubmitted() && $formLogin->isValid())
        {
            $username=$request->request->get("form")['username'];
            $password=$request->request->get("form")['password'];
            $dirigean=$this->employeRepository->findemp($username,$password);
            if(count($dirigean)===1){
                $session=$this->session;
                $session->set('username_employe',$username);
                $sessUsername=$session->get('username');
                    return $this->redirectToRoute('employe_accueil');
            }else{
                $erro="Username ou mot de passe incorrect";
                return $this->render('employe/login.html.twig', [
                    'form'=>$formLogin->createView(),
                    'err'=>$erro
                ]);
            }

        }

        return $this->render('employe/login.html.twig', [
            'form'=>$formLogin->createView()
        ]);
    }
    /**
     * @Route("/lougout/employe", name="logoutemploue")
     */
    public function logout(){
        $this->session->clear();
        return $this->redirectToRoute('user');
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

    /**
     * @param Employe $employe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/read/employe/{id}",name="employe_delete")
     */
    public function delete(Employe $employe){
        if (isset($employe)){
            $em=$this->getDoctrine()->getManager();
            $em->remove($employe);
            $em->flush();
            return $this->redirectToRoute('read_employe');
        }

    }

    /**
     * @param Employe $employe
     * @return Response
     * @Route("voir/employe/{id}",name="employe_voir")
     */
    public function voir(Employe $employe){
        return $this->render('employe/voir.html.twig',[
            'e'=>$employe
        ]);

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/acceuil/employe",name="employe_accueil")
     */
    public function accueil(Request $request)
    {
        return $this->render('employe/accueil.html.twig');
    }

}
