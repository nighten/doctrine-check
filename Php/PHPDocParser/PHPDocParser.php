<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\PHPDocParser;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Exception\PackageNotFoundException;
use Nighten\DoctrineCheck\Php\PHPDocParser\Bridge\PhpstanPhpdocParserV1;
use Nighten\DoctrineCheck\Php\PHPDocParser\Bridge\PhpstanPhpdocParserV2;

class PHPDocParser implements PHPDocParserInterface
{
    /**
     * @throws PackageNotFoundException
     */
    public function parse(
        string $docBlock,
        DoctrineCheckConfig $config,
    ): PHPDoc {
        $versionProvider = $config->getPackageVersionProvider();
        if (!$versionProvider->hasPackage('phpstan/phpdoc-parser')) {
            throw new PackageNotFoundException('Need install "phpstan/phpdoc-parser" for use doc block check');
        }
        $version = $config->getPackageVersionProvider()->getVersion('phpstan/phpdoc-parser');
        //Make it simple right now, ma be change in future if you need
        if (str_starts_with($version, '1')) {
            $parserBridge = new PhpstanPhpdocParserV1();
        } else {
            $parserBridge = new PhpstanPhpdocParserV2();
        }
        return $parserBridge->parse($docBlock);
    }
}
