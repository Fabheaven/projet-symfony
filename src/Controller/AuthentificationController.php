<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Form\LoginType;  
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($userRepository->findOneBy(['email' => $user->getEmail()])) {
                $this->addFlash('error', 'Un compte existe déjà avec cet e-mail.');
                return $this->redirectToRoute('app_inscription');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

        
            $entityManager->persist($user);
            $entityManager->flush();

          
            $this->addFlash('success', 'Votre compte a été créé avec succès !');
            return $this->redirectToRoute('app_login');  
        }

        return $this->render('pages/inscription/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]); 
    }


    #[Route('/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
{   
    // Tester si l'utilisateur est connecté
    if ($this->isGranted('IS_AUTHENTIFICATED_FULLY')){
        return $this->redirectToRoute('inscription');
    }

    $user = new User();
    $form = $this->createForm(LoginType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if (empty($user->getEmail())) {
            $this->addFlash('error', 'L\'email est requis.');
            return $this->redirectToRoute('app_login');
        }
    }

    $error = $authenticationUtils->getLastAuthenticationError();
    $lastEmail = $authenticationUtils->getLastUsername();

    $form = $this->createForm(LoginType::class);

    return $this->render('pages/login/index.html.twig', [
        'last_email' => $lastEmail,
        'error' => $error,
        'loginForm' => $form->createView(),
    ]);
}

    #[Route('/deconnexion', name: 'app_deconnexion')]
public function deconnexion(){}

}

