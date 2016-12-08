<?php
include_once('Utils.php');
include_once('Diff.php');
require_once dirname(__FILE__).'/Diff/Renderer/Html/SideBySide.php';
class recordHandler extends Utils{
    protected $url = "http://localhost:9200";
    protected $size = 1000;
    protected $from = "-1 day";
    protected $to = "now";
    protected $fields = "_index,frame,srcport,dstport,Session-Id";
    protected $randomDir;
    protected $from1= "-1 day";
    protected $to1= "now";
    protected $from2= "-1 day";
    protected $to2= "now";

    function __construct() {
        parent::__construct();
        if ($this->xmlConfig->host && $this->xmlConfig->port){
            $this->url = "http://" . $this->xmlConfig->host . ":" . $this->xmlConfig->port;
        }
        if ($this->xmlConfig->size){
            $this->size = $this->xmlConfig->size;
        }
        if ($this->xmlConfig->from){
            $this->from = $this->xmlConfig->from;
        }
        if ($this->xmlConfig->sessionDetail->fields){
            $this->fields = $this->xmlConfig->sessionDetail->fields;
        }        
        session_start();
        if( isset( $_SESSION['randdir'] ) )
        {
           $this->randomDir = $_SESSION['randdir'];
        }
        else
        {
           #$this->randomDir = mt_rand() . "/";
           $this->randomDir = mt_rand();
           $_SESSION['randdir'] = $this->randomDir;
        }
    }

    public function showStats($postParams){
        $scriptDir = $_SERVER['DOCUMENT_ROOT'] . "/RA_Gui/scripts/";
        $from1 = (isset($postParams['from1']))?$postParams['from1']:strtotime($this->from1);
        $to1 = (isset($postParams['to1']))?$postParams['to1']:strtotime($this->to1);
        $from2 = (isset($postParams['from2']))?$postParams['from2']:strtotime($this->from2);
        $to2 = (isset($postParams['to2']))?$postParams['to2']:strtotime($this->to2);
        $tmpDir = "/opt/common/RA/tmp_stats/" . $this->randomDir;
        $tab="DB";
        $cmd = "/bin/bash {$scriptDir}showStats.sh \"{$_SERVER['PHP_AUTH_USER']}\" \"{$_SERVER['PHP_AUTH_PW']}\" \"{$from1}\" \"{$to1}\" \"{$from2}\" \"{$to2}\"  \"{$this->randomDir}\" \"{$tab}\"";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showStats: " . $cmd . "\n", FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        $statsEnd = $tmpDir . "/stats_done.txt";
        $statsFile = $tmpDir . "/TimeFilteredStats.txt";
        $loopCount = 1;
        $error = "";
        while(1){
            sleep(2);
            if (file_exists($statsEnd)) {
                break;
            }
            $loopCount++;
            if ($loopCount > 60) {
                $error = "Command did not terminate in 2 minutes: $cmd";
                break;
            }
        }
        if($error != ""){
            $result["status"] = "error";
            $result["message"] = $error;            
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showStats Output: " . $error . "\n", FILE_APPEND | LOCK_EX);
        } else {
            $cmd = "cat $statsFile";
            $stream = shell_exec($cmd);
            $result["status"] = "success";
            $lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
            $result["record"] = implode("\n",$lines);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showStats Output:\n" . implode("\n",$lines) . "\n", FILE_APPEND | LOCK_EX);
        }
        return json_encode($result);
    }
    

    public function pcapStats($postParams){
        $scriptDir = $_SERVER['DOCUMENT_ROOT'] . "/RA_Gui/scripts/";
        $from1 = (isset($postParams['from1']))?$postParams['from1']:strtotime($this->from1);
        $to1 = (isset($postParams['to1']))?$postParams['to1']:strtotime($this->to1);
        $from2 = (isset($postParams['from2']))?$postParams['from2']:strtotime($this->from2);
        $to2 = (isset($postParams['to2']))?$postParams['to2']:strtotime($this->to2);
        $tmpDir = "/opt/common/RA/tmp_stats/" . $this->randomDir;
        $tab="PA";
        //$filter:(isset($postParams['filter']))?$postParams['filter']:"Diameter";
        $cmdcode = (isset($postParams['cmdcode']))?$postParams['cmdcode']:"0";
        $ingressport = (isset($postParams['ingressport']))?$postParams['ingressport']:"3998";
        $egressport = (isset($postParams['egressport']))?$postParams['egressport']:"3868";
        $sessionid = (isset($postParams['sessionid']))?$postParams['sessionid']:"";
        $cmd = "/bin/bash {$scriptDir}showPcap.sh \"{$_SERVER['PHP_AUTH_USER']}\" \"{$_SERVER['PHP_AUTH_PW']}\" \"{$from1}\" \"{$to1}\" \"{$from2}\" \"{$to2}\"  \"{$this->randomDir}\" \"{$tab}\" \"{$sessionid}\" \"{$cmdcode}\" \"{$ingressport}\" \"{$egressport}\" ";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "pcapStats: " . $cmd . "\n", FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        $statsEnd = $tmpDir . "/stats_done.txt";
        $pcapFile1 = $tmpDir . "/IngressPDAnalysis.txt";
        $pcapFile2 = $tmpDir . "/EgressPDAnalysis.txt";
        $loopCount = 1;
        $error = "";
        while(1){
            sleep(2);
            if (file_exists($statsEnd)) {
                break;
            }
            $loopCount++;
            if ($loopCount > 60) {
                $error = "Command did not terminate in 2 minutes: $cmd";
                break;
            }
        }
        if($error != ""){
            $result["status"] = "error";
            $result["message"] = $error;            
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "pcapStats Output: " . $error . "\n", FILE_APPEND | LOCK_EX);
        } else {
            $result["status"] = "success";
            $cmd = "cat $pcapFile1";
            $stream = shell_exec($cmd);
            $lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
            $result["record1"] = implode("\n",$lines);
            $cmd = "cat $pcapFile2";
            $stream = shell_exec($cmd);
            $lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
            $result["record2"] = implode("\n",$lines);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "pcapStats Output:\n" . implode("\n",$lines) . "\n", FILE_APPEND | LOCK_EX);
        }
        return json_encode($result);
    }


    public function showSessions($postParams, $userFlag){
        $pattern = (isset($postParams['filter']))?$postParams['filter']:"";
        $pattern = ($pattern != "")?".*".$pattern.".*":".*";
        //$field = ($userFlag == 1)?"User-Name":"Session-Id";
        if ($userFlag == 0) {
            $field = "Session-Id";
        } elseif ($userFlag == 1) {
            $field = "User-Name";
        } else {
            $field = "Subscription-Id-Data";
        }
        #$from = (isset($postParams['from']))?$postParams['from']:strtotime($this->from);
        $from = (isset($postParams['from']))?strtotime($postParams['from']):strtotime($this->from);
        //$from = "now-" . $from;
        #$to = (isset($postParams['to']))?$postParams['to']:strtotime($this->to);
        $to = (isset($postParams['to']))?strtotime($postParams['to']):strtotime($this->to);
        //$to = (isset($postParams['to']))?$postParams['to']:"now";
        //$to = time();
        $query = (isset($postParams['query']) && ($postParams['query'] != ''))?$postParams['query']:$field.":{$pattern}";
        $q = str_replace(":",":\"", $query);
        $q .= "\"";
        $r = array();
        
        if(isset($postParams['query']) && ($postParams['query'] != '')){
        	//$splitdata=explode(":", $q);
        	//$q="\"".$splitdata[0]."\" : ".$splitdata[1];
            $q_temp = "{\"" . $q . "}";
        	//$q .=$q_temp;
            $q = str_replace(":", "\": ", $q_temp);
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessions set $userFlag Query: " . $q . " Fields: " . $field . "\n", FILE_APPEND | LOCK_EX);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessions Original time FROM: " . $postParams['from'] . " TO: " . $postParams['to'] . "\n", FILE_APPEND | LOCK_EX);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessions time FROM: " . date('r', $from ) . " TO: " . date('r', $to) . "\n", FILE_APPEND | LOCK_EX);
        $cmd = <<< EOF
curl -XGET '{$this->url}/_all/_search?pretty' -d '{
    "fields": ["{$field}"],
    "query": {
        "filtered": {
            "query": {
                "match": {$q}
            },
            "filter": {
                "range": {
                    "time": {
                        "from": "{$from}",
                        "to": "{$to}"
                    }
                }            
            }
        }
    },    
    "size": {$this->size},
    "sort": [
        {
          "time": {
            "order": "desc",
            "ignore_unmapped": true
          }         
        }
    ]    
}'

