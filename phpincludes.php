<?php

main($argv);

function main($argv) {

    if (count($argv)<2) {
        showUsage();
        return;
    }

    $directory = $argv[1];
    if (isset($argv[2])) {
        $output = fopen($argv[2],'w');
    } else {
        $output = fopen('php://stdout');
    }

    run($directory, $output);

}

function showUsage() {
    echo "PHPIncludes 0.1 by Evert Pot\n";
    echo "\n";
    echo "Usage: phpincludes directory [outputfile]";
    echo "\n";
    echo "Outputfile is the file PHPIncludes writes to. If it's not specified, it will\n";
    echo "be sent to STDOUT\n"; 
    echo "Directory is the directory to be scanned for php files.\n";
}

function run($directory, $output) {

    $err = fopen('php://stderr','w');

    $files = findFiles($directory);
    fwrite($err,"Found " . count($files) . " php files\n");
    fwrite($err,"Parsing files\n");
    $result = findClasses($files);
    fwrite($err,"Calculating dependencies\n");

    $result = sortClasses($result);

    printResult($result, $output);

}

function findFiles($directory) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    while($it->valid()) {

        if (substr($it->getFileName(),-4)==='.php') {
            $files[] = $it->getPathName();
        }
        $it->next();

    }

    return $files;
}

function findClasses($files) {

    $classes = array();
    foreach($files as $file) {

        $tokens = token_get_all(file_get_contents($file));
        $index = 0;

        $lastClass = null;

        while($index<count($tokens)) {

            $token = $tokens[$index];

            // Classes and interfaces
            if ($token[0] === T_CLASS || $token[0]===T_INTERFACE) {
                while($tokens[$index][0] !== T_STRING) {
                    $index++;
                }
                $className = $tokens[$index][1];
                $classes[$className] = array('filename' => $file, 'dependencies' => array());
                $lastClass = $className;
            }

            // Extends, implements
            if ($tokens[$index][0] === T_EXTENDS || $tokens[$index][0] === T_IMPLEMENTS) {
                while($tokens[$index] !== '{') {
                    $index++;
                    if ($tokens[$index][0] === T_STRING) {
                        $classes[$lastClass]['dependencies'][] = $tokens[$index][1];
                    }
                }
            }
            $index++;

        }

    }
    return $classes;

}

/**
 * This function sorts classes based on their
 * dependencies.
 *
 * It does multiple loops through the classes list until every class
 * is in the sorted list.
 * usort didn't really work for this. Is there a more efficient way?
 *
 * The result is a list of filenames.
 *
 * @param string $classes
 * @return array 
 */
function sortClasses($classes) {

    $result = array();

    // We need a copy
    $fullClassList = $classes;

    $fileNames = array();

    while(count($classes) > 0) {

        foreach($classes as $class=>$info) {

            foreach($info['dependencies'] as $dep) {

                if (!isset($fullClassList[$dep])) {
                   // This dependency does not show up in the classlist at all, 
                   // so we can safely skip it.
                   continue;
                }
                if (!isset($result[$dep])) {
                    // This class is not in the resultset yet, but it will be, 
                    // so we'll skip it for now.
                    continue 2;
                }
            }

            // All dependencies have been met.
            // Adding it to the result, removing it from the source.
            $result[$class] = $info;
            $fileNames[] = $info['filename'];
            unset($classes[$class]);

        }


    }

    return $fileNames;

}

function printResult($result, $output) {

    fwrite($output, "<?php\n\n");
    foreach($result as $filename) {

        fwrite($output,"include '" . $filename . "';\n");

    }

}

?>
