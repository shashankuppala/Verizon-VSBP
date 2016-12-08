<?php
$linesEn=array();
$EngressStatsAr=array();
//$fpEn=fopen('/var/www/html/Angular/EngressStats.txt', 'r');
$fpEn=fopen('/var/filter/EgressStats.txt', 'r');
$out = array();
while (!feof($fpEn))
{
    $lineEn=fgets($fpEn);
    //process line 
    $lineEn=trim($lineEn);

if($lineEn!="")
{
	$myArrayEn = explode(',', $lineEn);
    
    foreach ($myArrayEn as $i => $val) {
            $out[$i] = isset($out[$i]) ? $out[$i] + $val : $val;
        }
    $linesEn[]=$lineEn;
}
}
fclose($fpEn);
 $outputEn =  array('datay'=>$out                 );
 print_r(error_get_last());
echo json_encode($outputEn,JSON_FORCE_OBJECT);
?>
