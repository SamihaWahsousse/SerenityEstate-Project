# SerenityEstate-Project

Application web pour la gestion d'une agence immobilière,
Application avec des annonces immobilière et un backoffice sécurisé .

**Symfony 6.3**
**PHP 8**

- Pagination
- Images
- Autocompletion Geoapify pour l'ajout des addresses des biens immobiliers.
- Gestion des photos des biens immobilier via une API de gestion des fichiers.
- Filtres de recherche de biens en fonction de la surface, du prix, du nombre de pièces ou des options définies.[en cours]
- Système d'administration - back-office sécurisé

## Installation

1. Clonez le dépôt : `https://github.com/SamihaWahsousse/SerenityEstate-Project.git`
2. Installer les dépendances : `composer install`
3. Créer la base de données : `bin/console doctrine:database:create`
4. Lancer la base de données : `bin/console doctrine:schema:update –force`
5. Lancer les fixtures : `bin/console doctrine:fixture:load`
6. Lancez le serveur interne : `symfony server:start`

L'application est prête à être utilisée!
