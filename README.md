# PHP Attributes Extractor

Easy way to extract and handle PHP Attributes.

## Requirements

* PHP >= 8.0

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
With this package you can simplify: 
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$attributes = Attributes::fromClass(MyClass::class)->instances();
```
Explaining parameters in detail :
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$attributes 
    = Attributes::fromClass(
        // classes: string or an array of className
        [MyClass::class, Another_Class::class],
        
        // target: where to search for attributes
        // default value is Attribute::TARGET_ALL
        Attribute::TARGET_METHOD|Attribute::TARGET_PROPERTY,  
        
        // attribute: the attribute name (string)
        // default values is NULL (search for all attributes)
        MyAttribute::class, 
        
        // flags: flags to filter attributes.     
        // default values is 0 (no filter will be applied )
        ReflectionAttribute::IS_INSTANCEOF
    );
```
This will return a collection of [`ParsedAttribute`](src/ParsedAttribute.php).
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$attributes = Attributes::fromClass(/** ... */);

$attributes->count();                       // int
$attributes->isEmpty();                     // bool
$attributes->hasAttribute(MyAttr::class);   // bool
$attributes->hasMany(MyAttr::class);        // bool
$attributes->first();                       // object ParsedAttribute
$attributes->filter($callable);             // new collection
$attributes->instances();                   // array of attributes instances
$attributes->firstInstance($defaultValue);  // instance of first parsed attribute from collection
$attributes->targets();                     // Reflection objects target by attributes
$attributes->whereAttribute(MyAttr::class); // new collection filtered by attribute name
$attributes->whereTarget(Attribute::TARGET_CLASS); // new collection filtered by attribute target
```

## Run test suite

```bash
composer test
```

## Run analysis
* Test Suit
* Static Analyse
* Coding Standard

```bash
composer check
```
