# blog-symfony
Blog app with Symfony and Docker. Made to learn.

<!-- implentation de fixtures and fakerPHP -->
- Integration of [DoctrineFixturesBundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html) and [FakePHP](https://fakerphp.github.io/) for generating false test data.
- Integration of [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle) to use a pagination system.


*Developpement in progress ...*



## Run localy the project

&nbsp;

Clone the project

```bash
git clone git@github.com:francoiscoche/symrecipe.git
```
Run the docker-compose

```bash
docker-compose build
docker-compose up -d
```

Copy the vendor folder to the container (did for performance purpose)
```bash
cd .\app\
docker cp vendor php8-symrecipe:/var/www/project
docker cp var php8-symrecipe:/var/www/project
```

Log into the PHP container

```bash
docker exec -it jobme-php8 bash
```

Start the server

```bash
symfony serve -d
```
*The application is available at http://127.0.0.1:9000*


Create fixtures
```bash
php bin/console doctrine:fixtures:load
```

### Author

From a [Tuto](https://youtu.be/3K6oBiQK8aA) by [@Emilien.Gts](https://gitlab.com/Emilien.Gts)

Adapted by [@francoiscoche](https://github.com/francoiscoche)
