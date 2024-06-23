<?php

namespace App\DTO;

class SocialLinkDTO
{
    public string $name;
    public string $url;
    public string $icon;

    public function __construct(string $name, string $url, string $icon)
    {
        $this->name = $name;
        $this->url = $url;
        $this->icon = $icon;
    }
}