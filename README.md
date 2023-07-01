type validation
===============

This package allows to check if a variable is of the expected type, using a type specification string like the ones in PHPDoc blocks.

Type specifications strings can be like the following:
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

use Jmf\TypeValidation\Type;

// Valid, will not throw an exception.
Type::mustBe('string', 'foo');

// Invalid, will throw an exception.
Type::mustBe('string', 123);
```

### Static validation with booleans

```php
<?php

use Jmf\TypeValidation\Type;

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

use Jmf\TypeValidation\TypeValidator;

$validator = new TypeValidator();

$variable = 'foo';

if ($validator->isValid('string', $variable)) {
	// Valid
} else {
	// Invalid
}
```

### Typical use

```php
<?php

namespace App;

use Jmf\TypeValidation\Type;

class PotatoPeeler
{
    /**
     * @param Potato[] $potatoes
     */
    public function peel(array $potatoes): void
    {
        Type::mustBe('\App\Potato[]', $potatoes);

        // ...
    }
}
```
