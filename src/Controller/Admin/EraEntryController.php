<?php

namespace App\Controller\Admin;

use App\Entity\Era;
use App\Entity\EraEntry;
use App\Form\DeleteType;
use App\Form\EraEntryType;
use App\Helper\DoctrineHelper;
use App\Model\Breadcrumb;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/era/{era}/entry')]
class EraEntryController extends AbstractController
{
    #[Route('/new', name: 'admin_era_entry_new')]
    public function new(Request $request, Era $era, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $entry = $this->createDefaultEraEntry($era);
        $form = $this->createForm(EraEntryType::class, $entry)
            ->add('submit', SubmitType::class, ['label' => 'new.submit', 'translation_domain' => 'admin_era_entry']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $entry);

            $message = $translator->trans('new.success', [], 'admin_era_entry');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_era_view', ['era' => $era->getId()]));
        }

        return $this->render('admin/era/entry/new.html.twig', ['form' => $form->createView(), 'breadcrumbs' => $this->getBreadcrumbs($era, $translator)]);
    }

    private function createDefaultEraEntry(Era $era): EraEntry
    {
        $entry = new EraEntry();
        $entry->setEra($era);

        return $entry;
    }

    #[Route('/{entry}/edit', name: 'admin_era_entry_edit')]
    public function edit(Request $request, Era $era, EraEntry $entry, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $form = $this->createForm(EraEntryType::class, $entry)
            ->add('submit', SubmitType::class, ['label' => 'edit.submit', 'translation_domain' => 'admin_era_entry']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $entry);

            $message = $translator->trans('edit.success', [], 'admin_era_entry');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_era_view', ['era' => $era->getId()]));
        }

        return $this->render('admin/era/entry/edit.html.twig', ['form' => $form->createView(), 'breadcrumbs' => $this->getBreadcrumbs($era, $translator)]);
    }

    #[Route('/{entry}/remove', name: 'admin_era_entry_remove')]
    public function remove(Request $request, Era $era, EraEntry $entry, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $form = $this->createForm(DeleteType::class, $entry)
            ->add('submit', SubmitType::class, ['label' => 'remove.submit', 'translation_domain' => 'admin_era_entry', 'attr' => ['class' => 'btn btn-danger']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::removeAndFlush($registry, $entry);

            $message = $translator->trans('remove.success', [], 'admin_era_entry');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_era_view', ['era' => $era->getId()]));
        }

        return $this->render('admin/era/entry/remove.html.twig', ['form' => $form->createView(), 'entry' => $entry, 'breadcrumbs' => $this->getBreadcrumbs($era, $translator)]);
    }


    /**
     * @return Breadcrumb[]
     */
    private function getBreadcrumbs(Era $era, TranslatorInterface $translator): array
    {
        return [
            new Breadcrumb(
                $translator->trans('index.title', [], 'admin'),
                $this->generateUrl('admin_index'),
            ),
            new Breadcrumb($translator->trans('entity.title', [], 'entity_era')),
            new Breadcrumb(
                $era->getName(),
                $this->generateUrl('admin_era_view', ['era' => $era->getId()]),
            ),
            new Breadcrumb($translator->trans('entity.title', [], 'entity_era_entry')),
        ];
    }
}
