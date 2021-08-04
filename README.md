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
With this package you can **simplify**: 
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$instances = Attributes::fromClass(MyClass::class)->instances();
```
Explaining parameters in detail:
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$attributes = 
     Attributes::fromClass(
        // classes: object|string|array of className or object
        [MyClass::class, Another_Class::class, $object],
        
        // target: where to search for attributes
        // default value is Attribute::TARGET_ALL (parse entire class)
        Attribute::TARGET_METHOD|Attribute::TARGET_PROPERTY,  
        
        // attribute: the attribute name (string)
        // default values is NULL (search for all attributes)
        MyAttribute::class, 
        
        // flags: flags to filter attributes.     
        // default values is 0 (no filter will be applied)
        ReflectionAttribute::IS_INSTANCEOF
    );
```
This will return a collection of [`ParsedAttribute`](src/ParsedAttribute.php).
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$attributes = Attributes::fromClass(/** ... */);

$attributes->attributes();                  // ReflectionAttribute[]
$attributes->count();                       // int
$attributes->isEmpty();                     // bool
$attributes->hasAttribute(MyAttr::class);   // bool
$attributes->hasMany(MyAttr::class);        // bool
$attributes->first();                       // (object) ParsedAttribute
$attributes->filter($callable);             // new collection 
$attributes->instances();                   // array of attributes instances
$attributes->firstInstance($defaultValue);  // instance of first parsed attribute
$attributes->targets();                     // Reflection objects target by attributes
$attributes->whereAttribute(MyAttr::class); // new collection filtered by attribute name
$attributes->toArray();                     // ParsedAttribute[]
$attributes->whereTarget(Attribute::TARGET_CLASS); // new collection filtered by target

// Iterable
foreach ($attributes as $attr) {
    $attr->attribute(); // ReflectionAttribute
    $attr->target();    // ReflectionClass|ReflectionClassConstant|
                        // ReflectionProperty|ReflectionMethod|ReflectionParameter
}

```
The collection is immutable and fluent:
```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;

$attributes = Attributes::fromClass(MyClass::class);

// Get all instances for MyAttr on class properties
$attributes
    ->whereTarget(Attribute::TARGET_PROPERTY)
    ->whereAttribute(MyAttr::class)
    ->instances();

// You can filter the collection (like above), but it's much better:
// ... avoid parsing the entire class 
$attributes = Attributes::fromClass(MyClass::class, Attribute::TARGET_PROPERTY, MyAttr::class);
$attributes->instances();
```

#### Filtering

```php
<?php
use BrenoRoosevelt\PhpAttributes\Attributes;
use BrenoRoosevelt\PhpAttributes\Specification\Criteria;
use BrenoRoosevelt\PhpAttributes\Specification\AttributeTarget;
use BrenoRoosevelt\PhpAttributes\Specification\TargetMatchType;

$attributes = Attributes::fromClass(MyClass::class);

$attributes
    ->where(
        Criteria::and(
            new AttributeTarget(Attribute::TARGET_PROPERTY), 
            new TargetMatchType(AnyClassType::class) // or primitives 'int', 'float', ...
        )
    )
    ->instances();
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