EOF;
        }else{
        $q = str_replace(":","\": \"", $query);
        //print_r($q);
        //print_r("Final Result else");
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessions $userFlag Query: " . $q . "\n", FILE_APPEND | LOCK_EX);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessions Original time FROM: " . $postParams['from'] . " TO: " . $postParams['to'] . "\n", FILE_APPEND | LOCK_EX);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessions time FROM: " . date('r', $from ) . " TO: " . date('r', $to) . "\n", FILE_APPEND | LOCK_EX);
        $cmd = <<< EOF
curl -XGET '{$this->url}/_all/_search?pretty' -d '{
    "fields": ["{$field}"],
    "query": {
        "filtered": {
            "query": {
                "match_all": {}
            },
            "filter": {
                "bool" : {
                    "must" : [
                        {
                            "regexp" : {
                                "{$q}"
                            }
                        },
                        {
                            "range": {
                                "time": {
                                    "from": "{$from}",
                                    "to": "{$to}"
                                }
                            }
                        }
                    ]
                }            
            }
        }
    },    
    "size": {$this->size},
    "sort": [
        {
          "time": {
            "order": "desc",
            "ignore_unmapped": true
          }         
        }
    ]    
}'

EOF;
        }
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Sessions $userFlag Query $query\n" . $cmd . "\n", FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Command executed\n", FILE_APPEND | LOCK_EX);
        $stream = json_decode($stream, true);
        $records = json_decode(json_encode($stream{"hits"}{"hits"}), true);
        $lines = array();
        foreach($records as $rec){
            foreach($rec{"fields"} as $k => $v){
                if(preg_match("/{$field}/i",$k)){
                    $val = preg_replace("/^['\"]/","",$v[0]);
                    $val = preg_replace("/['\"]$/","",$val);
                    array_push($lines,$val);
                }                
            }
        }
        $lines = array_unique($lines);
#GNDEBUG
if ($userFlag == 0) {
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Unique Sessions\n" . implode("\n",$lines) . "\n", FILE_APPEND | LOCK_EX);
} else {
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Unique Users\n" . implode("\n",$lines) . "\n", FILE_APPEND | LOCK_EX);
}
        $numSess = 0;
        foreach($lines as $line){
            $record = array();
            $record['id'] = $line;     
            $record['title'] = $line;
            $record['folder'] = false;
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $record, FILE_APPEND | LOCK_EX);
            array_push($r,$record);
        }
        if (!function_exists('naturalSort')) {
            function naturalSort($a, $b) {
                $sortFields = array("title");
                for($i = 0; $i < count($sortFields); $i++){
                    $fieldname = $sortFields[$i];
                    if(($j=strnatcasecmp(preg_replace('/\~\d+$/',"",$a["{$fieldname}"]) , preg_replace('/\~\d+$/',"",$b["{$fieldname}"]))) == 0)
                        continue;
                    else
                        break;
                }
                return strnatcasecmp(preg_replace('/\~\d+$/',"",$a["{$fieldname}"]) , preg_replace('/\~\d+$/',"",$b["{$fieldname}"]));
            }
        }        
        #usort($r, "naturalSort");
#GNDEBUG
if ($userFlag == 0) {
#foreach($r as $rec){
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Sessions Output:\n" . implode(", ",$rec) . "\n", FILE_APPEND | LOCK_EX);
#}
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Sessions Output:\n" . json_encode($r) . "\n", FILE_APPEND | LOCK_EX);
}
        return json_encode($r);
    }
    
    public function showSessionDetails($postParams){
        $sessionID = (isset($postParams['sessionId']))?$postParams['sessionId']:"";
        $filter = (isset($postParams['filter']))?$this->xmlConfig->$postParams['filter']:"_all";
        $fields = explode(",",$this->fields);
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessionDetails filter: " . $postParams['filter'] . " fields: " . $this->fields . "\n", FILE_APPEND | LOCK_EX);
        $ckboxId = array("_type","pcap","frame");
        $field = implode("\",\"",array_merge($ckboxId,$fields));
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessionDetails field: " . $field . "\n", FILE_APPEND | LOCK_EX);

        $q = (isset($postParams['query']) && ($postParams['query'] != ''))?$postParams['query']:"";
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessionDetails: " . $filter . " q: " . $q . "\n", FILE_APPEND | LOCK_EX);
        # GN 04/30/2015: Made change as session Id may contain the colon (:) character
        # Original Code: $q = str_replace(":",":\\\"", $q);
        $q = str_replace("Id:","Id:\\\"", $q);
        $q .= "\\\"";
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Query: " . $q . "\n", FILE_APPEND | LOCK_EX);
            $cmd = <<< EOF
curl -XGET "{$this->url}/{$filter}/_search?q=+{$q}" -d '{
    "fields": ["{$field}"],
    "query": {
        "filtered": {
            "query": {
                "match_all" : {}
            }
        }
    },
    "size": {$this->size},
    "sort": [
        {
          "time": {
            "order": "asc"
          },        
          "Accounting-Record-Number": {
            "order": "asc"
          },          
          "is_request": {
            "order": "desc"
          }          
        }
    ]    
}
'

