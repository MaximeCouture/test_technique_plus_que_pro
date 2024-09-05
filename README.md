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
docker exec symfony-docker-main-php-1 php bin/console d:m:m 
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

#### naviguer vers http://localhost:3000
#### naviguer vers http://localhost/admin pour acceder au back office

### Certaines des resources utilisées pour m'aider

https://dev.to/fpaghar/get-and-set-the-scroll-position-of-an-element-with-react-hook-4ooa

https://usehooks.com/usedebounce

https://react-bootstrap.netlify.app/docs/components/accordion

https://tanstack.com/query/v3/docs/framework/react/overview

https://www.themoviedb.org/talk/6558fa627f054018d5168d91

https://symfony.com/doc/current/http_client.html#limit-the-number-of-requests
