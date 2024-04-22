<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

interface SettingsInterface
{
    public function renderSettingsPage();

    public function renderLinksList();

    public function renderAddLink();

    public function renderEditLink(int $id);

    public function deleteLink(int $id);

    public function editUserData(Request $request);

    public function editLogin(Request $request);

    public function changeProfilePicture();

    public function changePassword(Request $request);
}