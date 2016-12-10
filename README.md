.checkout
=========

A Symfony project created on December 9, 2016, 6:23 pm.

### Console commands:

- Start web server: `php bin/console server:run --port 3000`
- In case of `date.timezone` exception place this code in `/app/AppKernel.php`:
```php
public function __construct($environment, $debug)
{
    date_default_timezone_set( 'Europe/Sofia' );
    parent::__construct($environment, $debug);
}
```

- Entity generation wizard console tool
```
php bin/console doctrine:generate:entity
AppBundle:Article
...
```

Names of our tables should be pluralized, so - go to /src/AppBundle/Entity/Article.php and change this: @ORM\Table(name="articles")

- Create DB tables with Doctrine
`php bin/console doctrine:schema:update --force`

- Clear cache
`php bin/console cache:clear --env=prod`

- Drop whole database
`php bin/console doctrine:database:drop --force`

- Create database
`php bin/console doctrine:database:create`

### Steps

- `php bin/console doctrine:generate:entity` - create user entity
- `AppBundle:Player`

- `$this->addFlash('danger', 'Planet name cannot be empty');` - flash message types: success, info, warning, danger

