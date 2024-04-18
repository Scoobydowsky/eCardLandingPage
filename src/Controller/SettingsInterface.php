<?php

namespace App\Controller;

interface SettingsInterface
{
    public function renderSettingsPage();

    public function renderLinksList();

    public function renderAddLink();

    public function renderEditLink();

    public function deleteLink();

    public function renderEditUser();
}