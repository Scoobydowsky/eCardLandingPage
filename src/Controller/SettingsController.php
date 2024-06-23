<?php

namespace App\Controller;

use App\DTO\SocialLinkDTO;
use App\DTO\UserDataDTO;
use App\Repository\LinksRepository;
use App\Repository\UserRepository;
use App\Service\AvatarService;
use App\Service\DataGetterService;
use App\Service\SocialService;
use App\Service\UserService;
use Doctrine\ORM\EntityNotFoundException;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    public function __construct(
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
        $userId = $this->getUser()->getId();
        $name = $request->get('name');
        $surname = $request->get('surname');
        $nick = $request->get('nick');
        $description = $request->get('description');

        if ($name && $surname) {
            try {
                $this->userService->updateUserData($userId, $name, $surname, $nick, $description);
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Wymagane Dane ( imię i nazwisko ) nie zostały podane');
            }
        } else {
            $this->addFlash('error', 'Imię i nazwisko jest wymagane');
        }

        return $this->redirectToRoute('admin_user_settings');
    }

    #[Route('/admin/settings/change-login',name:'app_change_login')]
    public function editLogin(Request $request): Response
    {
        $login = $request->get('login');

        if ($login != null) {
            try {
                $userId = $this->getUser()->getId(); // Pobranie ID zalogowanego użytkownika
                $this->userService->updateLogin($userId, $login);
                $this->addFlash('success', 'Zmieniono login pomyślnie');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Nie udało się zmienić loginu');
            }
        } else {
            $this->addFlash('warning', 'Nie podano loginu do zmiany');
        }

        return $this->redirectToRoute('admin_user_settings');
    }

    #[Route('/admin/settings/change-avatar', name: 'app_change_avatar')]
    public function changeProfilePicture(Request $request, AvatarService $avatarService)
    {
        $profilePicture = $request->files->get('avatar');
        if ($avatarService->changeAvatar($profilePicture)) {
            $this->addFlash('success', 'Successfully changed avatar');
        } else {
            $this->addFlash('error', 'You did not select a picture to upload');
        }

        return $this->redirectToRoute('admin_user_settings');
    }

    #[Route('/admin/settings/change-password', name: 'app_change_password', methods: ['POST'])]
    public function changePassword(Request $request)
    {
        $userId = $this->getUser()->getId();
        $oldPassword = $request->get('oldPassword');
        $newPassword = $request->get('newPassword');

        if (empty($oldPassword) || empty($newPassword)) {
            $this->addFlash('error', 'Obecne hasło i nowe hasło są wymagane');
            return $this->redirectToRoute('admin_user_settings');
        }

        try {
            $this->userService->changePassword($userId, $oldPassword, $newPassword);
            $this->addFlash('success', 'Hasło zostało zmienione pomyślnie');
        } catch (EntityNotFoundException $exception) {
            $this->addFlash('error', 'Użytkownik nie został znaleziony');
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Wystąpił błąd podczas zmiany hasła: ' . $exception->getMessage());
        }

        return $this->redirectToRoute('admin_user_settings');
    }
}