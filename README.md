[![Build Status](https://travis-ci.org/JeuxAmateurs/website.svg?branch=master)](https://travis-ci.org/JeuxAmateurs/website)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JeuxAmateurs/website/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JeuxAmateurs/website/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d149893e-b9ec-4689-ae99-3363332b1aae/mini.png)](https://insight.sensiolabs.com/projects/d149893e-b9ec-4689-ae99-3363332b1aae)

JeuxAmateurs
============

Site communautaire sur la création de jeux vidéo amateurs. Actuellement en construction.
La licence n'a pas encore été choisie, tous les droits sont réservés pour le moment.

1) Installation
---------------

Le projet est basé sur Symfony2 et gère ses dépendances via l'outil Composer. Le projet utilise la nouvelle structure de répertoires (par défaut dans le futur Symfony3).

    php composer.phar install

Le second permettra d'accéder à l'API de JeuxAmateurs.

2) Création de la base de données et de la structure
----------------------------------------------------

    php bin/console doctrine:database:create # création de la bdd
    php bin/console doctrine:schema:create # création de la structure
    
Si vous souhaitez commencer avec des données fictives, vous pouvez charger les fixtures.

    php bin/console doctrine:fixtures:load
    
3) Lancer les tests avant de contribuer
---------------------------------------

Si vous souhaitez contribuer, vérifiez bien qu'il n'y ait pas de régression avant de proposer une pull request.

    php bin/phpunit
