<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('users')]
class UsersController extends AbstractController
{
    #[Route('/', name:'users.list')]
    public function getAllUsers(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Users::class);
        $users = $repository->findAll();
        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/all/age/{ageMin<\d+>}/{ageMax<\d+>}', name:'users.list.byAge')]
    public function getAllUsersByAgeInterval(ManagerRegistry $doctrine, int $ageMin, int $ageMax): Response
    {
        $repository = $doctrine->getRepository(Users::class);
        $users = $repository->findUsersByAgeInterval($ageMin, $ageMax);
        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/stats/age/{ageMin<\d+>}/{ageMax<\d+>}', name:'users.list.stats.byAge')]
    public function statsUsersByAge(ManagerRegistry $doctrine, int $ageMin, int $ageMax): Response
    {
        $repository = $doctrine->getRepository(Users::class);
        $stats = $repository->statsUsersByAgeInterval($ageMin, $ageMax);
        //dd($stats);
        return $this->render('users/stats.html.twig', [
            'stats' => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ]);
    }


    #[Route('/all/{page<\d+>?1}/{nb<\d+>?12}', name:'users.list.all')]
    public function getAll(ManagerRegistry $doctrine, int $page, int $nb): Response
    {
        $repository = $doctrine->getRepository(Users::class);
        $nbUsers = $repository->count([]);
        // 24
        $nbPages = ceil($nbUsers / $nb);
        //dd($nbPages);
        $users = $repository->findBy([], [], limit:$nb, offset: ($page - 1) * $nb);
        return $this->render('users/index.html.twig', [
            'users' => $users,
            'isPaginated' => true,
            'nbPages' => $nbPages,
            'page' => $page,
            'nb' => $nb
        ]);
    }

    /* #[Route('/{id<\d+>}', name:'users.detail')]
    public function userDetails(ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Users::class);
        $user = $repository->find($id);
        if (!$user) {
            $this->addFlash('error', "L'utilisateur $id n'existe pas");
            return $this->redirectToRoute("users.list");
        }
        return $this->render('users/detail.html.twig', ['user' => $user]);
    } */

    #[Route('/{id<\d+>}', name:'users.detail')]
    public function userDetails(Users $user = null): Response
    {
        $id = $user ? $user->getId() : null;
        if (!$user) {
            $this->addFlash('error', "L'utilisateur $id n'existe pas");
            return $this->redirectToRoute("users.list");
        }
        return $this->render('users/detail.html.twig', ['user' => $user]);
    }

    #[Route('/add', name: 'users.add')]
    public function addUsers(ManagerRegistry $doctrine, Request $request): Response
    {
        
        $user = new Users();
        // $user est l'image de notre formulaire
        $form = $this->createForm(UserType::class, $user);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        // Mon formulaire va aller traiter la requête 
        $form->handleRequest($request);
        // Est-ce que le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // Si oui, on va ajouter l'objet user dans la base de données
            $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();
            // On affiche un message de succès
            $this->addFlash('success', $user->getName() . " a été ajouté avec succès");
            // Rediriger vers la liste des utilisateurs
            return $this->redirectToRoute("users.list");
            // Sinon, on affiche notre formulaire
        } else {
            return $this->render('users/add-user.html.twig', [
            'form' => $form->createView()
        ]);
        }        
    }

    #[Route('/delete/{id<\d+>}', name:'users.delete')]
    public function deleteUsers(ManagerRegistry $doctrine, Users $users = null): RedirectResponse
    {
        // Sinon => Envoyer un flashmessage d'error
        if (!$users) {
            $this->addFlash('error', 'Impossible de supprimer l\'utilisateur');
            return $this->redirectToRoute("users.list.all");
        }
        // S'il existe => le delete et retourner un flash message success
        // Récupérer l'user
        $entityManager = $doctrine->getManager();
        // Ajoute la fonction de suppression de la transaction
        $entityManager->remove($users);
        // Exécuter la transaction
        $entityManager->flush();
        $this->addFlash('success', 'L\'utilisateur a correctement été supprimé');
        return $this->redirectToRoute('users.list.all');
    }

    #[Route('/update/{id<\d+>}/{name}/{firstname}/{age}', name:'users.update')]
    public function updateUsers(Users $users = null, ManagerRegistry $doctrine, $name, $firstname, $age): Response
    {
        // Si la personne n'existe pas
        if (!$users) {
            // Message d'erreur
            $this->addFlash('error', 'Impossible de mettre à jour cet utilisateur');
            return $this->redirectToRoute("users.list.all");
        }
        // Récupérer les informations
        $entityManager = $doctrine->getManager();
        $users->setFirstname($firstname);
        $users->setAge($age);
        $users->setName($name);

        $entityManager->persist($users);

        // Insérer les informations
        $entityManager->flush();
        // Message de succès
        $this->addFlash('success', 'Cet utilisateur a été mis à jour avec succès');
        return $this->redirectToRoute("users.list.all");

    }
}
