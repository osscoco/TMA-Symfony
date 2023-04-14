Symfony Twitter Application (PARTIE EXPLICATION INSTALLATION APPLI)
========================

L'application Symfony Twitter a pour but de faire intéragir plusieurs personnes comme le vrai réseau social Twitter.

Pré requis
------------

  * PHP 8.1.0 or higher;
  * MySQL

Récupération (GIT)
------------

Créez un repertoire 'SymfonyTwitter' dans l'emplacement de votre ordinateur de votre choix, placez-vous dedans et récupérez le projet GIT :

```bash
$ git clone https://github.com/osscoco/SymfonyTwitter.git
```

Récupération (MOODLE)
------------

Placez-vous dans le repertoire récupéré sur Moodle

Installation des dépendances
------------

Ouvez le terminal et executez la commande :

```bash
$ composer install
```

Configuration database .env
------------

```bash
DATABASE_URL="mysql://db_username:db_password@host:port/db_name?serverVersion=8.0"
```

Execution de la migration
------------

Ouvez le terminal et executez la commande :

```bash
$ php bin/console doctrine:migration:migrate
```

Execution des jeux de tests d'insertions
------------

Ouvez le terminal et executez la commande :

```bash
$ php bin/console doctrine:fixtures:load
```

(TIPS : Si vous voulez remettre les ID des tables User et Tweet à 1)

```bash
ALTER TABLE `user` AUTO_INCREMENT = 1;
ALTER TABLE `tweet` AUTO_INCREMENT = 1;
```

Ouverture de l'application
------------

```bash
$ php -S localhost:8000 -t public
```

Tests
-----

Executez l'url suivante pour l'API :

```bash
$ localhost:8000/api/doc
```

Allez dans la route [AUTH] /api/login_check et remplissez le formulaire de connexion avec les informations suivantes :

email : root.root@iia-formation.fr
password : rootroot

Ensuite, copiez le token retourné, cliquez sur le bouton Authorize (en haut à droite de la page), et collez le dans le champs 'value' puis testez les différentes autres routes en dessous de /api/login_check

Executez l'url suivante pour le FRONT :

```bash
$ localhost:8000/
```
Formulaire de connexion :

email : root.root@iia-formation.fr
password : rootroot

Symfony Twitter Application (PARTIE EXPLICATION MISE EN PLACE DU PROJET)
========================

Création du projet Symfony
------------

Raccourci depuis la Stack Laragon

Création entités
------------

```bash
$ php bin/console make:entity User
$ php bin/console make:entity Tweet
```

* Configuration des entités avec l’ORM Doctrine :

  -	Repository User et Tweet
  -	Caractéristiques des champs des entités citées

*	Configuration des implémentations UserInterface et PasswordAuthenticatedInterface

Création Sécurité
------------

```bash
$ php bin/console make:auth
```

* Description :

  - Cela m’a permis de définir quelle classe doit s’authentifier et quel attribut doit être unique

* Configuration de controlleurs :
  
  -	Controller/SecurityController :
    
    *	Il faudra modifier le return de la méthode logout pour rediriger vers la route login.

  -	Security/UserAuthenticator :
    
    *	Il faudra modifier le return de la méthode onAuthenticationSuccess pour rediriger vers la route d’accueil

Création Formulaire d'enregistrement (Front)
------------

```bash
$ php bin/console make:registration-form
```

* Configuration du formulaire :

  -	Controller/RegistrationController :

    *	Il faudra ajouter les Setters TimeStamps car ils ne sont pas présent par défaut dans l’entité User

Création Formulaire Création Tweet et User (Front)
------------

```bash
$ php bin/console make:form Tweet
$ php bin/console make:form User
```

* Configuration du formulaire :

  -	Form/TweetType :

    *	Il faudra adapter ce formulaire par rapport à l’entité Tweet

  -	Form/UserType :

    *	Il faudra adapter ce formulaire par rapport à l’entité User

Création Controlleur API (BACK)
------------

```bash
$ php bin/console make:controller ApiController
```

* Description de la commande :

 -	Créer le controlleur qui stocke les méthodes CRUD de l’entité User et Tweet ainsi que la méthode login (avec les annotation OA/)

  *	Besoin : 

    - Lexik/jwt-authentication-bundle
    
      *	Package.yaml (configurations)
      *	Security.yaml (firewall)
      * OpenSSL Commandes : 
          
          - php bin/console lexik:jwt:generate-keypair --overwrite (Pour regenerer en cas de besoin private.pem et public.pem)
          - php bin/console lexik:jwt:generate-token --user-class App\Entity\User emailEnBdd

    - Nelmio/ApiDocBundle
      
      * Package.yaml (configurations)
      * Route.yaml (UI Nelmio /api/doc)

Création Controlleur Default (FRONT)
------------

```bash
$ php bin/console make:controller DefaultController
```

* Description de la commande :
  
  -	Créer le controlleur qui sert de Guard (utilisateur connecté ou non)

Création Controlleur Tweet et User (CRUD FRONT)
------------

```bash
$ php bin/console make:crud Tweet
$ php bin/console make:crud User
```

* Description des commandes :
  
  -	Créer les controllers, Repositories et Templates

Création DataFixtures (Import dans la Base de donnée)
------------

```bash
$ php bin/console doctrine:fixtures:load
```

* Description :

  -	Générer le fichier DataFixtures/AppFixtures
    
    * J’ai modifié le fichier pour générer des données aléatoires dans les tables User et Tweet















