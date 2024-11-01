<?php

namespace App\Controller\Admin;

use App\Entity\Era;
use App\Entity\EraEntry;
use App\Form\DateTimeHelper;
use App\Form\DeleteType;
use App\Form\EraType;
use App\Helper\DoctrineHelper;
use App\Model\Breadcrumb;
use App\Services\Interfaces\EmailServiceInterface;
use App\Services\Interfaces\ExportServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/era')]
class EraController extends AbstractController
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

            /** @var Era|null $copyEra */
            $copyEra = $form->getData()[EraType::COPY_ERA_FIELD];
            if ($copyEra) {
                $this->copyFromEra($copyEra, $era, $registry);
            }

            $message = $translator->trans('new.success', [], 'admin_era');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_era_view', ['era' => $era->getId()]));
        }

        return $this->render('admin/era/new.html.twig', ['form' => $form->createView(), 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
    }

    private function createDefaultEra(TranslatorInterface $translator): Era
    {
        $now = new \DateTime();
        $expectedDeadline = (clone $now)->setDate((int)$now->format('Y'), (int)$now->format('m'), 15);
        if ($expectedDeadline < $now) {
            $expectedDeadline->add(new \DateInterval('P1M'));
        }

        $era = new Era();
        $era->setDeadlineAt(\DateTimeImmutable::createFromMutable($expectedDeadline));
        $era->setName(DateTimeHelper::getMonthName($expectedDeadline, $translator->getLocale()));

        return $era;
    }

    #[Route('/{era}/view', name: 'admin_era_view')]
    public function view(Request $request, Era $era, TranslatorInterface $translator, ManagerRegistry $registry, EmailServiceInterface $emailService): Response
    {
        $parameters = [
            "era" => $era,
            'breadcrumbs' => $this->getBreadcrumbs($translator),
            'announce_form' => $this->announceForm($request, $era, $translator, $registry, $emailService)
        ];

        return $this->render('admin/era/view.html.twig', $parameters);
    }

    #[Route('/{era}/resend/{entry}', name: 'admin_era_resend')]
    public function resend(Era $era, EraEntry $entry, TranslatorInterface $translator, ManagerRegistry $registry, EmailServiceInterface $emailService): Response
    {
        // here no form token is used for a submission, but this is OK: entry ids are hard to guess

        if ($emailService->announceEra($entry)) {
            $entry->setLastReminderSent();
            DoctrineHelper::persistAndFlush($registry, $entry);

            $message = $translator->trans('resend.success', [], 'admin_era');
            $this->addFlash('success', $message);
        } else {
            $message = $translator->trans('resend.danger', [], 'admin_era');
            $this->addFlash('danger', $message);
        }

        return $this->redirectToRoute('admin_era_view', ['era' => $era->getId()]);
    }

    #[Route('/{era}/export', name: 'admin_era_export')]
    public function export(Era $era, TranslatorInterface $translator, ExportServiceInterface $exportService): Response
    {
        $filenameUnsafe = (new \DateTime())->format('Y.m.d-Hi-').$era->getName();
        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $filenameUnsafe).'.xlsx';

        $header = [
            $translator->trans('Full name', [], 'entity_era_entry'),
            $translator->trans('Email', [], 'entity_era_entry'),
            $translator->trans('Work mode', [], 'entity_era_entry'),
            $translator->trans('Team', [], 'entity_era_entry'),
            $translator->trans('General agreement', [], 'entity_era_entry'),
            $translator->trans('Time off request', [], 'entity_era_entry'),
            $translator->trans('Absences', [], 'entity_era_entry')
        ];

        $content = [];
        foreach ($era->getEntries() as $entry) {
            $row = [
                $entry->getFullName(),
                $entry->getEmail(),
                $entry->getWorkMode(),
                $entry->getTeam(),
                $entry->getGeneralAgreement(),
                $entry->getTimeOffRequests(),
                $entry->getAbsences(),
            ];

            $content[] = $row;
        }

        return $exportService->exportAsExcel($filename, $header, $content);
    }

    #[Route('/{era}/edit', name: 'admin_era_edit')]
    public function edit(Request $request, Era $era, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $form = $this->createForm(EraType::class, $era)
            ->add('submit', SubmitType::class, ['label' => 'edit.submit', 'translation_domain' => 'admin_era']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::persistAndFlush($registry, $era);

            $message = $translator->trans('edit.success', [], 'admin_era');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_index'));
        }

        return $this->render('admin/era/edit.html.twig', ['form' => $form->createView(), 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
    }

    #[Route('/{era}/remove', name: 'admin_era_remove')]
    public function remove(Request $request, Era $era, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $form = $this->createForm(DeleteType::class, $era)
            ->add('submit', SubmitType::class, ['label' => 'remove.submit', 'translation_domain' => 'admin_era', 'attr' => ['class' => 'btn btn-danger']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            DoctrineHelper::removeAndFlush($registry, $era);

            $message = $translator->trans('remove.success', [], 'admin_era');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_index'));
        }

        return $this->render('admin/era/remove.html.twig', ['form' => $form->createView(), 'era' => $era, 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
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

    private function announceForm(Request $request, Era $era, TranslatorInterface $translator, ManagerRegistry $registry, EmailServiceInterface $emailService): ?FormView
    {
        if ($era->getAnnouncedAt() || $era->isDeadlinePassed()) {
            return null;
        }

        $form = $this->createFormBuilder()
            ->add('send', SubmitType::class, ['label' => 'announce.submit', 'translation_domain' => 'admin_era'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $allSuccessful = true;
            foreach ($era->getEntries() as $entry) {
                if ($emailService->announceEra($entry)) {
                    $entry->setLastReminderSent();
                    DoctrineHelper::persistAndFlush($registry, $entry);
                } else {
                    $allSuccessful = false;
                }
            }

            $era->setAnnouncedAt();
            DoctrineHelper::persistAndFlush($registry, $era);

            if ($allSuccessful) {
                $message = $translator->trans('announce.success', [], 'admin_era');
                $this->addFlash('success', $message);
            } else {
                $message = $translator->trans('announce.danger', [], 'admin_era');
                $this->addFlash('danger', $message);
            }

            return null;
        }

        return $form->createView();
    }

    public function copyFromEra(Era $sourceEra, Era $targetEra, ManagerRegistry $registry): void
    {
        /** @var EraEntry[] $newEntries */
        $newEntries = [];
        foreach ($sourceEra->getEntries() as $copyEntry) {
            $newEntry = EraEntry::copyPersistentFields($copyEntry);
            $newEntry->setEra($targetEra);

            $newEntries[] = $newEntry;
        }

        DoctrineHelper::persistAndFlush($registry, ...$newEntries);
    }
}
