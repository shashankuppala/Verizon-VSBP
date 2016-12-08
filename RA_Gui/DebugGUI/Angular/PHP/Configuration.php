<?php
/*echo '<pre>';

// Outputs all the result of shellcommand "ls", and returns
// the last output line into $last_line. Stores the return value
// of the shell command in $retval.
$last_line = system('ls', $retval);

// Printing additional info
echo '
</pre>
<hr />Last line of the output: ' . $last_line . '
<hr />Return value: ' . $retval;*/


/*exec("ls  /html", $results);
foreach(array_slice($results,1,count($results)) as $file) {
    echo $file . "\n";
}*/

$output = shell_exec("/bin/bash   ls /controllers");
echo "<pre>$output</pre>";



?>