EOF;
/**/
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "SessionDetails\n" . $cmd . "\n", FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        $stream = json_decode($stream, true);
        $records = json_decode(json_encode($stream{"hits"}{"hits"}), true);
        $lines = array();
        $mappings = array();
        $inBytes = 0;
        $outBytes = 0;
        foreach($fields as $f){
            if($this->xmlConfig->valueMapping->$f){
                foreach($this->xmlConfig->valueMapping->$f as $m){
                    $mappings[$f."_".$m->key] = $m->value;

                }
            }               
        }
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessionDetails Records:\n" . print_r($records,true) . "\n", FILE_APPEND | LOCK_EX);
#if ($sessionID != "") {
#    $resultStr = array();
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Calling findDiff\n" . $line . "\n", FILE_APPEND | LOCK_EX);
#    $postParams['sessionID'] = $sessionID;
#    $resultStr = $this->findDiff($postParams);
#}
        //foreach($records as $rec){
        while(sizeof($records) > 0){
            $rec = array_shift($records);
            $line = array_flip($fields);
            $id = array_flip($ckboxId);
            $flag3G = false;
            $aca = array();
            foreach($fields as $f){
                $val = str_replace("\"","",$rec{"{$f}"});
                $indx= $f . "_" . str_replace("'","",$val);
                if(array_key_exists($indx, $mappings)){
                    $val = $mappings[$indx];
                }
                $line[$f] = str_replace("'","",$val);                
            }
            if($rec{"_type"}){
                $type = $rec{"_type"};
                if(preg_match("/({$this->xmlConfig->type3G})[1,4]/",$type)){
                    $flag3G = true;
                }
                $id["_type"] = preg_replace("/[\"']/","",$rec{"_type"});
            }
            $recFields = ($rec{"fields"})?$rec{"fields"}:$rec{"_source"};
            foreach($recFields as $k => $v){
                $val = preg_replace("/[\"']/","",$v[0]);
                if($k == "time"){
                    $timestamp = floor($val);
                    $milliseconds = round(($val - $timestamp) * 1000000);
                    #$val = gmdate(preg_replace('`(?<!\\\\)u`', $milliseconds, "M d Y H:i:s.u"), $timestamp);
                    $val = date(preg_replace('`(?<!\\\\)u`', $milliseconds, "M d Y H:i:s.u"), $timestamp);
                }
                if(preg_match("/{$k}/i",$field)){
                    if(preg_match("/(pcap|frame)/i",$k)){
                        if($k == "pcap"){
                            $id[$k] = basename(str_replace("'","",$val),".pcap");
                        }else{
                            $id[$k] = str_replace("'","",$val);
                        }
                    }else{                    
                        $indx = $k . "_" . str_replace("'","",$val);
                        if(array_key_exists($indx, $mappings)){
                            $val = $mappings[$indx];
                        }
                        if($flag3G){
                            if(preg_match("/{$k}/",$this->xmlConfig->unique3G)){
                                $aca[$k] = preg_replace("/[\"']/","",$val);
                            }
                        }
                        if($flag3G && ($k == "Accounting-Record-Number")) $k = "Accounting-Record-Type";                        
                        $line[$k] = preg_replace("/[\"']/","",$val);
                    }
                }
                #if($k == "Accounting-Input-Octets"){
                #    $inBytes += $val;
                #}
                #if($k == "Accounting-Output-Octets"){
                #    $outBytes += $val;
                #}
            }
            if(sizeof($aca) > 0){
                $recACA = $this->getACA($filter,$field,$aca);
                //$records = array_merge($records,$recACA);
                if(sizeof($recACA) > 0){
                    array_push($records,$recACA[0]);
                }
            }          
            if(sizeof($line) > 0){
        $id["_type"] = ($line["type"])?$line["type"]:$id["_type"];
                $line["id"] = $id["_type"] . "_" . $id["pcap"] . "-" . $id["frame"] . ".txt";
                array_push($lines,$line);
            }
        }
        if(sizeof($lines) == 0){
            $line = array_flip($fields);
            $line = array_fill_keys(array_keys($line), null);
            $result = '{
                    "draw": 1,
                    "recordsTotal": '.sizeof($lines).',
                    "recordsFiltered": '.sizeof($lines).',
                    "data": '. json_encode($line) . '}';
        }else{
            //$line = "Totals: Input Bytes = 0 Output Bytes = 0";
            //$line = array_flip($fields);
            //$line = array_fill_keys(array_keys($line), null);
            //$line["time"] = "Total Bytes";
            //$line["Accounting-Input-Octets"] = $inBytes;
            //$line["Accounting-Output-Octets"] = $outBytes;
            //array_push($lines,$line);
            $result = '{
                    "draw": 1,
                    "recordsTotal": '.sizeof($lines).',
                    "recordsFiltered": '.sizeof($lines).',
                    "data": '. json_encode($lines) . '}';            
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessionDetails lines:\n" . json_encode($lines) . "\n", FILE_APPEND | LOCK_EX);
        }
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showSessionDetails result:\n" . json_encode(json_decode($result)) . "\n", FILE_APPEND | LOCK_EX);
        return json_encode(json_decode($result));
    }

    public function showDiff($postParams){
    $sessionID = (isset($postParams['sessionID']))?$postParams['sessionID']:"";
        $tmpDir = ($this->xmlConfig->tmpDir)?$this->xmlConfig->tmpDir:$_SERVER['DOCUMENT_ROOT'] . "tmp/";
        $tmpDir = $tmpDir . $this->randomDir . "/";
        #$scriptDir = $_SERVER['DOCUMENT_ROOT'] . "/RA_Gui/scripts/";
    $diff1 = ($this->xmlConfig->diffFile1)?$this->xmlConfig->diffFile1:"diff1.txt";
        $diff2 = ($this->xmlConfig->diffFile2)?$this->xmlConfig->diffFile2:"diff2.txt";
        $sessEnd = ($this->xmlConfig->sessProcEnd)?$this->xmlConfig->sessProcEnd:"diff.txt";
        $sessEnd = $tmpDir . $sessEnd;
        $ingressFiles = (isset($postParams['ingressFiles']))?$postParams['ingressFiles']:"";
        $egressFiles = (isset($postParams['egressFiles']))?$postParams['egressFiles']:"";
        $error = "";
        $size = 4096;
    $result = array();
        #GNTEST: comment line below
    #$cmd = "/bin/bash {$scriptDir}showSessions.sh \"{$sessionID}\" \"{$_SERVER['PHP_AUTH_USER']}\" \"{$_SERVER['PHP_AUTH_PW']}\"";
        //Open file
    if($sessionID != ""){
            #GNTEST: comment line below
        #$stream = shell_exec($cmd);
            #$loopCount = 1;
            #while(1){
            #    sleep(2);
            #    if (file_exists($sessEnd)) {
            #        break;
            #    }
            #    $loopCount++;
            #    if ($loopCount > 120) {
            #        $error = "Command did not terminate in 4 minutes: $cmd";
            #        break;
            #    }
            #}
            if(($ingressFiles == "") && ($egressFiles == "")){
                $diffFiles1 = $this->getAllRecords($tmpDir,"(vseprf|pgw|pdsn)");
                $diffFiles2 = $this->getAllRecords($tmpDir,"(ccf|aaa)"); 
            }else{
                $diffFiles1 = split(",",$ingressFiles);
                $diffFiles2 = split(",",$egressFiles);                
                $diffFiles1 = array_filter($diffFiles1, 'strlen');
                $diffFiles2 = array_filter($diffFiles2, 'strlen');
#GNDEBUG
$gnBuf = "showDiff: split IN: " . implode(", ",$diffFiles1) . " OUT: " . implode(", ",$diffFiles2) . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
$gnBuf = "showDiff: split SZ_IN: " . sizeof($diffFiles1) . " SZ_OUT: " . sizeof($diffFiles2) . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
            }
            $numInFiles = sizeof($diffFiles1);
            $numOutFiles = sizeof($diffFiles2);
            if (($numInFiles == 0) && ($numOutFiles == 0)) {
#GNDEBUG
$gnBuf = "showDiff: NumAllFiles: zero\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                $error = "Ingress and Egress Records is 0";
                //return json_encode($result);
                $numInFiles = 0;
            } else {
                if (($numInFiles != 0) && ($numOutFiles != 0)) {
                    if ($numInFiles != $numOutFiles) {
#GNDEBUG
$gnBuf = "showDiff: NumAllFiles: in = " . $numInFiles . " out = " . $numOutFiles . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                        $error = "Num Records mismatch, Ingress = " . $numInFiles . " Egress = " . $numOutFiles;
                        //return json_encode($result);
                        $numInFiles = 0;
                    }
                } else {
                    $numInFiles = (sizeof($diffFiles2) > sizeof($diffFiles1))?sizeof($diffFiles2):sizeof($diffFiles1);
                }
            }
            $diffHtml = "";
            $tabHtml = "<ul class='nav nav-tabs'>";
            #$numFiles = (sizeof($diffFiles2) > sizeof($diffFiles1))?sizeof($diffFiles2):sizeof($diffFiles1);
            $numFiles = $numInFiles;
            $diffHtml = "";
            $tabHtml = "<ul class='nav nav-tabs'>";
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showDiff1: $diffFiles1 $diffFiles2\n", FILE_APPEND | LOCK_EX);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "showDiff2: $numFiles\n" . $tmpDir . "\n", FILE_APPEND | LOCK_EX);
            for($i=0; $i<$numFiles; $i++){
                $file1content = array();
                $file2content = array();                
                if($diffFiles1[$i]){
                    $diff1 = $tmpDir . basename($diffFiles1[$i]);
#GNDEBUG
$gnBuf = "File Name1: " . $diff1 . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                    if (file_exists($diff1))
                    {
                        if(preg_match("/({$this->xmlConfig->type3G})/",basename($diff1))){
                            $file1 = file_get_contents($diff1);
                        }else{
                            #GNTEST
                            $file1 = $this->filterUnwantedSession($postParams,$diff1);
                            #$file1 = $this->filterSession($postParams,$diff1);
                        }
                        $file1 = "Filename: " . basename($diff1) . "\n" . $file1;
                        $file1content = preg_split("/[\r\n]/",$file1,-1,PREG_SPLIT_NO_EMPTY);
                    }else{
                        $error = "File does not exist : $diff1";
                    }
                }
                if($diffFiles2[$i]){
                    $diff2 = $tmpDir . basename($diffFiles2[$i]);
#GNDEBUG
$gnBuf = "File Name2: " . $diff2 . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                    if (file_exists($diff2))
                    {
                        if(preg_match("/({$this->xmlConfig->type3G})/",basename($diff2))){
                            $file2 = file_get_contents($diff2);
                        }else{
                            #GNTEST
                            $file2 = $this->filterUnwantedSession($postParams,$diff2);
                            #$file2 = $this->filterSession($postParams,$diff2);
                        }
                        $file2 = "Filename: " . basename($diff2) . "\n" . $file2;
                        $file2content = preg_split("/[\r\n]/",$file2,-1,PREG_SPLIT_NO_EMPTY);        
                    }else{
                        $error = "File does not exist : $diff2";
                    }
                }
                if((sizeof($file1content) > 0) && (sizeof($file2content) > 0)){
                    $this->matchLineOrder($file1content,$file2content);
                }
                // Options for generating the diff
                $options = array(
                    'context' => 999999,
                    'ignoreNewLines' => true,
                    'ignoreWhitespace' => true,
                    'ignoreCase' => true,
                );
                
                // Initialize the diff class
                $diff = new Diff($file1content, $file2content, $options);
                
                // Generate a side by side diff
                
                $renderer = new Diff_Renderer_Html_SideBySide;
                $active = ($i == 0)?"active":"";
                $tabHtml .= "<li class='$active'><a href='#tabs-" . intval($i+1) . "' data-toggle='tab'>Record " . intval($i+1) . "</a></li>";
                $diffHtml .= "<div class='tab-pane $active' id='tabs-" . intval($i+1) . "'>";
                $diffHtml .= $diff->render($renderer);
                $diffHtml .= "</div>";
            }
            $tabHtml .= "</ul>";
            if($error == ""){
                $result["status"] = "success";
                $result["record"] = $tabHtml . "<div class='tab-content'>" . $diffHtml . "</div>";
            }else{
                $result["status"] = "error";
                $result["message"] = $error;            
            }
    }
        return json_encode($result);
    }

    public function findDiff($postParams){
        $sessionID = (isset($postParams['sessionId']))?$postParams['sessionId']:"";
        $q = (isset($postParams['query']) && ($postParams['query'] != ''))?$postParams['query']:"";
        $sessionID = str_replace("Session-Id:","", $q);
        $tmpDir = ($this->xmlConfig->tmpDir)?$this->xmlConfig->tmpDir:$_SERVER['DOCUMENT_ROOT'] . "tmp/";
        $tmpDir = $tmpDir . $this->randomDir . "/";
        $scriptDir = $_SERVER['DOCUMENT_ROOT'] . "/RA_Gui/scripts/";
        $diff1 = ($this->xmlConfig->diffFile1)?$this->xmlConfig->diffFile1:"diff1.txt";
        $diff2 = ($this->xmlConfig->diffFile2)?$this->xmlConfig->diffFile2:"diff2.txt";
        $sessEnd = ($this->xmlConfig->sessProcEnd)?$this->xmlConfig->sessProcEnd:"diff.txt";
        $sessEnd = $tmpDir . $sessEnd;
        $ingressFiles = (isset($postParams['ingressFiles']))?$postParams['ingressFiles']:"";
        $egressFiles = (isset($postParams['egressFiles']))?$postParams['egressFiles']:"";
        $error = "";
        $size = 4096;
        $inlabel = "IN";
        $outlabel = "OUT";
        $result = array();
        #$cmd = "/bin/bash {$scriptDir}showSessions.sh \"{$sessionID}\" \"{$_SERVER['PHP_AUTH_USER']}\" \"{$_SERVER['PHP_AUTH_PW']}\"";
        $cmd = "/bin/bash {$scriptDir}showSessions.sh \"{$sessionID}\" \"{$_SERVER['PHP_AUTH_USER']}\" \"{$_SERVER['PHP_AUTH_PW']}\" \"{$this->randomDir}\"";
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "findDiff\n" . $sessionID . " CMD: " . $cmd . "\n", FILE_APPEND | LOCK_EX);
        if($sessionID == ""){
	    $result["status"] = "error";
	    $result["message"] = "Session ID is NULL";            
	    return json_encode($result);
        }
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Diff\n" . $cmd . "\n", FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
	$loopCount = 1;
	while(1){
	    sleep(2);
	    if (file_exists($sessEnd)) {
		break;
	    }
	    $loopCount++;
	    if ($loopCount > 60) {
		$error = "Command did not terminate in 2 minutes: $cmd";
		break;
	    }
	}
	if($error != ""){
	    $result["status"] = "error";
	    $result["message"] = $error;            
	    return json_encode($result);
	}
	if(($ingressFiles == "") && ($egressFiles == "")){
	    $diffFiles1 = $this->getAllRecords($tmpDir,"(vseprf|pgw|pdsn)");
	    $diffFiles2 = $this->getAllRecords($tmpDir,"(ccf|aaa)"); 
	}else{
	    $diffFiles1 = split(",",$ingressFiles);
	    $diffFiles2 = split(",",$egressFiles);                
	}
        
	$ingressFile = $tmpDir . "ingressRecords.txt";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "ingressFile: " . $ingressFile . "\n", FILE_APPEND | LOCK_EX);
	$cmd = "cat $ingressFile";
	$stream = shell_exec($cmd);
	#$result["status"] = "success";
	$lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
	#$result["record"] = implode("\n",$lines);
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "ingressFile Output:\n" . implode("\n",$lines) . "\n", FILE_APPEND | LOCK_EX);
	# result array
	$arrIngress = array();
	$arrIngress1 = array();
	$count=0;
	foreach($lines as $line){
	    $pairs = explode(',',$line);
	    # loop through each pair
	    foreach ($pairs as $i) {
		# split into name and value_r
		list($name,$value) = explode('=', $i, 2);
		$value=str_replace("'","",$value);
		if($name=="time"){
		    $timestamp = floor($value);
		    $milliseconds = round(($value - $timestamp) * 1000000);
		    #$value = gmdate(preg_replace('`(?<!\\\\)u`', $milliseconds, "M d Y H:i:s.u"), $timestamp);      
		    $value = date(preg_replace('`(?<!\\\\)u`', $milliseconds, "M d Y H:i:s.u"), $timestamp);      
		}
		if($name=="is_request"){
		    if ($value==0) {
			$recType = array("INVALID","INVALID","ACA_START","ACA_INTERIM","ACA_STOP");
		    } else {
			$recType = array("INVALID","INVALID","ACR_START","ACR_INTERIM","ACR_STOP");
		    }
		}
		if($name=="Accounting-Record-Type"){
		    $value=$recType[$value]; 
		}
		#$arrIngress[$count][$name] = $value;
		#$arrIngress[$name] = $value;
		$arrIngress[$count][$name] = $value;                    
	    }
	    $count++;
	}


	$egressFile = $tmpDir . "egressRecords.txt";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "egressFile: " . $egressFile . "\n", FILE_APPEND | LOCK_EX);
	$cmd = "cat $egressFile";
	$stream = shell_exec($cmd);
	#$result["status"] = "success";
	$lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
	#$result["record"] = implode("\n",$lines);
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "egressFile Output:\n" . implode("\n",$lines) . "\n", FILE_APPEND | LOCK_EX);
	# result array
	$arrEgress = array();
	$count=0;
	foreach($lines as $line){
	    $pairs = explode(',',$line);
	    # loop through each pair
	    foreach ($pairs as $i) {
		# split into name and value
		list($name,$value) = explode('=', $i, 2);
		$value=str_replace("'","",$value);
		if($name=="time"){
		    $timestamp = floor($value);
		    $milliseconds = round(($value - $timestamp) * 1000000);
		    #$value = gmdate(preg_replace('`(?<!\\\\)u`', $milliseconds, "M d Y H:i:s.u"), $timestamp);
		    $value = date(preg_replace('`(?<!\\\\)u`', $milliseconds, "M d Y H:i:s.u"), $timestamp);
		}
		if($name=="is_request"){
		    if ($value==0) {
			$recType = array("INVALID","INVALID","ACA_START","ACA_INTERIM","ACA_STOP");
		    } else {
			$recType = array("INVALID","INVALID","ACR_START","ACR_INTERIM","ACR_STOP");
		    }
		}
		if($name=="Accounting-Record-Type"){
		    $value=$recType[$value]; 
		}
		#$arrEgress[$count][$name] = $value;
		#$arrEgress[$name] = $value;
		$arrEgress[$count][$name] = $value;
	    }
	    $count++;
	}

	$result = array("status"=>"success","draw"=>1,"outbuf"=>$outBuf,"recordTotal"=>sizeof($arrIngress),"recordsFiltered"=>sizeof($arrIngress),"data"=>$arrIngress,"drawEg"=>1,"recordTotalEg"=>sizeof($arrEgress),"recordsFilteredEg"=>sizeof($arrEgress),"dataegress"=>$arrEgress);
	
		


