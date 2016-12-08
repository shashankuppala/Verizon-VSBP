<?php
    $linesEnB=array();
    //$fpEnB=fopen('/var/www/html/Angular/EngressStatsACR.txt', 'r');
    $fpEnB=fopen('/var/filter/EgressStatsACR.txt', 'r');
    $out=array();
    while (!feof($fpEnB))
{
    $lineEnB=fgets($fpEnB);
    //process line however you like
    $lineEnB=trim($lineEnB);
//print_r($myArray);
    //add to array
if($lineEnB!="")
{
    $myArrayEnB = explode(',', $lineEnB);
    
    foreach ($myArrayEnB as $i => $val) {
            $out[$i] = isset($out[$i]) ? $out[$i] + $val : $val;
        }
    $linesEnB[]=$lineEnB;
}
}
fclose($fpEnB);
 $outputEnB =  array('data'=>$out                 );
 print_r(error_get_last());
echo json_encode($outputEnB,JSON_FORCE_OBJECT);
?>
