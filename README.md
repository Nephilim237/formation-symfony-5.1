# formation-symfony-5.1
A basic symfony project of announcements
Get bookings, give notation, add users, announcements, comments and notations, edit all of them
remove or delete them.
A complete system

# Installation

Open the command line or powershell

1- On clone le depot / clone de repository
git clone https://github.com/Nephilim237/formation-symfony-5.1.git

2- On se deplace dans le dossier / get in the project root
cd formation-symfony-5.1

3- On installe les dependances / Install all dependecies
composer install

4- On cree la base de donnees / Create database
php bin/console doctrine:database:create

5- On execute les migrations / run migrations
php bin/console doctrine:migrations:migrate

6- On execute la fixture (Remplissage de la base de donnees) / Run fixtures
php bin/console doctrine:fixtures:load --no-interaction

7- On lance le serveur / Start the server
php bin/console server:run

On peut commencer / We can start
