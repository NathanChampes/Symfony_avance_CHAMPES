<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
  private UserPasswordHasherInterface $passwordHasher;

  public function __construct(UserPasswordHasherInterface $passwordHasher)
  {
    $this->passwordHasher = $passwordHasher;
  }

  public function load(ObjectManager $manager): void
  {
    $admin = new User();
    $admin->setEmail('admin@gmail.com');
    $admin->setFirstname('Wallace');
    $admin->setLastname('Breen');
    $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin1');
    $admin->setPassword($hashedPassword);
    $admin->setRoles(['ROLE_ADMIN']);
    $manager->persist($admin);

    $manage = new User();
    $manage->setEmail('manage@gmail.com');
    $manage->setFirstname('Feather');
    $manage->setLastname('McGraw');
    $hashedPassword = $this->passwordHasher->hashPassword($manage, 'manage1');
    $manage->setPassword($hashedPassword);
    $manage->setRoles(['ROLE_USER']);
    $manager->persist($manage);

    $user = new User();
    $user->setEmail('user@gmail.com');
    $user->setFirstname('Gromit');
    $user->setLastname('Wallace');
    $hashedPassword = $this->passwordHasher->hashPassword($user, 'user1');
    $user->setPassword($hashedPassword);
    $user->setRoles(['ROLE_USER']);
    $manager->persist($user);

    $manager->flush();
  }
}
