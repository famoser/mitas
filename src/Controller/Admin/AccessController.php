<?php

namespace App\Controller\Admin;

use App\Entity\Era;
use App\Entity\EraEntry;
use App\Entity\User;
use App\Form\DeleteType;
use App\Form\EraEntryType;
use App\Helper\DoctrineHelper;
use App\Model\Breadcrumb;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/access')]
class AccessController extends AbstractController
{
    #[Route('/new', name: 'admin_access_new')]
    public function new(Request $request, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, ['label' => 'new.email', 'translation_domain' => 'admin_access'])
            ->add('submit', SubmitType::class, ['label' => 'new.submit', 'translation_domain' => 'admin_access'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $registry->getRepository(User::class);
            $email = $form->getData()['email'];

            $user = $repository->findOneBy(['email' => $email]);
            if (!$user) {
                $user = new User();
                $user->setEmail($email);
            }

            $user->setIsAdmin(true);
            DoctrineHelper::persistAndFlush($registry, $user);

            $message = $translator->trans('new.success', [], 'admin_access');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_index'));
        }

        return $this->render('admin/access/new.html.twig', ['form' => $form->createView(), 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
    }

    #[Route('/{admin}/remove', name: 'admin_access_remove')]
    public function remove(Request $request, User $admin, TranslatorInterface $translator, ManagerRegistry $registry): Response
    {
        $form = $this->createForm(DeleteType::class, $admin)
            ->add('submit', SubmitType::class, ['label' => 'remove.submit', 'translation_domain' => 'admin_access', 'attr' => ['class' => 'btn btn-danger']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setIsAdmin(false);
            DoctrineHelper::removeAndFlush($registry, $admin);

            $message = $translator->trans('remove.success', [], 'admin_access');
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('admin_index'));
        }

        return $this->render('admin/access/remove.html.twig', ['form' => $form->createView(), 'admin' => $admin, 'breadcrumbs' => $this->getBreadcrumbs($translator)]);
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
            new Breadcrumb($translator->trans('title', [], 'admin_access')),
        ];
    }
}
