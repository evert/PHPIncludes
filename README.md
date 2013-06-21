PHP includes
============

This is a simple script that traverses your sourcecode and attempts and create
an 'includes' file.

Using an includes file instead of for example autoload can have significant
performance benefits.

Installation
------------

```json
{
    "require" : {
        "evert/phpincludes" : "~0.2"
    }
}
```

This will install the package into `vendor/evert/phpincludes`, and symlink the
executable in `vendor/bin`. I tend to do something like this:

```json
{
    "require" : {
        "evert/phpincludes" : "~0.2"
    },
    "config" : {
        "bin-dir" : "bin"
    }
}
```

So all my project's bin files are in a local bin/ directory. On my own system
I also have a `~/bin/` directory (in my home) and install various php
utilities using composer.

Usage
-----

    phpincludes [--php52] [-v] <directory> [outputfile]

      <directory>
        This is the directory that will be scanned for PHP files.

      [outputfile]
        Outputfile is the file PHPIncludes writes to. If it's not specified, it will
        be sent to STDOUT

        If the output file already exists, it will attempt to update the existing
        includes file. It does so by looking at two markers in the file:

        // Begin includes\n
        and
        // End includes\n

        Every before '// Begin includes\n' will be retained, as well as everything
        after '// End includes\n'. Everything in between will be overwritten. The
        \n is a unix newline.

      --php52

        By default every include will be prefixed with the __DIR__ constant, so that
        every line looks like:

        include __DIR__ . '/File.php';

        If the php52 option is supplied, the __DIR__ constant is not used, but
        instead every file will be prefixed with dirname(__FILE__).

      -v
        The verbose option will show a lot of debugging output.

TODO
----

- Files with no classes or interfaces are ignored, those need to be added
- Allow checking for files with extensions other than .php

