<?php

namespace App\Controller\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateArticleController extends AbstractController
{
    /**
     * @Route("/articles", methods={"POST"})
     */
    public function index(Request $request): Response
    {


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Article/CreateArticleController.php',
        ]);
    }
}