file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "findDiff result:\n" . json_encode(json_decode($result)) . "\n", FILE_APPEND | LOCK_EX);

	$numInFiles = sizeof($diffFiles1);
	$numOutFiles = sizeof($diffFiles2);
	if (($numInFiles == 0) && ($numOutFiles == 0)) {
#GNDEBUG
$gnBuf = "findDiff: NumAllFiles: zero\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
$changed=0;
	    $outBuf = "Records: Ingress = " . $numInFiles . " Egress = " . $numOutFiles . " Changed = ".$changed."" . ",Bytes:";
	    /*$result = '{
		"outbuf": '.$outBuf.',
		"ingress": {
		    "draw": 1,
		    "recordsTotal": '.sizeof($arrIngress).',
		    "recordsFiltered": '.sizeof($arrIngress).',
		    "data": '. json_encode($arrIngress) . '
		},
		"egress": {
		    "draw": 1,
		    "recordsTotal": '.sizeof($arrEgress).',
		    "recordsFiltered": '.sizeof($arrEgress).',
		    "data": '. json_encode($arrEgress) . '
		}
	    }';  */
	    
	    $result = array("status"=>"success","draw"=>1,"outbuf"=>$outBuf,"recordTotal"=>sizeof($arrIngress),"recordsFiltered"=>sizeof($arrIngress),"data"=>$arrIngress,"drawEg"=>1,"recordTotalEg"=>sizeof($arrEgress),"recordsFilteredEg"=>sizeof($arrEgress),"dataegress"=>$arrEgress);
		      
	    return json_encode($result);
	}
	if ($numInFiles != 0) {
#GNDEBUG
$gnBuf = "findDiff: First Record: in = " . $diffFiles1[0] . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
	    if (preg_match("/vseprf/",$diffFiles1[0])) {
		$inlabel = "VSEP";
	    } elseif (preg_match("/pgw/",$diffFiles1[0])) {
		$inlabel = "PGW";
	    } else {
		$inlabel = "PDSN";
	    }
	}
	if ($numOutFiles != 0) {
#GNDEBUG
$gnBuf = "findDiff: First Record: out = " . $diffFiles2[0] . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
	    if (preg_match("/ccf/",$diffFiles2[0])) {
		$outlabel = "CCF";
	    } else {
		$outlabel = "AAA";
	    }
	}
	$byteBuf1 = $this->byteCounts($sessionID, $diffFiles1);
	$byteBuf1 = $inlabel . ": " . $byteBuf1;
	$byteBuf2 = $this->byteCounts($sessionID, $diffFiles2);
	$byteBuf2 = $outlabel . ": " . $byteBuf2;
	if ($numInFiles != $numOutFiles) {
#GNDEBUG
$gnBuf = "findDiff: NumAllFiles: in = " . $numInFiles . " out = " . $numOutFiles . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
	    if ($numInFiles > $numOutFiles) {
		$changed = " ($inlabel > $outlabel)";
	    } else {
		$changed = " ($outlabel > $inlabel)";
	    }
	    #$outBuf = "Num Records mismatch, Ingress = " . $numInFiles . " Egress = " . $numOutFiles;
	    $outBuf = "$inlabel: Records = " . $numInFiles . "@ $outlabel: Records = " . $numOutFiles ."@Changed:". $changed . "@" . $byteBuf1 . "@" . $byteBuf2;
	    $result = array("status"=>"success","draw"=>1,"outbuf"=>$outBuf,"recordTotal"=>sizeof($arrIngress),"recordsFiltered"=>sizeof($arrIngress),"data"=>$arrIngress,"drawEg"=>1,"recordTotalEg"=>sizeof($arrEgress),"recordsFilteredEg"=>sizeof($arrEgress),"dataegress"=>$arrEgress);
	    return json_encode($result);
	}
	$diffHtml = "";
	$tabHtml = "<ul class='nav nav-tabs'>";
	#$numFiles = (sizeof($diffFiles2) > sizeof($diffFiles1))?sizeof($diffFiles2):sizeof($diffFiles1);
	$numFiles = $numInFiles;
