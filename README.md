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

### Validation with exceptions

```php
<?php

use perf\Typing\Type;

$validInput = array('foo', 'bar');

// Valid, will not throw an exception.
Type::mustBe('string[]', $validInput);


$invalidInput = array(123);

// Invalid, will throw an exception.
Type::mustBe('string[]', $invalidInput);
```

### Validation with booleans

```php
<?php

use perf\Typing\Type;

$validInput = array('foo', 'bar');

// Valid, will return true.
if (Type::is('string[]', $validInput)) {
	// Valid
} else {
	// Invalid
}


$invalidInput = array(123);

// Invalid, will return false.
if (Type::is('string[]', $invalidInput)) {
	// Valid
} else {
	// Invalid
}
```

### Using the concrete validator

You can also use a concrete instance of a type validator if you need to inject it in your own objects.

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
