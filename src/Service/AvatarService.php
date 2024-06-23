<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AvatarService
{
    private $imageManager;
    private $targetDir;

    public function __construct(
        protected ParameterBagInterface $params
    )
    {
        $this->imageManager = new ImageManager(['driver' => 'gd']);
        $this->targetDir = $params->get('kernel.project_dir') . '/public/img';

    }

    public function changeAvatar($profilePicture): bool
    {
        if (!$profilePicture) {
            return false;
        }

        $avatarRoute = $this->targetDir . "/img/Profile.png";
        $avatar = $this->imageManager->make($profilePicture->getPathname());
        $avatar->fit(500, 500, function ($constraint) {
            $constraint->upsize();
        });
        $avatar->save($avatarRoute, 75, 'png');

        return true;
    }
}