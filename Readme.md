Symfony Twitter Application
========================

L'application Symfony Twitter a pour but de faire intéragir plusieurs personnes comme le vrai réseau social Twitter.

Pré requis
------------

  * PHP 8.1.0 or higher;
  * MySQL

Récupération GIT
------------

Créez un repertoire 'SymfonyTwitter', placez-vous dedans et récupérez le projet GIT :

```bash
$ git clone https://github.com/osscoco/SymfonyTwitter.git
```

Installation des dépendances
------------

Ouvez le terminal et executez la commande :

```bash
$ composer install
```

Configuration .env
------------

```bash
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
# https://symfony.com/doc/current/configuration/secrets.html
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=6bb6b004d13f9473d432263020fc51d5
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
DATABASE_URL="mysql://db_username:db_password@host:port/db_name?serverVersion=8.0"
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
# Choose one of the transports below
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/messenger ###

###> symfony/mailer ###
# MAILER_DSN=null://null
###< symfony/mailer ###
```

Execution de la migration
------------

Ouvez le terminal et executez la commande :

```bash
$ php bin/console doctrine:migration:migrate
```

Ouverture de l'application
------------

```bash
$ php -S localhost:8000 -t public
```

Tests
-----

Executez l'url suivante :

```bash
$ localhost:8000/api/documentation
```