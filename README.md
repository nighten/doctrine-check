# Doctrine check

Tool for check doctrine entity field mapping with php types.

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

## The list of checks:

- Simple types such as int, string, bool, datetime and so on. Check match type and nullable

Example:

```php
#[ORM\Column(type: 'string', nullable: true)]
private ?string $code;

#[ORM\Column(type: 'integer', nullable: false, enumType: Type::class)]
private Type $type;

#[ORM\Column(type: 'boolean', nullable: false)]
private bool $deleted = false;

#[ORM\Column(type: 'datetime_immutable', nullable: true)]
private ?DateTimeImmutable $updatedAt = null;
```

- Association mapping ManyToOne:

Example:

```php
#[
    ORM\ManyToOne(targetEntity: User::class),
    ORM\JoinColumn(nullable: false),
]
private User $user;
```

## Configuration

### Add additional type mappings:

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

### Add specific entity classes:

The checker will check only the specified classes

```php

use App\Entity\EntityClass;
use Nighten\DoctrineCheck\Type;

//...

return function (DoctrineCheckConfig $config): void {
    //...
    $config->addEntityClass(EntityClass::class);
};
```

### Ignore some entity classes:

The checker will check all classes except those specified

```php

use App\Entity\EntityClass;
use Nighten\DoctrineCheck\Type;

//...

return function (DoctrineCheckConfig $config): void {
    //...
    $config->addExcludedEntityClasses(EntityClass::class);
};
```

### Add error ignores:

The checker will skip the specified errors

```php

use App\Entity\EntityClass;
use Nighten\DoctrineCheck\Type;

//...

return function (DoctrineCheckConfig $config): void {
    //...
    $config->addIgnore(EntityClass::class, 'name', ErrorType::TYPE_WRONG_NULLABLE);
};
```
