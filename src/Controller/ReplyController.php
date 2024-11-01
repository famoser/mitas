<?php

namespace App\Controller;

use App\Entity\EraEntry;
use App\Form\ReplyType;
use App\Helper\DoctrineHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReplyController extends AbstractController
{
    #[Route('/reply/{entry}', name: 'reply')]
    public function reply(Request $request, EraEntry $entry, ManagerRegistry $registry): Response
    {
        $form = $this->createForm(ReplyType::class, $entry, ['disabled' => $entry->getEra()->isDeadlinePassed()]);

        if (!$entry->getEra()->isDeadlinePassed()) {
            $form->add('submit', SubmitType::class, ['label' => 'index.submit', 'translation_domain' => 'reply']);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !$entry->getEra()->isDeadlinePassed()) {
            $entry->setLastConfirmedAt();
            DoctrineHelper::persistAndFlush($registry, $entry);
        }

        return $this->render('reply/index.html.twig', ['entry' => $entry, 'form' => $form->createView()]);
    }

    #[Route('/reply/{entry}/none', name: 'reply_none')]
    public function replyNone(EraEntry $entry, ManagerRegistry $registry): Response
    {
        $entry->setLastConfirmedAt();
        DoctrineHelper::persistAndFlush($registry, $entry);

        return $this->redirectToRoute('reply', ['entry' => $entry->getId()]);
    }
}
