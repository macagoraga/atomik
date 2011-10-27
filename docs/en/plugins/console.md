
# Console

This plugins allow Atomik to be used in a terminal. It allows other plugins to provide
custom commands and to create scripts to better administer your application.

To call your application from the command line, use the following command

	php index.php [command] [args]

Where *index.php* is Atomik's core file.

## Creating custom scripts

Scripts are simple php files. You can do anything you want with them. They are located
in the *app/scripts* folder. This can be changed using the *scripts\_dir*
conguration key.

It is possible for the script to access the command line arguments. A variable named $arguments
is available in script files. It is an array containing all the arguments after the command name (like $\_SERVER[argv]).

To call your script use the previous command line by replacing [command] with the filename of your
script without the extension.

Let's create a script in *app/scripts/cleanup-my-db.php*

    <?php
    ConsolePlugin::println('cleaning ' . $arguments[0]);

To call this script use the following command:

    php index.php cleanup-my-db "dbname"

## Using commands

A command is like a script but in the form of a function. Commands needs to be registered.
Their useful because they provide a way for any plugins to add its own command for administration.

To register a command use the *ConsolePlugin::register()* method. It takes
as first argument the command name (the one that will be used in the command line) and a php
callback to the command's associated function or method.

Like scripts, commands can retreive the arguments from the command line. They will be passed as the first
parameter of the callback

    function sayHelloTo($arguments)
    {
	    ConsolePlugin::println('hello ' . $arguments[0]);
    }
    ConsolePlugin::register('say-hello-to', 'sayHelloTo');

Plugins can listen for the Console::Start event to register their commands.

## Built-in commands

The plugin provides one built-in command to generate new actions and views. 
Just specify a name and the action file and the view file will be generated. 
You can generate multiple pages by separating them by a space

    php index.php generate home
    php index.php generate photos about

## Utility methods

The Console plugin also provides some utility methods.

*ConsolePlugin::println()* can be used to print a message on a single line.
You can also control the indentation using the second argument (which is an int).

*ConsolePlugin::success()* and *ConsolePlugin::fail()* are
two methods to print a *[SUCCESS]* or *[FAIL]* string. A message can also be
printed. They can work in conjonction with *ConsolePlugin::println()*. A new line
is added after a success or fail call.

    ConsolePlugin::println('trying to connect to the remote server...');
    if ($connectionSuccess) {
	    ConsolePlugin::success();
    } else {
	    ConsolePlugin::fail();
    }

This will print something like (if success):

    trying to connect to the remote server... [SUCCESS]
			
You can create directories using *ConsolePlugin::mkdir()*.

    ConsolePlugin::mkdir('/my/dir');
    ConsolePlugin::mkdir('/my/dir', $indent, "My message to announce i'm creating a directory");

Finally, you can create files using *ConsolePlugin::touch()*.

    ConsolePlugin::touch('/path/to/my/file.txt');
    ConsolePlugin::touch('/path/to/my/file.txt', $fileContent);
    ConsolePlugin::touch('/path/to/my/file.txt', '', $indent, "My message to announce i'm creating a file");
