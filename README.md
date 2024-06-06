# Doctrine check

Tool for check doctrine entity field mapping with php types. Beta version. Check only basic types.

## Install

```bash
composer require nighten/doctrine-check --dev
```

## Running 

Create a `doctrine-check-config.php` in your root directory and modify:

Need to add doctrine object manager to config. Example for Symfony project:
```php
use App\Kernel;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

return function (DoctrineCheckConfig $config): void {
    (new Dotenv())->bootEnv(__DIR__ . '/.env');
    $kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
    $kernel->boot();

    $config->addObjectManager($kernel->getContainer()->get('doctrine')->getManager());
};
```

Then run check:

```bash
vendor/bin/doctrine-check types
```

## Configuration

### Add new type mappings:

```php

use Symfony\Component\Uid\UuidV1;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\UuidV7;

//...

return function (DoctrineCheckConfig $config): void {
    //...
    $config->addTypeMapping('uuid', UuidV1::class);
    $config->addTypeMapping('uuid', UuidV4::class);
    $config->addTypeMapping('uuid', UuidV7::class);
};
```
