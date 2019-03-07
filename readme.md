# Posterno Schema
A Posterno component that provides integration for rich snippets ( structured data ) via json-ld. Big thanks to [https://github.com/spatie/schema-org](https://github.com/spatie/schema-org).

## Installation

This is a development repository for the schema component and requires no installation for production sites because it's integrated within Posterno's core plugin.

For development purposes you can clone this repository within the same folder where the main "posterno" plugin folder is located so that composer can properly load it.

## Automatic Schema.org definitions generator

This component provides a `generate` command that can be used to automatically re-generate all the definitions grabbed from the schema.org types. The automatic generation script, will build new files and store them under the `src` subdirectory.

To generate new definitions run the following command:

```
composer generate
```

## Programmatic usage

```php
use PNO\SchemaOrg\Schema;

$localBusiness = Schema::localBusiness()
    ->name('Test')
    ->email('demo@demo.com')
    ->contactPoint(Schema::contactPoint()->areaServed('Worldwide'));

$localBusiness->toScript();
```

```html
<script type="application/ld+json">
{
    "@context": "http:\/\/schema.org",
    "@type": "LocalBusiness",
    "name": "Test",
    "email": "demo@demo.com",
    "contactPoint": {
        "@type": "ContactPoint",
        "areaServed": "Worldwide"
    }
}
</script>
```

All types can be instantiated though the `PNO\SchemaOrg\Schema` factory class, or with the `new` keyword.

``` php
$localBusiness = Schema::localBusiness()->name('PNO');

// Is equivalent to:

$localBusiness = new LocalBusiness();
$localBusiness->name('PNO');
```

> *All types also accept arrays of the expected data type, for example `sameAs` accepts a string or an array of strings.*

All types also implement the SPL's `ArrayAccess` for accessing the properties via array notation:

```php
$anotherLocalBusiness = new LocalBusiness();
var_dump(isset($anotherLocalBusiness['name'])); // => false
$anotherLocalBusiness['name'] = 'PNO';
var_dump(isset($anotherLocalBusiness['name'])); // => true
var_dump($anotherLocalBusiness['name']); // => 'PNO'
unset($anotherLocalBusiness['name']);
var_dump(isset($anotherLocalBusiness['name'])); // => false
```

Types can be converted to an array or rendered to a script.

```php
$localBusiness->toArray();

$localBusiness->toScript();

echo $localBusiness; // Same output as `toScript()`
```

Additionally, all types can be converted to a plain JSON string by just calling `json_encode()` with your object:

```php
echo json_encode($localBusiness);
```

### Enumerations

As of v1.6.0, all [Enumeration](http://schema.org/Enumeration) child types are available as classes with constants.

```php
Schema::book()->bookFormat(PNO\Schema\BookFormatType::Hardcover);
```

There's no full API documentation for types and properties. You can refer to [the source](https://github.com/Posterno/posterno-schema/tree/master/src) or to [the schema.org website](http://schema.org).

If you don't want to break the chain of a large schema object, you can use the `if` method to conditionally modify the schema.

```php
use PNO\SchemaOrg\LocalBusiness;
use PNO\SchemaOrg\Schema;

$business = ['name' => 'PNO'];

$localBusiness = Schema::localBusiness()
    ->name($business['name'])
    ->if(isset($business['email']), function (LocalBusiness $schema) {
        $schema->email($business['email']);
    });
```

I recommended double checking your structured data with [Google's structured data testing tool](https://search.google.com/structured-data/testing-tool)

### Advanced Usage

If you'd need to set a custom property, you can use the `setProperty` method.

```php
$localBusiness->setProperty('foo', 'bar');
```

If you'd need to retrieve a property, you can use the `getProperty` method. You can optionally pass in a second parameter to provide a default value.

```php
$localBusiness->getProperty('name'); // 'PNO'
$localBusiness->getProperty('bar'); // null
$localBusiness->getProperty('bar', 'baz'); // 'baz'
```

All properties can be retrieved as an array with the `getProperties` method.

```php
$localBusiness->getProperties(); // ['name' => 'PNO', ...]
```

Multiple properties can be set at once using the `addProperties` method.

```php
$localBusiness->addProperties(['name' => 'value', 'foo' => 'bar']);
```

Context and type can be retrieved with the `getContext` and `getType` methods.

```php
$localBusiness->getContext(); // 'http://schema.org'
$localBusiness->getType(); // 'LocalBusiness'
```

### Graph - multiple items

The Graph has a lot of methods and utilities - the type-safe and simplest way is to use the overloaded methods of the `PNO\SchemaOrg\Schema` class itself. These methods will get an already created or new instance of the requested schema.

```php
$graph = new Graph();

// Create a product and prelink organization
$graph
    ->product()
    ->name('My cool Product')
    ->brand($graph->organization());

// Hide the organization from the created script tag
$graph->hide(\PNO\SchemaOrg\Organization::class);

// Somewhere else fill out the organization
$graph
    ->organization()
    ->name('My awesome Company');

// Render graph to script tag
echo $graph;
```

With these tools the graph is a collection of all available schemas, can link these schemas with each other and prevent helper schemas from being rendered in the script-tag.

## Known Issues

### Type Inheritance

The spec rdfa document that's used to generate this code uses single inheritance for the types. However, the spec on http://schema.org uses multiple inheritance in some cases. Read the docs and use [Google's structured data testing tool](https://search.google.com/structured-data/testing-tool) to ensure you're on the right track!

For example, according to the rdfa, a `LocalBusiness` inherits properties from `Organization`. However, if you visit the spec page on [Schema.org](https://schema.org/LocalBusiness), it inherits properties from `Organization` and `Place`. The current solution is by manually specifying properties on the item, as described above in [advanced usage](#advanced-usage).

```php
Schema::localBusiness()

    // `address` is part of `Organization`, so the method exists
    ->address(/* ... */)

    // `openingHoursSpecification` is part of `Place`, so we need to manually add it
    ->setProperty('openingHoursSpecification', /* ... */);
```
