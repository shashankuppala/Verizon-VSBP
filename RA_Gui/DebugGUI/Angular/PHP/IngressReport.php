<?php
$linesIn=array();
$IngressStatsAr=array();
//$fpIn=fopen('/var/www/html/Angular/IngressStats.txt', 'r');
$fpIn=fopen('/var/filter/IngressStats.txt', 'r');
$out = array();
while (!feof($fpIn))
{
    $lineIn=fgets($fpIn);
    //process line however you like
    $lineIn=trim($lineIn);
//print_r($myArray);
    //add to array
if($lineIn!="")
{
	$myArrayIn = explode(',', $lineIn);
    
    foreach ($myArrayIn as $i => $val) {
            $out[$i] = isset($out[$i]) ? $out[$i] + $val : $val;
        }
    $linesIn[]=$lineIn;
}
}
fclose($fpIn);
 $outputIn =  array('datax'=>$out                 );
 print_r(error_get_last());
echo json_encode($outputIn,JSON_FORCE_OBJECT);
?>
