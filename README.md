PHP includes v0.1
=================

This is a simple script that traverses your sourcecode and attempts and create
an 'includes' file.

Using an includes file instead of for example autoload can have significant
performance benefits.

Usage
-----

- Download PHPIncludes
- Run with "php phpincludes.php [directory] [outputfile]"
 
If the outputfile is not supplied, output is sent to STDOUT.

If the output file already exists, it will attempt to update the existing
includes file. It does so by looking at two markers in the file:

```
// Begin includes\n

and

// End includes\n
```

Every before '// Begin includes\n' will be retained, as well as everything after
'// End includes\n'. Everything in between will be overwritten. The \n is a
unix newline.

TODO
----

- Files with no classes or interfaces are ignored, those need to be added
- Allow checking for files with extensions other than .php
- Split up into classes
- Create a pear package


