# Posterno Schema
A Posterno component that provides integration for rich snippets ( structured data ) via json-ld.

## Installation

This is a development repository for the schema component and requires no installation for production sites because it's integrated within Posterno's core plugin.

For development purposes you can clone this repository within the same folder where the main "posterno" plugin folder is located so that composer can properly load it.

## Automatic Schema.org definitions generator

This component provides a `generate` command that can be used to automatically re-generate all the definitions grabbed from the schema.org types. The automatic generation script, will build new files and store them under the `src` subdirectory.

To generate new definitions run the following command:

```
composer generate
```
