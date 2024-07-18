<?php

declare(strict_types=1);

namespace Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
]
class EntitySimpleType
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private int $integer;

    #[ORM\Id, ORM\Column(type: 'smallint', nullable: false)]
    private int $smallint;

    #[ORM\Column(type: 'bigint', nullable: false)]
    private string $bigint;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $string;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $text;

    #[ORM\Column(type: 'decimal', nullable: false)]
    private string $decimal;

    #[ORM\Column(type: 'float', nullable: false)]
    private float $float;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $boolean;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTime $datetime;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $datetime_immutable;

    #[ORM\Column(type: 'date', nullable: false)]
    private DateTime $date;

    #[ORM\Column(type: 'date_immutable', nullable: false)]
    private DateTimeImmutable $date_immutable;

    #[ORM\Column(type: 'guid', nullable: false)]
    private string $guid;

    #[ORM\Column(type: 'simple_array', nullable: false)]
    private array $simple_array;

    #[ORM\Column(type: 'json', nullable: false)]
    private array $json;

    #[ORM\Column(type: 'json_object', nullable: false)]
    private array $json_object;

    #[ORM\Column(type: 'serialized', nullable: false)]
    private array $serialized;
}
