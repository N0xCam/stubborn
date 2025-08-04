Documentation Technique - Application Stubborn

1. Présentation du projet
Stubborn est une boutique en ligne spécialisée dans la vente de sweats éthiques. L'application permet aux utilisateurs de parcourir des produits, filtrer par tranche de prix, ajouter au panier et finaliser leur commande via Stripe. Une interface administrateur
permet la gestion des produits. Note : Le design proposé dans cette application est une adaptation UI/UX des wireframes
fournis, afin d’offrir une véritable expérience utilisateur.
Pour tester en mode admin : identifiant : admin / mot de passe : admin123

3. Technologies utilisées
- PHP 8.3
- Symfony 6.x
- Twig (moteur de templates)
- Doctrine ORM
- Stripe (paiement sécurisé)
- HTML/CSS
- PHPUnit (tests)
- MySQL
- Bootstrap

3. Installation et configuration
Voici les étapes pour installer et configurer le projet en local :
1. Cloner le dépôt : `git clone https://github.com/N0xCam/stubborn.git`
2. Installer les dépendances : `composer install` 3. Copier le fichier `.env` en `.env.local` et adapter les variables (ex. Stripe)
4. Créer et migrer la base de données : - `php bin/console doctrine:database:create` - `php bin/console doctrine:migrations:migrate`
5. Charger les fixtures : `php bin/console doctrine:fixtures:load`
6. Lancer le serveur local : `symfony server:start`

4. Fonctionnalités principales
- Page d’accueil avec mise en avant de produits
- Page boutique avec filtres par tranches de prix
- Page produit avec choix de la taille et ajout au panier
- Panier stocké en session (ajout, suppression, validation)
- Paiement sécurisé avec Stripe (checkout)
- Interface administrateur (dashboard CRUD produits)
- Authentification utilisateur
- Tests automatisés (PHPUnit)
  
5. Structure du projet
├── config/
├── public/
│ └── styles.css
├── src/
│ ├── Controller/
│ ├── Entity/
│ └── Repository/
├── templates/
├── tests/
│ └── Controller/
├── .env
├── composer.json
├── README.md

6. Tests
Les tests sont lancés via PHPUnit avec la commande `php bin/phpunit`.
Ils couvrent :
- Le fonctionnement du panier
- La redirection après paiement
- L’affichage conditionnel en fonction de l’état connecté ou non
