typing
======

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

### Typical use

```php
<?php

namespace My;

use perf\Typing\Type;

class PotatoPeeler
{

    /**
     * @param Potato[] $potatoes
     * @return void
     */
    public function peel(array $potatoes)
    {
        Type::mustBe('\My\Potato[]', $potatoes);

        // ...
    }
}
```
