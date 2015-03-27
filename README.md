perf typing
============

Typing package from perf, the PHP Extensible and Robust Framework.

Validates a variable against a type specification (as the ones in PHPDoc blocks), like the following:
- integer
- mixed
- string
- string[]
- {string:mixed}
- etc.

## Usage

### Static validation

Static validation can be invoked from anywhere, and will throw an exception when the variable does not satisfy the type specification.

```php
<?php

use perf\Typing\Type;

$validInput = array('foo', 'bar');

// Valid, will not throw an exception.
Type::check('string[]', $validInput);


$invalidInput = array(123);

// Invalid, will throw an exception.
Type::check('string[]', $invalidInput);
```

### Concrete validation

```php
<?php

use perf\Typing\TypeValidator;

$validInput = array('foo', 'bar');

$validator = new TypeValidator();

if ($validator->isValid('string[]', $validInput)) {
	// Valid
} else {
	// Invalid
}
```
