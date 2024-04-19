<?php

namespace App\Controller;

use App\Controller\SettingsInterface;
use App\Service\DataGetterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController implements SettingsInterface
{
    public function __construct(
        private DataGetterService $getterService
    )
    {

    }

    #[Route('/admin/settings',name: 'admin_user_settings')]
    public function renderSettingsPage()
    {
        // TODO: Implement renderSettingsPage() method.

        return $this->render('settings/user.html.twig');
    }
    #[Route('/admin/socials/',name: 'admin_list_socials')]
    public function renderLinksList()
    {
        $profiles = $this->getterService->getSocials();
        return $this->render('settings/socials/list.html.twig',
        [
            'profiles'=>$profiles
        ]);
    }

    public function renderAddLink()
    {
        // TODO: Implement renderAddLink() method.
    }

    #[Route('//admin/socials/edit/{id}', name:'admin_edit_social')]
    public function renderEditLink(int $id)
    {
        $social = $this->getterService->getSocialObjectById($id);
        // TODO: Implement renderEditLink() method.

        return $this->render('settings/socials/page.html.twig',
        [
            'social'=>$social
        ]);
    }

    public function deleteLink(int $id)
    {
        // TODO: Implement deleteLink() method.
    }

    public function renderEditUser()
    {
        // TODO: Implement renderEditUser() method.
    }
}