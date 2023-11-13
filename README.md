# SerenityEstate-Project

Real estate Application with listings and administration panel
Application web pour la gestion d'une agence immobilière

**Symfony 6.3**

- Pagination
- Images
- Autocompletion Geoapify 
- Filtres de recherche de biens en fonction de la surface, du prix, du nombre de pièces ou des options définies.
- Système d'administration - back-office sécurisé

## Installation

1. Clonez le dépôt : `https://github.com/SamihaWahsousse/SerenityEstate-Project.git`
2. Installer les dépendances : `composer install`
3. Créer la base de données : `bin/console doctrine:database:create`
4. Lancer la base de données : `bin/console doctrine:schema:update –force`
5. Lancer les fixtures : `bin/console doctrine:fixture:load`
6. Lancez le serveur interne : `symfony serve`

L'application est prête à être utilisée!
