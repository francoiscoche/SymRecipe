# SymRecipe
App did with Symfony and Docker. Made to learn.

Inspiration from [@Emilien.Gts](https://gitlab.com/Emilien.Gts) [tutorial](https://youtu.be/3K6oBiQK8aA)

<br>

<!-- implentation de fixtures and fakerPHP -->
- Integration of [DoctrineFixturesBundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html) and [FakePHP](https://fakerphp.github.io/) for generating false test data.
- Integration of [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle) to use a pagination system.
- Integration of [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle) to manage uploading pictures.
- Integration of [KarserRecaptcha3Bundle](https://github.com/karser/KarserRecaptcha3Bundle) to securise form contact.

*Developpement in progress ...*

<br>

## Run localy the project

<br>

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
cd .\project\
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
