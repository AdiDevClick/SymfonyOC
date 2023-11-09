<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        // Afichier notre tableau todo
        // sinon je l'initialise puis j'affiche
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste des todos vient d'être initialisée");
        }
        // Si j'ai mon tableau todo dans ma session, je ne fais que l'afficher
        return $this->render('todo/index.html.twig');
    }

    #[Route('/reset', name:'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        // vérifier si j'ai mon array dans la session
        if ($session->has('todos')) {
            // Si oui
            // On reset la liste des todos
            $session->clear();
            $this->addFlash('success', "La todo a été réinitialisée avec succès.");
        } else {
            // Si non, on affiche un message d'erreur
            $this->addFlash('error', "La todo ne peut être réinitialisée");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/delete/{name}/', name:'todo.delete')]
    public function deleteTodo(Request $request, string $name): RedirectResponse
    {
        $session = $request->getSession();
        // vérifier si j'ai mon array dans la session
        if ($session->has('todos')) {
            // Si oui
            // Vérifier si on a déjà un todo avec le même nom
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                // Si oui, on le delete
                unset($todos[$name]);
                $session->set("todos", $todos);
                $this->addFlash('success', "Le todo : $name a été supprimé de la liste avec succès.");

            } else {
                // Si non, on affiche un message d'erreur
                $this->addFlash('error', "Le todo : $name ne peut être supprimé, il n'existe pas.");
            }
            // Si non
        } else {
            // Afficher une erreur puis rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée.");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/update/{name}/{content}', name:'todo.update')]
    public function updateTodo(Request $request, string $name, string $content): RedirectResponse
    {
        $session = $request->getSession();
        // vérifier si j'ai mon array dans la session
        try {
            if ($session->has('todos')) {
                // Si oui
                // Vérifier si on a déjà un todo avec le même nom
                $todos = $session->get('todos');
                if (isset($todos[$name])) {
                    // Si oui, on modifie la valeur et on affiche un message de succes
                    $todos[$name] = $content;
                    $session->set("todos", $todos);
                    $this->addFlash('success', "Le todo : $name a été mis à jour avec succès.");
                    //$session->replace($todos);
                } else {
                    // Si non, on affiche un message d'erreur
                    $this->addFlash('error', "Le todo : $name n'existe pas dans la liste.");
                }
                // Si non
            } else {
                // Afficher une erreur puis rediriger vers le controlleur index
                $this->addFlash('error', "La liste des todos n'est pas encore initialisée.");
            }
        } catch (\Error $e) {
            $this->addFlash("error", $e->getMessage());
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route(
        '/add/{name?none}/{content?none}',
        name:'todo.add',
        //defaults: ['content' => 'Valeur pas défaut']
    )]
    public function addTodo(Request $request, string $name, string $content): RedirectResponse
    {
        $session = $request->getSession();
        // vérifier si j'ai mon array dans la session
        try {
            if ($session->has('todos')) {
                // Si oui
                // Vérifier si on a déjà un todo avec le même nom
                $todos = $session->get('todos');
                if (isset($todos[$name])) {
                    // Si oui, on affiche un erreur
                    $this->addFlash('error', "Le todo : $name existe déjà dans la liste.");
                } else {
                    // Si non, on l'ajoute et on affiche un message de succès
                    $todos[$name] = $content;
                    $session->set("todos", $todos);
                    $this->addFlash('success', "Le todo : $name a été ajouté avec succès.");
                }
                // Si non
            } else {
                // Afficher une erreur puis rediriger vers le controlleur index
                $this->addFlash('error', "La liste des todos n'est pas encore initialisée.");
            }
        } catch (\Error $e) {
            $this->addFlash("error", $e->getMessage());
        }
        return $this->redirectToRoute('app_todo');
    }
}
