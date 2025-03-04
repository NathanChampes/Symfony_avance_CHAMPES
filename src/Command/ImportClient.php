<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
  name: 'app:import-client',
  description: 'Import client',
)]
class ImportClient extends Command
{
  // J'ai principalement repris le fonction de la commande ImportProductsCommand.php
  private EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    parent::__construct();
    $this->entityManager = $entityManager;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $lastname = $input->getArgument('lastname');
    $firstname = $input->getArgument('firstname');
    $email = $input->getArgument('email');
    $phone = $input->getArgument('phone');
    $address = $input->getArgument('address');

    $client = new Client();
    $client->setLastname($lastname);
    $client->setFirstname($firstname);
    $client->setEmail($email);
    $client->setPhoneNumber($phone);
    $client->setAddress($address);

    $this->entityManager->persist($client);
    $this->entityManager->flush();

    $io->success('Client ajouté avec succès.');

    return Command::SUCCESS;
  }


  protected function configure(): void
  {
    $this->addArgument("lastname", InputArgument::REQUIRED, "Lastname of the client");
    $this->addArgument("firstname", InputArgument::REQUIRED, "Firstname of the client");
    $this->addArgument("email", InputArgument::REQUIRED, "Email of the client");
    $this->addArgument("phone", InputArgument::REQUIRED, "Phone of the client");
    $this->addArgument("address", InputArgument::REQUIRED, "Address of the client");
  }
}
