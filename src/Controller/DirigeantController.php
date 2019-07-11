<?php

namespace App\Controller;

use App\Entity\Dirigeant;
use App\Repository\DirigeantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DirigeantController extends AbstractController
{

    /**
     * @var DirigeantRepository
     */
    private $repository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(DirigeantRepository $repository,SessionInterface $session)
    {
        $this->repository = $repository;
        $this->session = $session;
    }

    /**
     * @Route("/", name="user")
     */
    public function user()
    {
        return $this->render('dirigeant/user.html.twig');
    }


    /**
     * @Route("/login/dirigeant", name="login_dirigeant")
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {

        $formLogin=$this->createFormBuilder()
        ->add('username',TextType::class)
        ->add('password',PasswordType::class)
        ->getForm();

        $formLogin->handleRequest($request);

        if($formLogin->isSubmitted() && $formLogin->isValid())
        {
            $username=$request->request->get("form")['username'];
            $password=$request->request->get("form")['password'];
            $dirigean=$this->repository->findDiri($username,$password);
            if(count($dirigean)===1){
                $session=$this->session;
                $session->set('username_dirigeant',$username);
                $sessUsername=$session->get('username');

                return $this->redirectToRoute('accueil');

            }else{
                $erro="Username ou mot de passe incorrect";
                return $this->render('dirigeant/login.html.twig', [
                    'form'=>$formLogin->createView(),
                    'err'=>$erro
                ]);
            }

        }

        return $this->render('dirigeant/login.html.twig', [
            'form'=>$formLogin->createView()
        ]);
    }

    /**
     * @Route("/lougout", name="logout")
     */
    public function logout(){
        $this->session->clear();
        return $this->redirectToRoute('user');
    }

    /**
     * @Route("/accueil/dirigeant", name="accueil")
     * @param Request $request
     * @return Response
     */
    public function accueil(Request $request)
    {
          return $this->render('dirigeant/accueil.html.twig');
    }

    /**
     * @param Request $request
     * @Route("/create/dirigeant", name="new_dirigeant")
     * @return Response
     */
    public function create(Request $request)
    {
        $dirigean=new Dirigeant();
        $form=$this->createFormBuilder($dirigean)
            ->add('nom')
            ->add('username')
            ->add('password',PasswordType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $em=$this->getDoctrine()->getManager();
           $pass=sha1($dirigean->getPassword());

           $dirigean->setPassword($pass);
           $em->persist($dirigean);
           $em->flush();
           return $this->redirectToRoute('login_dirigeant');

        }

        return $this->render('dirigeant/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }


}
