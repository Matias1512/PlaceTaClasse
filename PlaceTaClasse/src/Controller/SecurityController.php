<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: "/inscription", name: "app_inscription")]
    public function inscription(Request $requeteHttp, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $formulaireUser=$this->createForm(UserType::class, $user);
        $formulaireUser->handleRequest($requeteHttp);

        if ($formulaireUser->isSubmitted()&&$formulaireUser->isValid())
        {
            $user->setRoles(['ROLE_USER']);
            $encodageMdp= $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($encodageMdp);

            $manager->persist($user);
            $manager->flush();

            return $this -> redirectToRoute('app_login');
        }
        return $this->render('security/inscription.html.twig',
                            ['vueInscription'=> $formulaireUser->createView()]);
    }
}
