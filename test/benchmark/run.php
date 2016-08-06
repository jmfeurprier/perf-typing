<?php

require(__DIR__ . '/../../vendor/autoload.php');

$typeSpecification = "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}";
$iterations        = 1000;
$variable          = array(
    'foo' => new \stdClass(),
    'bar' => array(
        123 => -2.34,
    ),
);

$timestampStart = microtime(true);

for ($i = 0; $i < $iterations; ++$i) {
    \perf\TypeValidation\Type::mustBe($typeSpecification, $variable);
}

$timestampEnd = microtime(true);

$duration = ($timestampEnd - $timestampStart) * 1000.0;

echo "{$duration} ms.", PHP_EOL;
