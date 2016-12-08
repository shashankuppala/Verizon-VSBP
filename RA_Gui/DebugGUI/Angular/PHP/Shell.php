<?php
    $fileName=$_GET['pcapfile'];
    $protocol=$_GET['protocol'];
    $port=$_GET['port'];
    $output="/var/www/html/DebugGUI/Angular/tsharkFiles/sh7.txt";        
    //$shCmd= "/opt/common/RA/pcapToText.sh ". $fileName. " ". $protocol. " " . $port. " " . $output;
   //$command= "/opt/common/RA/pcapToText.sh ";  
   $shCmd = "/bin/bash /opt/common/RA/pcapToText.sh \"{$fileName}\" \"{$protocol}\" \"{$port}\" \"{$output}\""; //Passing variables to the pcapTotext.sh to the     
    $result = shell_exec($shCmd);
   echo $result;
 ?>
