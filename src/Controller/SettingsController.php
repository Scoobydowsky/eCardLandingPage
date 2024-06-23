<?php

namespace App\Controller;

use App\DTO\SocialLinkDTO;
use App\DTO\UserDataDTO;
use App\Repository\LinksRepository;
use App\Repository\UserRepository;
use App\Service\DataGetterService;
use App\Service\SocialService;
use App\Service\UserService;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    public function __construct(
        private DataGetterService $getterService,
        private SocialService $socialService,
        private UserService $userService,
        private UserRepository $userRepository,
        private LinksRepository $linksRepository
    )
    {

    }

    #[Route('/admin/settings',name: 'admin_user_settings')]
    public function renderSettingsPage()
    {
        $user = $this->userRepository->find(['id'=>'1']);
        return $this->render('settings/user.html.twig',
        [
            'user'=>$user
        ]);
    }
    #[Route('/admin/socials/',name: 'admin_list_socials')]
    public function renderLinksList()
    {
        $profiles = $this->linksRepository->findAll();
        return $this->render('settings/socials/list.html.twig',
        [
            'profiles'=>$profiles
        ]);
    }
    #[Route('/admin/socials/add', name: 'admin_add_social')]
    public function renderAddLink(Request $request)
    {
        if ($request->isMethod('POST')) {
            $dto = new SocialLinkDTO(
                $request->get('name'),
                $request->get('url'),
                $request->get('icon')
            );

            if (!$dto->name || !$dto->url) {
                $this->addFlash('error', 'Name and Address are required');
                return $this->redirectToRoute('admin_add_social', ['social' => null]);
            } else {
                $this->socialService->addNewSocial($dto->name, $dto->url, $dto->icon);
                return $this->redirectToRoute('admin_list_socials');
            }
        }

        return $this->render('settings/socials/page.html.twig', ['social' => null]);
    }

    #[Route('/admin/socials/edit/{id}', name:'admin_edit_social')]
    public function renderEditLink(int $id,Request $request)
    {
        $social = $this->linksRepository->findOneBy(['id'=>$id]);

        if($request->isMethod('POST')){
            $name = $request->get('name');
            $address = $request->get('url');
            $icon = $request->get('icon');

            if($this->socialService->editSocial($social,$name,$address,$icon))
            {
                return $this->redirectToRoute('admin_list_socials');
            }
            else
            {
                return $this->redirectToRoute('admin_edit_social',['id'=> $social->getId()]);
            }
        }
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

    #[Route('/admin/settings/change-user-data', name: 'app_change_user_data')]
    public function editUserData(Request $request)
    {
        $dto = new UserDataDTO(
            $request->get('name'),
            $request->get('surname'),
            $request->get('nick'),
            $request->get('description')
        );

        if ($dto->name && $dto->surname) {
            try {
                $this->userService->updateUserData($dto->name, $dto->surname, $dto->nick, $dto->description);
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Wymagane Dane ( imię i nazwisko ) nie zostały podane');
            }
        } else {
            $this->addFlash('error', 'Imię i nazwisko jest wymagane');
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

    #[Route('/admin/settings/change-avatar',name: 'app_change_avatar')]
    public function changeProfilePicture(Request $request)
    {
        $profilePicture = $request->files->get('avatar');
        if($profilePicture){
//            TODO TO PRZEBUDOWAĆ DO SERWISU
            $avatarRoute = $this->getParameter('kernel.project_dir') ."/public/img/Profile.png";
            $imageManager = new ImageManager(array('driver' => 'gd'));
            $avatar = $imageManager->make($profilePicture->getPathname());
            $avatar->fit(500,500,function ($constraint)
            {
                $constraint->upsize();
            });
            $avatar->save($avatarRoute, 75, 'png');
            $this->addFlash('success','Successfuly changed avatar');
        }else{
            $this->addFlash('error','You not select picture to upload');
        }
        return $this->redirectToRoute('admin_user_settings');
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

        return $this->redirectToRoute('admin_user_settings');
    }
}