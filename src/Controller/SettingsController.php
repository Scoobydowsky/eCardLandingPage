<?php

namespace App\Controller;

use App\Service\DataGetterService;
use App\Service\SocialService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\returnArgument;

class SettingsController extends AbstractController implements SettingsInterface
{
    public function __construct(
        private DataGetterService $getterService,
        private SocialService $socialService,
        private UserService $userService
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

    #[Route('/admin/settings/change-user-data',name:'app_change_user_data')]
    public function editUserData(Request $request)
    {
        $name = $request->get('name');
        if($name == null)
        {
            $this->addFlash('error', 'Nie podano imienia');
        }
        $surname = $request->get('surname');
        if($surname == null)
        {
            $this->addFlash('error', 'Nie podano nazwiska');
        }
        $nick = $request->get('nick');
        if($nick == null)
        {
            $this->addFlash('warning', 'Nie podano pseudonimu');
        }
        $description = $request->get('description');
        if($description == null)
        {
            $this->addFlash('warning', 'Nie podano opisu');
        }
        if(($name != null)&&($surname != null))
        {
            try{
                $this->userService->updateUserData($name,$surname,$nick,$description);
            }
            catch (\Exception $exception)
            {
                $this->addFlash('error', 'Nie podano wymaganych danych (imię i nazwisko)');
            }
        }
        return $this->redirectToRoute('admin_user_settings');
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
    #[Route('/admin/seetings/change-password',name: 'app_change_password')]
    public function changePassword(Request $request)
    {
        $oldPassword = $request->get('oldPassword');
        $newPassword = $request->get('newPassword');
        $reTypePassword = $request->get('reTypedPassword');
        if($newPassword != $reTypePassword){
            $this->addFlash('error','New password and retyped password are not equal');
        }else
        {
            try {
                $this->userService->changePassword($oldPassword, $newPassword);
                $this->addFlash('success','Successfully changed password');
            }catch (\Exception $exception)
            {
                $this->addFlash('error','Something went wrong');
            }
        }
        // TODO: Implement changePassword() method.

        return $this->redirectToRoute('admin_user_settings');
    }
}