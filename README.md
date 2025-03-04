# Mon projet Symfony

## Introduction

Pour commencer, je vais remettre le projet dans son contexte.  
C'est un projet universitaire (BUT 3) qui a pour objectif de nous faire travailler notre maîtrise du framework Symfony.

Le sujet de ce projet est la création d'un Backoffice pour une entreprise.

Nous étions libres sur pas mal de choses, dont le choix de l'entreprise fictive.  
Étant un grand fan de Wallace et Gromit, c'est tout naturellement que je me suis tourné vers la création d'un Backoffice pour une entreprise de vente de peluches Wallace et Gromit.

J'ai rencontré quelques difficultés, mais globalement, j'ai trouvé le projet super sympa, et le libre choix de l'entreprise laisse place à la créativité.

Lors de ce projet, j'ai essayé de laisser plus de commentaires qu'à mon habitude.  
En effet, j'essaie de rendre mon code plus _maintenable_, et cela passe (pour moi) par une bonne documentation.  
Certains commentaires ont été générés par IA, mais la majorité est écrite à la main (comme ce README).

## Outils

Alors, pour ce projet, on avait pour consigne d'éviter l'IA, donc j'ai utilisé pas mal de documentation Symfony ainsi que des questions Stack Overflow.

En IDE, j'ai utilisé Neovim. Pour être compatible avec Symfony, j'ai juste utilisé les packs suivants :

```lua
  { import = "astrocommunity.pack.php" },
  { import = "astrocommunity.pack.tailwindcss" },
```

Après je n'ai pas utilisé grand-chose de particulier.

## Installation du projet

Bon, là, je vais vous décrire comment récupérer et peut-être lancer le projet si tout va bien.

1. On va commencer par clôner le projet :

```bash
git clone https://github.com/NathanChampes/Symfony_avance_CHAMPES.git
cd Symfony_avance_CHAMPES
```

2. Ensuite, on installe les dépendances :

```bash
composer install
npm install
```

3. On met en place la base de données :

```bash
php bin/console doctrine:database:migrate
php bin/console doctrine:migrations:migrate
```

4. On lance le serveur :

```bash
symfony local:server:start
```
