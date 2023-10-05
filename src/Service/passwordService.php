<?php

namespace App\Service;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class passwordService extends AbstractController
{
    public function createPassword(Users $user, UserPasswordHasherInterface $userPasswordHasher, Form $form)
    {
        
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
    }
}