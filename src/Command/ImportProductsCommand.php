<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Import products from CSV file',
)]
class ImportProductsCommand extends Command
{
    private string $projectDir;

    // On injecte le service EntityManagerInterface pour pouvoir sauvegarder les produits
    // Et on injecte le service ParameterBagInterface pour récupérer le chemin du projet
    public function __construct(
        private EntityManagerInterface $entityManager,
        ParameterBagInterface $params
    ) {
        parent::__construct();
        $this->projectDir = $params->get('kernel.project_dir');
    }

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Chemin du CSV à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $filepath = $this->projectDir . '/' . $filename;

        if (!file_exists($filepath)) {
            $io->error('Le fichier est introuvable : ' . $filepath);
            return Command::FAILURE;
        }

        if (($handle = fopen($filepath, 'r')) === false) {
            $io->error('Bon bah on arrive pas à l\'ouvrir : ' . $filepath);
            return Command::FAILURE;
        }

        //ça c'est pour sauter la première ligne (header)
        fgetcsv($handle);

        $imported = 0;
        while (($data = fgetcsv($handle)) !== false) {
            // J'ai ajouté plus de possibilité par rapport à la consigne car je veux que ce soit fonctionnel avant tout :)
            if (count($data) >= 6) {
                $product = new Product();
                $product->setName($data[0] ?? "");
                $product->setDescription($data[1] ?? "");
                $product->setPrice((float)$data[2] ?? 0);
                $product->setStock($data[3] ?? 0);
                $product->setCharacter($data[4] ?? "");
                $product->setSize($data[5] ?? "");
                // Le seul problème avec ça c'est que du coup ça ne gère pas
                // l'upload donc ça part du principe que les images ont déjà été uploads
                $img = $product->setImageFilename($data[6] ?? "");

                $this->entityManager->persist($product);
                $imported++;
            }
        }

        fclose($handle);
        $this->entityManager->flush();

        $io->success(sprintf('%d products imported successfully', $imported));

        return Command::SUCCESS;
    }
}
