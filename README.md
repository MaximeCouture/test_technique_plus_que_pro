# test_technique_plus_que_pro

## Initialisation du Projet

### Partie Backend

Lancer le container backend (depuis le dossier root)
```
cd symfony-docker-main
docker compose up -d --wait
```

Maj BDD et import des films, possibilité de créer un cron pour lancer la commande régulierement et garder une base a jour.
Pour 10 pages (soit environ 250 films en fonction des doublons entre jour et semaines) ~10 secondes en local
```
docker exec symfony-docker-main-php-1 php bin/console d:m:m -n
docker exec symfony-docker-main-php-1 php bin/console app:import-movie 10
```

Il y a aussi une commande import-genre (sans parametres) mais les genres sont automatiquement importer lors de l'import de films

### Partie Frontend

Lancer le container frontend (depuis le dossier root)
```
cd react-front
npm install
docker compose up -d --wait
```

## Testing

### Avant de lancer les test, ne pas oublier de creer la bdd et lancer les migrations dans l'env de test
```
docker exec symfony-docker-main-php-1 php bin/console --env=test doctrine:database:create 
docker exec symfony-docker-main-php-1 php bin/console --env=test d:m:m -n
```

Le package dama/doctrine-test-bundle est utilisé pour que la BDD soit vidée entre chaque test.

#### naviguer vers http://localhost:3000 
#### naviguer vers http://localhost/admin pour acceder au back office

### Certaines des resources utilisées pour m'aider

Mise en place de l'infinite scroll dans React
https://dev.to/fpaghar/get-and-set-the-scroll-position-of-an-element-with-react-hook-4ooa

Debouncing de la recherche
https://usehooks.com/usedebounce

Documentation de la lib ReactBootstrap
https://react-bootstrap.netlify.app/docs/components/accordion

Documentation de la lib ReactQuery
https://tanstack.com/query/v3/docs/framework/react/overview

Information sur la limitation de requete sur l'api TMDB
https://www.themoviedb.org/talk/6558fa627f054018d5168d91

Mise en place d'un limiter pour httpClient dans symfony
https://symfony.com/doc/current/http_client.html#limit-the-number-of-requests

Configuration des Cors dans Symfony
https://symfony.com/bundles/NelmioCorsBundle/current/index.html

Doc de EasyAdmin
https://symfony.com/bundles/EasyAdminBundle/current/fields.html

Doc de Api Simulator
https://apisimulator.io/docs/1.12/standalone-api-simulator/request-matching.html

Test de Command Symfony
https://symfony.com/doc/current/console.html#testing-commands

Reset de la DB pour chaque test
https://symfony.com/doc/current/testing.html#resetting-the-database-automatically-before-each-test