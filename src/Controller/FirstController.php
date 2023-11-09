<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/template', name: 'template')]
    public function template(): Response
    {
        return $this->render('template.html.twig');
    }

    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => 'Quijo',
            'firstname' => 'Adrien',
        ]);
    }

    #[Route('/hello/{nom}/{firstname}', name: 'say_hello')]
    public function sayHello(Request $request, string $nom, string $firstname): Response
    {
        //dd($request);
        return $this->render('first/hello.html.twig', [
            'nom' => $nom,
            'firstname' => $firstname
            // 'path' => '    '
        ]);
    }

    /* #[Route('/hello', name: 'say_hello')]
    public function Hello(): Response
    {
        $rand = rand(0, 10);
        echo $rand;
        if ($rand % 2 == 0) {
            return $this->redirectToRoute('app_first', [
                'number' => $rand,
            ]);
        }

        return $this->forward('App\Controller\FirstController::index', [
            'number' => $rand,
        ]);
    }  */

    /* #[Route('{maVar}', name:'test.order.route')]
    public function testOrderRoute($maVar): Response
    {
        return new Response("
            <html><body>$maVar</body></html>
            ");
    } */

    #[Route(
        'multi/{entier1<\d+>}/{entier2<\d+>}',
        name:'multiplication',
        //requirements: ['entier1' => '\d+', 'entier2' => '\d+']
    )]
    public function multiplication(int $entier1, int $entier2): Response
    {
        $resultat = $entier1 * $entier2;
        return new Response("<h1>$resultat </h1>");
    }
}
