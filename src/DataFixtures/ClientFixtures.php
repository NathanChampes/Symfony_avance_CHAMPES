<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {

    $clientsData = [
      [
        'firstname' => 'Wallace',
        'lastname' => 'Inventor',
        'email' => 'wallace@example.com',
        'phoneNumber' => '0123456789',
        'address' => '62 West Wallaby Street, Wigan',
      ],
      [
        'firstname' => 'Gromit',
        'lastname' => 'Dog',
        'email' => 'gromit@example.com',
        'phoneNumber' => '0987654321',
        'address' => '62 West Wallaby Street, Wigan',
      ],
      [
        'firstname' => 'Wendolene',
        'lastname' => 'Ramsbottom',
        'email' => 'wendolene@example.com',
        'phoneNumber' => '0112233445',
        'address' => 'Sheep Street, Wigan',
      ],
      [
        'firstname' => 'Shaun',
        'lastname' => 'Sheep',
        'email' => 'shaun@example.com',
        'phoneNumber' => '0223344556',
        'address' => 'Farm Road, Wigan',
      ],
    ];

    foreach ($clientsData as $data) {
      $client = new Client();
      $client->setFirstname($data['firstname']);
      $client->setLastname($data['lastname']);
      $client->setEmail($data['email']);
      $client->setPhoneNumber($data['phoneNumber']);
      $client->setAddress($data['address']);
      $manager->persist($client);
    }

    $manager->flush();
  }
}
