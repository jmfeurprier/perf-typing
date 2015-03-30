perf typing
============

Typing package from perf, the PHP Extensible and Robust Framework.

Validates a variable against a type specification (as the ones in PHPDoc blocks), like the following:
```
- integer
- scalar
- mixed
- string
- float[]
- int[][]
- {string:float}
- {int:\My\Stuff}
- {int:string}[]
- resource
- int|string
- {string:{int:\My\Stuff|int[]}}[]
```
etc.

## Usage

### Static validation with exceptions

```php
<?php

use perf\Typing\Type;

// Valid, will not throw an exception.
Type::mustBe('string', 'foo');

// Invalid, will throw an exception.
Type::mustBe('string', 123);
```

### Static validation with booleans

```php
<?php

use perf\Typing\Type;

$variable = 'foo';

if (Type::is('string', $variable)) {
	// Valid
} else {
	// Invalid
}
```

### Concrete validation

You can also use a concrete instance of a type validator.

```php
<?php

use perf\Typing\TypeValidator;

$validator = new TypeValidator();

$variable = 'foo';

if ($validator->isValid('string', $variable)) {
	// Valid
} else {
	// Invalid
}
```
