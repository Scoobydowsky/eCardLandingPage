<?php

namespace App\Controller;

use App\Service\DataGetterService;
use App\Service\SocialService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController implements SettingsInterface
{
    public function __construct(
        private DataGetterService $getterService,
        private SocialService $socialService
    )
    {

    }

    #[Route('/admin/settings',name: 'admin_user_settings')]
    public function renderSettingsPage()
    {
        // TODO: Implement renderSettingsPage() method.
        $user = $this->getterService->getUserData();
        return $this->render('settings/user.html.twig',
        [
            'user'=>$user
        ]);
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
    #[Route('/admin/socials/add',name: 'admin_add_social')]
    public function renderAddLink()
    {
        // TODO: Implement renderAddLink() method.
    }

    #[Route('/admin/socials/edit/{id}', name:'admin_edit_social')]
    public function renderEditLink(int $id)
    {
        $social = $this->getterService->getSocialObjectById($id);
        // TODO: Implement renderEditLink() method.

        return $this->render('settings/socials/page.html.twig',
        [
            'social'=>$social
        ]);
    }
    #[Route('/admin/socials/delete/{id}',name: 'admin_delete_social')]
    public function deleteLink(int $id)
    {
        $this->socialService->deleteSocial($id);
        return $this->redirectToRoute('admin_list_socials');
    }

    public function editUserData()
    {
        // TODO: Implement editUserData() method.
    }

    #[Route('/admin/settings/change-login',name:'app_change_login')]
    public function editLogin(Request $request)
    {
        $login = $request->get('login');

        if($login != null) {
            try {
                $this->userService->updateLogin($login);
                $this->addFlash('success', 'Zmieniono login pomyślnie');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Nie udało się zmienić loginu');
            }
        }
        else {
            $this->addFlash('warning','Nie podano loginu do zmiany');
        }

        return $this->redirectToRoute('admin_user_settings');
    }

    public function changeProfilePicture()
    {
        // TODO: Implement changeProfilePicture() method.
    }

    public function changePassword()
    {
        // TODO: Implement changePassword() method.
    }
}