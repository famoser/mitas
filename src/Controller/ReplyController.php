<?php

namespace App\Controller;

use App\Entity\EraEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReplyController extends AbstractController
{
    #[Route('/reply/{entry}', name: 'reply')]
    public function reply(EraEntry $entry): Response
    {
        return $this->render('index.html.twig');
    }
}
