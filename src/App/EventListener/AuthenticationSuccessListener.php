<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener extends AbstractController
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
            'roles' => $user->getRoles(),
            'email' => $user->getEmail(),
            'id' => $user->getId(),
            // Take foreign key artist or organizer. But if I use only ``` 'artistId => $user->getArtist() ```, I take an object and not a FK
            'artistId' => $user->getArtist() ? $user->getArtist()->getId() : null,
            'organizerId' => $user->getOrganizer() ? $user->getOrganizer()->getId() : null,
                  
        );

        $event->setData($data);
    }
}

     
