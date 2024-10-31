<?php

namespace App\Controller;

use App\Entity\Era;
use App\Model\Breadcrumb;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_index')]
    public function number(ManagerRegistry $registry, TranslatorInterface $translator): Response
    {
        $repository = $registry->getRepository(Era::class);
        $eras = $repository->findBy([], ['deadlineAt' => 'DESC']);

        return $this->render('admin/index.html.twig', ['breadcrumbs' => $this->getBreadcrumbs($translator), 'eras' => $eras]);
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
