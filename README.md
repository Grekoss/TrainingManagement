# Plateforme de formation

Application pour aider les formations au sein de l'enseigne McDonald's lors une évolution.

Utilisation test effectuer sur un site. Qui a rempli ses fonctions et potentiellement sera utilisé pour du long therme.

## Installation:

Cloner le projet

```shell
php bin/console composer install
```

-> renseigner les informations sur le fichier `.env.local` afin de permettre d'acceder à la base de donnée de votre serveur.

```shell
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

php bin/console ckeditor:install
(extension pour éditer le texte)
```

et si vous souhaite utiliser les fixtures -> `php bin/console doctrine:fixtures:load`

sinon, vous pouvez créer votre premier compte, l'adminitrateur par l'intermediaire de la ligne de commande suivante :
`php bin/console app:createAdmin`

```md
Temporairement, un compte étudiant en fixture sera mis en place:
ID : 'user@formation.fr'
Password: 'password'  
``` 
 

[&copy; Cédric POURRIAS - pourriascedric@gmail.com](mailto:pourriascedric@gmail.com)  
