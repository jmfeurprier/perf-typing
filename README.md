perf typing
============

Typing package from perf, the PHP Extensible and Robust Framework.

Validates a variable against a type specification (as the ones in PHPDoc blocks), like the following:
- integer
- mixed
- string
- string[]
- resource
- {string:float}
- etc.

## Usage

### Validation with exceptions

```php
<?php

use perf\Typing\Type;

// Valid, will not throw an exception.
Type::mustBe('string', 'foo');

// Invalid, will throw an exception.
Type::mustBe('string', 123);
```

### Validation with booleans

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

### Using the concrete validator

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
