<?php

namespace App\Controller;

use App\Entity\Era;
use App\Form\DateTimeHelper;
use App\Form\EraType;
use App\Helper\DoctrineHelper;
use App\Model\Breadcrumb;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/era')]
class AdminEraController extends AbstractController
{
    #[Route('/new', name: 'admin_era_new')]
    public function new(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $era = $this->createDefaultEra($translator);
        $form = $this->createForm(EraType::class, $era)
            ->add('submit', SubmitType::class, ['label' => 'new.submit', 'translation_domain' => 'admin_era']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $era);

            $message = $translator->trans('new.success', [], 'admin_era');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_era_view', ['era' => $era->getId()]));
        }

        return $this->render('admin/era/new.html.twig', ['form' => $form->createView(), 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
    }

    private function createDefaultEra(TranslatorInterface $translator): Era
    {
        $now = new \DateTime();
        $expectedDeadline = (clone $now)->setDate($now->format('Y'), $now->format('m'), 15);
        if ($expectedDeadline < $now) {
            $expectedDeadline->add(new \DateInterval('P1M'));
        }

        $era = new Era();
        $era->setDeadlineAt(\DateTimeImmutable::createFromMutable($expectedDeadline));
        $era->setName(DateTimeHelper::getMonthName($expectedDeadline, $translator->getLocale()));

        return $era;
    }


    #[Route('/view/{era}', name: 'admin_era_view')]
    public function view(Era $era, TranslatorInterface $translator): Response
    {
        return $this->render('admin/era/view.html.twig', ["era" => $era, 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
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
            new Breadcrumb($translator->trans('entity.title', [], 'entity_era')),
        ];
    }
}
