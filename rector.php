<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (
    RectorConfig $rectorConfig
): void {
    $rectorConfig->paths(
        [
            __DIR__ . '/src',
            __DIR__ . '/test',
        ]
    );
    $rectorConfig->skip(
        [
            Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector::class,
            Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector::class,
            Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector::class,
/*
            Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
            Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector::class,
            Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector::class,
            Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector::class,
*/
        ]
    );
    $rectorConfig->sets(
        [
            LevelSetList::UP_TO_PHP_82,
            SetList::CODE_QUALITY,
            SetList::CODING_STYLE,
            SetList::DEAD_CODE,
        ]
    );
};
