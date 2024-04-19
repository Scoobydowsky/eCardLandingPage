<?php

namespace App\Controller;

interface SettingsInterface
{
    public function renderSettingsPage();

    public function renderLinksList();

    public function renderAddLink();

    public function renderEditLink(int $id);

    public function deleteLink(int $id);

    public function renderEditUser();
}