# Posterno Schema
posterno-schema is a Posterno component that provides integration for rich snippets ( structured data ) via json-ld.

## Installation

This is a development repository for the schema component and requires no installation for production sites because it's integrated within Posterno's core plugin.

For development purposes you can install this component as a composer dependency:

```
composer require posterno/schema:dev-master
```

## Automatic Schema.org definitions generator

This component provides a `generate` command that can be used to automatically re-generate all the definitions grabbed from the schema.org types. The automatic generation script, will build new files and store them under the `includes/classes` subdirectory. All files under the `generated` folder should not be manually modified.

To generate new definitions run the following command:

```
composer generate
```
