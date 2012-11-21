Yii CoffeeScript Extension
==========================

A Yii extension that compiles CoffeeScript source files.

### Features

* Force compile on each request
* Concatenate multiple CoffeScript files

Installation
------------

Clone the repo 
    git clone git@github.com:apburton84/yii-coffeescript-extension.git, 
or 
    [download the latest release](https://github.com/apburton84/yii-coffeescript-extension/zipball/master).

Copy the yii-coffescript-extension folder to your Yii projects extensions folder.

Add the coffeescript extension as a component to your yii configuration.

```php
// application components
'components' => array(
    'coffeescript' => array( 
        'class' => 'ext.coffeescript.components.CoffeeScriptCompiler', 
        'paths' => array( 
            'coffee/test.coffee' => 'js/test.js', 
        ),
    ), 
    ...
),
```

Configuration
-------------

### Options

Available options:

*  paths    - an array of the CoffeeScript files to compile
*  disable  - disable the extention (for: production)
*  force    - force compile (for: development)
*  filename - The source file (for: debugging info: formatted into error messages)
*  header   - Add a header to the generated source (default: TRUE)
*  rewrite  - Enable rewriting token stream (for: debugging)
*  tokens   - Reference to token stream (for: debugging)
*  trace    - File to write parser trace to (for: debugging)

### Example

```php
'coffeescript' => array( 
    'force' => false, 
    ...
),
```

Usage
-----

To compile a list of files to the respective CoffeeScript files.

```php
'coffeescript' => array( 
    'class' => 'ext.coffeescript.components.CoffeeScriptCompiler', 
    'paths' => array( 
        'coffee/test.coffee' => 'js/test.js', 
    ),
),

or, if you want to concatenate multiple files in to a single CoffeeScript file.

```php
'coffeescript' => array( 
    'class' => 'ext.coffeescript.components.CoffeeScriptCompiler', 
    'paths' => array( 
        'js/test.js' => array('coffee/test-1.coffee', 'coffee/test-2.coffee'), 
        ...
    ),
),
```
