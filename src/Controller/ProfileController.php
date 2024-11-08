<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        // Vérification que l'utilisateur est bien authentifié
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }

    #[Route('/profile/update', name: 'app_modifier_informations')]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérification de l'authentification de l'utilisateur
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'utilisateur connecté
            $user = $this->getUser();

            if (!$user instanceof User) {
             throw $this->createAccessDeniedException('Utilisateur non trouvé.');
        }
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'avatar
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                // Nom unique pour le fichier
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();

                try {
                    // Déplacer le fichier dans le répertoire des avatars
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );

                    // Mise à jour de l'avatar de l'utilisateur
                    $user->setAvatar($newFilename);
                } catch (FileException $e) {
                    // Gestion d'une éventuelle erreur lors de l'upload du fichier
                    $this->addFlash('error', "Une erreur est survenue lors de l'upload de votre avatar.");
                }
            }

            // Enregistrer les modifications en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/modif.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
