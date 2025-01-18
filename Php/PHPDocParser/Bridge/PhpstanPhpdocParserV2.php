<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\PHPDocParser\Bridge;

use Nighten\DoctrineCheck\Php\PHPDocParser\PHPDoc;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser as PhpDocParserVendor;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;

class PhpstanPhpdocParserV2
{
    public function parse(string $docBlock): PHPDoc
    {
        $config = new ParserConfig([]);
        $lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $phpDocParser = new PhpDocParserVendor($config, $typeParser, $constExprParser);
        $tokens = new TokenIterator($lexer->tokenize($docBlock));
        $phpDocNode = $phpDocParser->parse($tokens);

        $result = new PHPDoc();
        $varTags = $phpDocNode->getVarTagValues();
        foreach ($varTags as $varTag) {
            $this->compileTypes($varTag->type, $result);
        }
        //TODO: Add other params if need
        return $result;
    }

    private function compileTypes(TypeNode $type, PHPDoc $PHPDoc): void
    {
        if ($type instanceof IdentifierTypeNode) {
            $PHPDoc->addType($type->name);
        }
        if ($type instanceof UnionTypeNode) {
            foreach ($type->types as $unionType) {
                $this->compileTypes($unionType, $PHPDoc);
            }
        }
    }
}
