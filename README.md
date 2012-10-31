PHP includes v0.1.0
===================

This is a simple script that traverses your sourcecode and attempts and create
an 'includes' file.

Using an includes file instead of for example autoload can have significant
performance benefits.

Installation
------------

```
pear config-set auto_discover 1
pear install pear.sabredav.org/phpincludes-alpha 
```

Usage
-----

    phpincludes [--php52] <directory> [outputfile]

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

TODO
----

- Files with no classes or interfaces are ignored, those need to be added
- Allow checking for files with extensions other than .php
- Split up into classes
- Namespace support


