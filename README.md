# PHP Attributes
[![Build](https://github.com/brenoroosevelt/php-attributes/actions/workflows/ci.yml/badge.svg)](https://github.com/brenoroosevelt/php-attributes/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/brenoroosevelt/php-attributes/branch/main/graph/badge.svg?token=S1QBA18IBX)](https://codecov.io/gh/brenoroosevelt/php-attributes)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/brenoroosevelt/php-attributes/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/brenoroosevelt/php-attributes/?branch=main)
[![Latest Version](https://img.shields.io/github/release/brenoroosevelt/php-attributes.svg?style=flat)](https://github.com/brenoroosevelt/php-attributes/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)

Easy way to extract and handle PHP Attributes.

## Requirements

* PHP >= 8.1

## Install 

```bash
composer require brenoroosevelt/php-attributes
```

## Usage
Instead of doing like this:

```php
<?php

$myAttribute = Attr::class;
$attributes = [];
$relfectionClass = new ReflectionClass(MyClass::class);
foreach ($relfectionClass->getAttributes($myAttribute) as $attribute) {
    $attributes[] = $attribute;
}

foreach ($relfectionClass->getMethods() as $methods) {
    foreach ($methods->getAttributes($myAttribute) as $attribute) {
        $attributes[] = $attribute;
    }
}

foreach ($relfectionClass->getProperties() as $property) {
     /** ... */
}

foreach ($relfectionClass->getReflectionConstants() as $property) {
    /** ... */
}

$instances = array_map(fn(ReflectionAttribute $attr) => $attr->newInstance(), $attributes);
```
With this package you can **simplify**:

```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$instances = Attributes::extract(MyAttr::class)->fromClass(MyClass::class)->getInstances();
```
Explaining parameters in detail:

```php
<?php
use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;

$extract = 
     Attributes::extract(
        // $attribute: the attribute name (string)
        // default values is NULL (search for all attributes)
        Attribute::class,
        
        // $flag: flags to filter attributes.     
        // default values is 0 (no filter will be applied)
        ReflectionAttribute::IS_INSTANCEOF
    );
```
All these methods will return a collection of [`ParsedAttribute`](src/ParsedAttribute.php).

 * `fromClass(string|object|array $objectOrClass): Collection`
 * `fromProperties(string|object|array $objectOrClass, string ...$property)`
 * `fromMethods(string|object|array $objectOrClass, string ...$method)`
 * `fromClassConstants(string|object|array $objectOrClass, string ...$constant)`
 * `fromParameters(string|object|array $objectOrClass, string $method, string ...$parameter)`
 * `fromConstructor(string|object|array $objectOrClass)`
 * `fromConstructorParameters(string|object|array $objectOrClass, string ...$parameter)`
   
The Collection class is immutable and fluent:

```php
<?php
/* $attributes = Attributes::extract()->from... */ 

// Collection
$attributes->add(new ParsedAttribute(...)) // new Collection instance (immutable)
$attributes->merge(new Collection);        // new Collection instance (immutable)
$attributes->getInstances();               // object[] array with attributes instances
$attributes->getTargets();                 // Reflector[] array with Reflection objects target by attributes
$attributes->getAttributes();              // ReflectionAttribute[]
$attributes->count();                      // int
$attributes->isEmpty();                    // bool
$attributes->first();                      // null|(object) ParsedAttribute
$attributes->toArray();                    // ParsedAttribute[]

// Iterable (ParsedAttribute[])
foreach ($attributes as $attr) {
    $attr->attribute(); // ReflectionAttribute
    $attr->target();    // ReflectionClass|ReflectionClassConstant|
                        // ReflectionProperty|ReflectionMethod|ReflectionParameter
}

```


## Contributing

### Run test suite
```bash
composer test
```

### Run analysis
* Test suite
* Static analysis
* Coding Standard

```bash
composer check
```
## License

This project is licensed under the terms of the MIT license. See the [LICENSE](LICENSE.md) file for license rights and limitations.