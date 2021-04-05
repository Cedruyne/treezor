# Treezor - test technique
## Initialisation :
Build des images et démarrage des containers:

`docker-compose up -d`

Création des tables nécessaires au projet :

`docker-compose exec myapp sh -c 'php myapp/bin/console doctrine:migrations:migrate'`