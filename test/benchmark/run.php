<?php

require(__DIR__ . '/../../vendor/autoload.php');

$typeSpecification = "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}";
$iterations        = 100000;
$variable          = [
    'foo' => new stdClass(),
    'bar' => [
        123 => -2.34,
    ],
];

$timestampStart = microtime(true);

for ($i = 0; $i < $iterations; ++$i) {
    Jmf\TypeValidation\Type::mustBe($typeSpecification, $variable);
}

$timestampEnd = microtime(true);

$duration = ($timestampEnd - $timestampStart) * 1000.0;

echo "{$duration} ms.", PHP_EOL;
