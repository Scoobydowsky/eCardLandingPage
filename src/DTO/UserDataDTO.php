<?php

namespace App\DTO;

class UserDataDTO
{
    public ?string $name;
    public ?string $surname;
    public ?string $nick;
    public ?string $description;

    public function __construct(?string $name, ?string $surname, ?string $nick, ?string $description)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->nick = $nick;
        $this->description = $description;
    }
}