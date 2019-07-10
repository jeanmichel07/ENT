<?php

namespace App\Controller;

use App\Repository\DirigeantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirigeantController extends AbstractController
{

    /**
     * @var DirigeantRepository
     */
    private $repository;

    public function __construct(DirigeantRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/login/dirigeant", name="login_dirigeant")
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $dirigean=$this->repository->findDiri($request->get('username'),$request->get('password'));

        $formLogin=$this->createFormBuilder()
        ->add('username')
        ->add('password',PasswordType::class)
        ->getForm();

        return $this->render('dirigeant/login.html.twig', [
            'form'=>$formLogin->createView()
        ]);
    }
}
