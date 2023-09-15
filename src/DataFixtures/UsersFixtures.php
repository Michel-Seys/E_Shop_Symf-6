<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slug
    ){}
    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('admin@mail.mail')
                ->setLastname('Seys')
                ->setFirstName('Michel')
                ->setAddress('31, rue rue')
                ->setZipcode('12345')
                ->setCity('Ville')
                ->setPassword(
                    $this->passwordEncoder->hashPassword($admin, 'admin')
                );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for($usr = 1; $usr <=10; $usr++){

            $user = new Users();
            $user   ->setEmail($faker->email)
                    ->setLastname($faker->lastName)
                    ->setFirstName($faker->firstName())
                    ->setAddress($faker->streetAddress())
                    ->setZipcode($faker->postcode())
                    ->setCity($faker->city())
                    ->setPassword($this->passwordEncoder->hashPassword($admin, 'password'));
    $manager->persist($user);
        }
        $manager->flush();
    }
}
