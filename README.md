Yii CoffeeScript Extension
==========================

### Quick Start
Clone the repo, `git clone git@github.com:apburton84/yii-coffeescript-extension.git`, or [download the latest release](https://github.com/apburton84/yii-coffeescript-extension/zipball/master).

### Installtion

add the coffeescript extension as a component to your yii configuration.

// application components
'components' => array(
    'coffeescript' => array( 
        'class' => 'ext.coffeescript.components.CoffeeScriptCompiler', 
        'forceCompile' => true, 
        'paths' => array( 
            'coffee/test.coffee' => 'js/test.js', 
        ),
    ), 
    ...
)
