<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    // La j'ai fait un test avec un mock
    public function testUserCreation(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));

        $entityManager->expects($this->once())
            ->method('flush');

        $user = new User();
        $user->setEmail('test@example.com')
            ->setFirstname('Test')
            ->setLastname('User')
            ->setPassword('hashed_password')
            ->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('Test', $user->getFirstname());
        $this->assertEquals('User', $user->getLastname());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }
}
