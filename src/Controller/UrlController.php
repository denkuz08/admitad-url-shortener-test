<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/u")
 */
class UrlController extends AbstractController
{
    /**
     * @Route("/{shortCode}", methods={"GET"}, name="redirect_url", requirements={"shortCode": "[a-zA-Z0-9]+"})
     */
    public function index(string $shortCode, UrlRepository $urlRepository): Response
    {
        $url = $urlRepository->findOneBy(['shortCode' => $shortCode]);
        if (!$url) {
            return $this->redirectToRoute('index');
        }

        return $this->redirect($url->getUrl());
    }
}
