	<?php
	$linesInA=array();
	//$fpInA=fopen('/var/www/html/Angular/IngressStatsACR.txt', 'r');
	$fpInA=fopen('/var/filter/IngressStatsACR.txt', 'r');
    $out=array();
	while (!feof($fpInA))
{
    $lineInA=fgets($fpInA);
    //process line however you like
    $lineInA=trim($lineInA);
//print_r($myArray);
    //add to array
if($lineInA!="")
{
	$myArrayInA = explode(',', $lineInA);
    
    foreach ($myArrayInA as $i => $val) {
            $out[$i] = isset($out[$i]) ? $out[$i] + $val : $val;
        }
    $linesInA[]=$lineInA;
}
}
fclose($fpInA);
 $outputInA =  array('dataA'=>$out                );
 print_r(error_get_last());
echo json_encode($outputInA,JSON_FORCE_OBJECT);
?>