#GNDEBUG
$gnBuf = "findDiff: NumFiles: " . $numFiles . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
	$diffCount = 0;
	$patterns = array("AVP: Origin-Host","AVP: Origin-State-Id","AVP: Destination-Realm","AVP: Destination-Host","AVP: Origin-Realm");
	for($i=0; $i<$numFiles; $i++){
	    $file1content = array();
	    $file2content = array();                
	    if($diffFiles1[$i]){
		$diff1 = $tmpDir . basename($diffFiles1[$i]);
#GNDEBUG
$gnBuf = "findDiff: " . $i . " File Name1: " . $diff1 . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
		if (file_exists($diff1))
		{
		    if(preg_match("/({$this->xmlConfig->type3G})/",basename($diff1))){
			$file1 = file_get_contents($diff1);
		    }else{
			#GNTEST
			#$file1 = $this->filterUnwantedSession($postParams,$diff1);
			#$file1 = $this->filterSession($postParams,$diff1);
			$file1 = $this->filterSession($sessionID,$diff1);
		    }
		    $file1 = "Filename: " . basename($diff1) . "\n" . $file1;
		    $file1content = preg_split("/[\r\n]/",$file1,-1,PREG_SPLIT_NO_EMPTY);
		}else{
		    $error = "File does not exist : $diff1";
		}
	    }
	    if($diffFiles2[$i]){
		$diff2 = $tmpDir . basename($diffFiles2[$i]);
#GNDEBUG
$gnBuf = "findDiff: " . $i . " File Name2: " . $diff1 . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
		if (file_exists($diff2))
		{
		    if(preg_match("/({$this->xmlConfig->type3G})/",basename($diff2))){
			$file2 = file_get_contents($diff2);
		    }else{
			#GNTEST
			#$file2 = $this->filterUnwantedSession($postParams,$diff2);
			#$file2 = $this->filterSession($postParams,$diff2);
			$file2 = $this->filterSession($sessionID,$diff2);
		    }
		    $file2 = "Filename: " . basename($diff2) . "\n" . $file2;
		    $file2content = preg_split("/[\r\n]/",$file2,-1,PREG_SPLIT_NO_EMPTY);        
		}else{
		    $error = "File does not exist : $diff2";
		}
	    }
	    if($error != ""){
		$result["status"] = "error";
		$result["message"] = $error;            
		return json_encode($result);
	    }
	    if((sizeof($file1content) > 0) && (sizeof($file2content) > 0)){
		$this->matchLineOrder($file1content,$file2content);
	    }
	    $r1 = array();
	    $r2 = array();
	    $r1 = array_diff($file1content, $file2content);
	    $r2 = array_diff($file2content, $file1content);
	    $cnt1 = 0;
	    $cnt2 = 0;
	    $nr1 = 0;
	    $nr2 = 0;
	    $sz1 = sizeof($r1) - 1;
	    $sz2 = sizeof($r2) - 1;
	    if ($sz1 != $sz2) {
		foreach($r1 as $val){
		    $index = 0;
		    foreach($patterns as $pat){
			if (preg_match("/".$pat."/",$val)) {
			    $cnt1 += $index;
			    $nr1++;
			    break;
			}
			$index++;
		    }
		}
		foreach($r2 as $val){
		    $index = 0;
		    foreach($patterns as $pat){
			if (preg_match("/".$pat."/",$val)) {
			    $cnt2 += $index;
			    $nr2++;
			    break;
			}
			$index++;
		    }
		}
		#foreach($r2 as $val){
		#    if (preg_match("/(".$patterns[0]."|".$patterns[1]."|".$patterns[2]."|".$patterns[3]."|".$patterns[4].")/",$val)) {
		#        $cnt2++;
		#    }
		#}
		$diffCount++;
	    } else {
		foreach($r1 as $val){
		    $index = 0;
		    foreach($patterns as $pat){
			if (preg_match("/".$pat."/",$val)) {
			    $cnt1 += $index;
			    $nr1++;
			    break;
			}
			$index++;
		    }
		}
		foreach($r2 as $val){
		    $index = 0;
		    foreach($patterns as $pat){
			if (preg_match("/".$pat."/",$val)) {
			    $cnt2 += $index;
			    $nr2++;
			    break;
			}
			$index++;
		    }
		}
		if (($sz1 != $nr1) || ($sz2 != $nr2)) {
		    $diffCount++;
		} else {
		    if ($cnt1 != $cnt2) {
			$diffCount++;
		    }
		}
            }

#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Diffs1: " . $sz1 . " Match " . $nr1 . " Weight " . $cnt1 . "\n" . implode("\n",$r1) . "\n", FILE_APPEND | LOCK_EX);
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Diffs2: " . $sz2 . " Match " . $nr2 . " Weight " . $cnt2 . "\n" . implode("\n",$r2) . "\n", FILE_APPEND | LOCK_EX);
        }
        #$outBuf = "Total Records = " . $numFiles . " Changed = " . $diffCount;
        #$outBuf = "Records: Ingress = " . $numFiles . " Egress = " . $numFiles . " Changed = " . $diffCount;
        $outBuf = "$inlabel: Records = " . $numInFiles . "@ $outlabel: Records = " . $numOutFiles . "@ Changed = " . $diffCount . "@" . $byteBuf1 . "@" . $byteBuf2;
        $result = array("status"=>"success","draw"=>1,"outbuf"=>$outBuf,"recordTotal"=>sizeof($arrIngress),"recordsFiltered"=>sizeof($arrIngress),"data"=>$arrIngress,"drawEg"=>1,"recordTotalEg"=>sizeof($arrEgress),"recordsFilteredEg"=>sizeof($arrEgress),"dataegress"=>$arrEgress);
        
        /*reading .txt files from tmp folder*/
        return json_encode($result);
    }
    
    
    
    
    
    

    function byteCounts($sessionId, $files) {
        $tmpDir = ($this->xmlConfig->tmpDir)?$this->xmlConfig->tmpDir:$_SERVER['DOCUMENT_ROOT'] . "tmp/";
        $tmpDir = $tmpDir . $this->randomDir . "/";

        $numFiles = sizeof($files);

#GNDEBUG
$gnBuf = "byteCounts: " . $sessionId . " num files: " . $numFiles . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);

        $cnt = 0;
        $sdc_cnt = 0;
        $patterns = array("AVP: Accounting-Input-Octets","AVP: Accounting-Output-Octets","AVP: Service-Data-Container");
        $bytes = array(0,0);
        for($i=0; $i<$numFiles; $i++){
            $filecontent = array();
            if($files[$i]){
                $diff = $tmpDir . basename($files[$i]);
#GNDEBUG
#$gnBuf = "byteCounts: " . $i . " File Name1: " . $diff . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                if (file_exists($diff))
                {
                    if(preg_match("/({$this->xmlConfig->type3G})/",basename($diff))){
                        $file = file_get_contents($diff);
                    }else{
                        #GNTEST
                        #$file = $this->filterUnwantedSession($postParams,$diff);
                        #$file = $this->filterSession($postParams,$diff);
                        $file = $this->filterSession($sessionId,$diff);
                    }
                    $file = "Filename: " . basename($diff) . "\n" . $file;
                    $filecontent = preg_split("/[\r\n]/",$file,-1,PREG_SPLIT_NO_EMPTY);
                }else{
                    $error = "File does not exist : $diff";
                }
            }
            if(sizeof($filecontent) > 0){
#GNDEBUG
#$gnBuf = "byteCounts: size = " . sizeof($filecontent) . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                foreach($filecontent as $val){
#GNDEBUG
#$gnBuf = "byteCounts: " . $val . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                    $index = 0;
                    foreach($patterns as $pat){
                        if (preg_match("/".$pat."/",$val)) {
                            if ($index == 2) {
                                $sdc_cnt++;
                            } else {
                                $bytestr = preg_replace("/^.*val=/","",$val);
                                $bytes[$index] += (int)$bytestr;
                            #$bytes[$index] += 1;
                            }
                            break;
                        }
                        $index++;
                    }
                }
            }
        }
        $outBuf = "Input Bytes = " . $bytes[0] . " @Output Bytes = " . $bytes[1] . " @SDC Counts = " . $sdc_cnt;
        return($outBuf);
    }
   
    function filterUnwantedSession($postParams, $filename){
        $sessionID = (isset($postParams['sessionID']))?$postParams['sessionID']:"Session-Id";
        $patterns = array("^\s*Epoch","^\s*Flags","Session-Id");
        $cmd = "egrep -n '(" . implode("|",$patterns) . ")' $filename";
#GNDEBUG
#$gnBuf = "EGREP: " . $cmd . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        $lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
        
        $result = array();
        foreach($lines as $line){
            $l = preg_split("/(\:\s?)/",$line,2,PREG_SPLIT_NO_EMPTY);
            if(sizeof($l) == 2){
                $result[$l[0]]=$l[1];
            }
        }
        
        ksort($result);
        $start = 0;
        $end = 0;
        $del = array();
        $line = 0;
        $epochs = array();
        $flags = array();
        $new_del = array();
        $sess_line = 0;
        $num_frames = substr_count($stream,"Epoch Time:");
        $mid_frame = ($num_frames + ($num_frames%2))/2;
        $frame_count = 0;

        foreach ($result as $key => $val) {
            if ($frame_count < $mid_frame) {
                if(preg_match("/".$patterns[0]."/",$val)){
                    $frame_count++;
                    if ($frame_count != $mid_frame) {
                        continue;
                    }
                } else {
                    continue;
                }
            } 
            if(preg_match("/(".$patterns[0]."|".$sessionID.")/", $val)){
                if(preg_match("/".$patterns[0]."/",$val)){
                    $end = intval($key);
                    $line = intval($key);
#GNDEBUG
#$gnBuf = "\n" . "EPOCH RESULT: " . $val . " START: " . $start . " LINE: " . $line . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                    array_push($epochs, $line);
                } else {
                    if ($sess_line != 0) {
                        break;
                    }
                    $sess_line = intval($key);
                    $epoch_i_need = 0;
                    foreach ($epochs as $value){
                        if ($value > $sess_line) {
                            break;
                        }
                        $epoch_i_need = $value;
                    }
                    #reset($epochs);
                    if ($epoch_i_need > 1) {
                        $new_val = 1 . "," . ($epoch_i_need - 1) . "d";
                        array_push($new_del, $new_val);
                    }
                    $flags_i_need = 0;
                    foreach ($flags as $value){
                        $flags_i_need = $value;
                        if ($value > $epoch_i_need) {
                            break;
                        }
                    }
                    #reset($flags);
#GNDEBUG
#$gnBuf = "\n" . "SESSIONID RESULT: " . $val . " FLAGS_I_NEED: " . $flags_i_need . " LINE: " . $sess_line . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                }
                if (($start != 0) && ($end != 0)) {
                    if(($end - $start) > 0){
                        $val = $start . "," . ($end - 1) . "d";
                        array_push($del, $val);
                    }
                }
                $start = 0;
                $end = 0;
            }else{
                if(preg_match("/".$patterns[1]."/",$val)){
                    $line = intval($key);
                    array_push($flags, $line);
#GNDEBUG
#$gnBuf = "\n" . "FLAGS RESULT: " . $val . " START: " . $start . " LINE: " . $line . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
                }
                if($start == 0){
                    $start = intval($key);
                }else{
                    $end = intval($key);
                    if(($end - $start) == 1){ $start = $end;}
                }
            }
        }
        $marker = 1;
        if ($sess_line != 0) {
            $flags_i_also_need = 0;
            #reset($flags);
            foreach ($flags as $value){
                if ($value > $sess_line) {
                    break;
                }
                $flags_i_also_need = $value;
            }
            if (($flags_i_also_need - 1) > ($flags_i_need + 1))  {
                $new_val = ($flags_i_need + 1) . "," . ($flags_i_also_need - 1) . "d";
                array_push($new_del, $new_val);
            }
            $flags1 = 0;
            #reset($flags);
            foreach ($flags as $value){
                $flags1 = $value;
                if ($value > $sess_line) {
                    break;
                }
            }
            $epoch1 = 0;
            #reset($epochs);
            foreach ($epochs as $value){
                $epoch1 = $value;
                if ($value > $sess_line) {
                    break;
                }
            }
            if ($flags1 > $epoch1) {
                $marker = $epoch1;
            } else {
                $marker = $flags1;
            }
        }
        if ($marker > 1) {
            $new_val =  $marker . ",\$d";
            array_push($new_del, $new_val);
        }
        if($start != 0){
            $val =  $start . ",\$d";
            array_push($del, $val);
        }
        #$cmd = "sed -e '" . implode(";",$del) . "' $filename";
        $cmd = "sed -e '" . implode(";",$new_del) . "' $filename";
#GNDEBUG
#$gnBuf = "\n" . "Flags: " . $flags_i_also_need . " Marker: " . $marker . " MidFrame: " . $mid_frame . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
#$gnBuf = "\n" . "OLDDEL: " . implode(";",$del) . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
#$gnBuf = "\n" . "NEWDEL: " . implode(";",$new_del) . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
$gnBuf = "\n" . "SED: " . $cmd . "\n";
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        return rtrim($stream);
    }
    
    #function filterSession($postParams, $filename){
        #$sessionID = (isset($postParams['sessionID']))?$postParams['sessionID']:"Session-Id";
    function filterSession($sessionID, $filename){
        $patterns = array("^\s*Epoch","^\s*Flags","Session-Id");
        $cmd = "egrep -n '(" . implode("|",$patterns) . ")' $filename";
#GNDEBUG
#$gnBuf = "EGREP: " . $cmd . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        $lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
        
        $result = array();
        foreach($lines as $line){
            $l = preg_split("/(\:\s?)/",$line,2,PREG_SPLIT_NO_EMPTY);
            if(sizeof($l) == 2){
                $result[$l[0]]=$l[1];
            }
        }
        
        ksort($result);
        $start = 0;
        $end = 0;
        $del = array();
        $line = 0;
        $epochs = array();
        $flags = array();
        $sess_line = 0;
        $num_frames = substr_count($stream,"Epoch Time:");
        $mid_frame = ($num_frames + ($num_frames%2))/2;
        $frame_count = 0;

        foreach ($result as $key => $val) {
            if ($frame_count < $mid_frame) {
                if(preg_match("/".$patterns[0]."/",$val)){
                    $frame_count++;
                    if ($frame_count != $mid_frame) {
                        continue;
                    }
                } 
                continue;
            } 
            if(preg_match("/".$sessionID."/", $val)){
                if ($sess_line != 0) {
                    break;
                }
                $sess_line = intval($key);
                $new_val = 1 . "," . ($sess_line - 1) . "d";
                array_push($del, $new_val);
#GNDEBUG
#$gnBuf = "\n" . "SESSIONID RESULT: " . $sess_line . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
            }
            $end_i_need = 0;
            if ($sess_line != 0) {
                if(preg_match("/(".$patterns[0]."|".$patterns[1].")/",$val)){
                    $end_i_need = intval($key);
                    $new_val =  $end_i_need . ",\$d";
                    array_push($del, $new_val);
                    break;
                }
            }
        }
        $cmd = "sed -e '" . implode(";",$del) . "' $filename";
#GNDEBUG
#$gnBuf = "\n" . "SED: " . $cmd . "\n";
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $gnBuf, FILE_APPEND | LOCK_EX);
        $stream = shell_exec($cmd);
        return rtrim($stream);
    }
    
    function getAllRecords($dir, $pattern){
        $cmd = "ls $dir/*.txt | egrep '{$pattern}' | sort -V";
        $stream = shell_exec($cmd);
        $lines = preg_split("/[\r\n]/",$stream,-1,PREG_SPLIT_NO_EMPTY);
        return $lines;        
    }

    public function matchLineOrder(&$file1content, &$file2content){
        $content1 = array();
        $content2 = array();
        $order = array();
        if((sizeof($file1content) > 0) && (sizeof($file2content) > 0)){
            $duplicates = array();
            foreach($file1content as $l){
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "matchLine\n" . $l . "\n", FILE_APPEND | LOCK_EX);
                $line = preg_split("/:/",$l,2,PREG_SPLIT_NO_EMPTY);
                if(preg_match("/AVP/",$line[0]))
                    $line = preg_split("/((AVP)?\:|\s)/",$l,2,PREG_SPLIT_NO_EMPTY);
                if(array_key_exists($line[0],$content1)){
                    if(array_key_exists($line[0],$duplicates)){
                        $k = $duplicates[$line[0]] + 1;
                    }else{
                        $k = 1;
                    }
                    $duplicates[$line[0]] = $k;                    
                    $content1[$line[0].$k] = $l;
                    array_push($order, $line[0].$k);
                }else{
                    $content1[$line[0]] = $l;
                    array_push($order, $line[0]);
                }
            }
            $duplicates = array();
            foreach($file2content as $l){
                $line = preg_split("/:/",$l,2,PREG_SPLIT_NO_EMPTY);
                if(preg_match("/AVP/",$line[0]))
                    $line = preg_split("/((AVP)?\:|\s)/",$l,2,PREG_SPLIT_NO_EMPTY);                
                if(array_key_exists($line[0],$content2)){
                    if(array_key_exists($line[0],$duplicates)){
                        $k = $duplicates[$line[0]] + 1;
                    }else{
                        $k = 1;
                    }
                    $duplicates[$line[0]] = $k;                    
                    $content2[$line[0].$k] = $l;
                }else{
                    $content2[$line[0]] = $l;
                }
            }        
        }
        if((sizeof($order) > 0) && (sizeof($content2) > 0)){
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "matchLine order\n" . implode("\n",$order) . "\n", FILE_APPEND | LOCK_EX);
            $order = array_flip($order);
            $order = array_intersect_key($order,$content2);
            $content2 = array_merge($order,$content2);
            $file2content = array_values($content2);
        }
    }

    public function getACA($filter,$field,$aca){
        $q = "";
        $aca = array_flip($aca);
        $aca = array_map(create_function('$a','if (preg_match("/^src/", $a)) return str_replace("src","dst",$a); else return str_replace("dst","src",$a);'), $aca);
        $aca = array_flip($aca);
        foreach($aca as $k=>$v){           
            $q .= "+$k:" . "\"$v\"";
        }
        $cmd = <<< EOF
curl -XGET '{$this->url}/{$filter}/_search?q={$q}&default_operator=AND&pretty' -d '{
    "fields": ["{$field}"],
    "query": {
        "filtered": {
            "query": {
                "match_all" : {}
            }                     
        }
    },
    "size": {$this->size},
    "sort": [
        {
          "time": {
            "order": "desc"
          },        
          "Accounting-Record-Number": {
            "order": "asc"
          },          
          "is_request": {
            "order": "desc"
          }          
        }
    ]    
}
'
EOF;
        $stream = shell_exec($cmd);
        $stream = json_decode($stream, true);
        $records = json_decode(json_encode($stream{"hits"}{"hits"}), true);
        return $records;
    }
}
?>
