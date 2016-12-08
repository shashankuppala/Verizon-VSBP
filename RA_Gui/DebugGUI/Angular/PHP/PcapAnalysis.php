<?php
if(isset($_GET['action']) && !empty($_GET['action'])) {
	
    $action = $_GET['action'];
    switch($action) {
        case 'ingresscapfiles' : GetIngressCapFiles();break;
        case 'egresscapfiles' : GetEgressCapFiles();break;
        // ...etc...
    }
}
function GetIngressCapFiles(){
	//print_r($_GET['action']);
$dir    = '/var/filter/Ingress';
$ingressfiles = scandir($dir);
$ingressfiles = array_slice($ingressfiles, 3);
echo json_encode($ingressfiles,JSON_FORCE_OBJECT);
}

function GetEgressCapFiles(){
	//print_r($_GET['action']);
$dir    = '/var/filter/Egress';
$egressfiles = scandir($dir);
$egressfiles= array_slice($egressfiles, 3);
echo json_encode($egressfiles,JSON_FORCE_OBJECT);
}
?>
