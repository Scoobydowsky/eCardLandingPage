<?php

namespace App\Controller;

use App\Controller\SettingsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController implements SettingsInterface
{


    public function renderSettingsPage()
    {
        // TODO: Implement renderSettingsPage() method.
    }
    #[Route('/admin/socials/',name: 'admin_list_socials')]
    public function renderLinksList()
    {
        // TODO: Implement renderLinksList() method.

        return $this->render('settings/socials/list.html.twig');
    }

    public function renderAddLink()
    {
        // TODO: Implement renderAddLink() method.
    }

    public function renderEditLink()
    {
        // TODO: Implement renderEditLink() method.
    }

    public function deleteLink()
    {
        // TODO: Implement deleteLink() method.
    }

    public function renderEditUser()
    {
        // TODO: Implement renderEditUser() method.
    }
}