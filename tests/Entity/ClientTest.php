<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientTest extends KernelTestCase
{
  // La j'ai pas utiliser mock car je n'ai pas besoin de simuler une entité
  private ValidatorInterface $validator;

  protected function setUp(): void
  {
    self::bootKernel();
    $this->validator = static::getContainer()->get(ValidatorInterface::class);
  }

  public function testValidClient(): void
  {
    $client = new Client();
    $client->setFirstname('John');
    $client->setLastname('Doe');
    $client->setEmail('john.doe@example.com');
    $client->setPhoneNumber('0123456789');
    $client->setAddress('123 Main St');

    $errors = $this->validator->validate($client);

    $this->assertCount(0, $errors);
  }

  public function testInvalidEmail(): void
  {
    $client = new Client();
    $client->setFirstname('John');
    $client->setLastname('Doe');
    $client->setEmail('invalid-email');
    $client->setPhoneNumber('0123456789');
    $client->setAddress('123 Main St');

    $errors = $this->validator->validate($client);

    $this->assertGreaterThan(0, $errors->count());
  }
}
