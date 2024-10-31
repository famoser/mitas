<?php

namespace App\Controller;

use App\Model\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_index')]
    public function number(TranslatorInterface $translator): Response
    {
        return $this->render('admin/index.html.twig', ['breadcrumbs' => $this->getBreadcrumbs($translator)]);
    }

    /**
     * @return Breadcrumb[]
     */
    private function getBreadcrumbs(TranslatorInterface $translator): array
    {
        return [
            new Breadcrumb(
                $translator->trans('index.title', [], 'admin'),
                $this->generateUrl('admin_index'),
            ),
        ];
    }
}
