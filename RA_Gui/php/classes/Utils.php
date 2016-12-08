<?php
class Utils {
    public $xmlFile;
    public $xmlConfig;
    public $webDir;
  
    public function __construct(){
	try {
            $this->xmlFile= $_SERVER['DOCUMENT_ROOT'] . "/RA_Gui/RA_config.xml";
            $xml = file_get_contents($this->xmlFile, true, null);
            $this->xmlConfig = simplexml_load_string($xml);
	}catch(Exception $e){
            syslog(LOG_ERR, "Exception: " . $e->getMessage());
	    die("Exception: " .$e->getMessage());
	}
    }    

    public function array_column($input, $column_key, $index_key = null)
    {
        if ($index_key !== null) {
            // Collect the keys
            $keys = array();
            $i = 0; // Counter for numerical keys when key does not exist
            
            foreach ($input as $row) {
                if (array_key_exists($index_key, $row)) {
                    // Update counter for numerical keys
                    if (is_numeric($row[$index_key]) || is_bool($row[$index_key])) {
                        $i = max($i, (int) $row[$index_key] + 1);
                    }
                    
                    // Get the key from a single column of the array
                    $keys[] = $row[$index_key];
                } else {
                    // The key does not exist, use numerical indexing
                    $keys[] = $i++;
                }
            }
        }
        
        if ($column_key !== null) {
            // Collect the values
            $values = array();
            $i = 0; // Counter for removing keys
            
            foreach ($input as $row) {
                if (array_key_exists($column_key, $row)) {
                    // Get the values from a single column of the input array
                    $values[] = $row[$column_key];
                    $i++;
                } elseif (isset($keys)) {
                    // Values does not exist, also drop the key for it
                    array_splice($keys, $i, 1);
                }
            }
        } else {
            // Get the full arrays
            $values = array_values($input);
        }
        
        if ($index_key !== null) {
            return array_combine($keys, $values);
        }
        
        return $values;
    }
    
    public function outputJSON($data) {
	header("Content-Type: application/json;charset=utf-8");
	echo json_encode($data);
    }

    public function verifyUser($uname, $pwd) {
        $userInfo = array();
	
	$descriptorspec = array(
	   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
	   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
	   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
	);

	$cwd = '/tmp';
	$env = array('some_option' => 'aeiou');
	
	$process = proc_open("/usr/sbin/pwauth", $descriptorspec, $pipes, $cwd, $env);

	if (is_resource($process)) {
		fwrite($pipes[0], "$uname\n$pwd");
		fclose($pipes[0]);

		$output = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
		//fclose($pipes[2]);

		$return_value = proc_close($process);

		switch($return_value) {
			case 0:	// Status-OK: Valid Login
				$userInfo['username']=$uname;
				return $userInfo;
			case 1: // Status-unknown: Nonexistant login or (for some configurations) incorrect password. 
			case 2: // Status-invalid: Incorrect password (for some configurations).
			default:
				syslog(1,"authentication failed for user '$uname', return value $return_value");
				return $userInfo;
		}
	}
	return $userInfo;
    }
    function dirToArray($dir,$filterExt) {        
       $result = array(); 
       $cdir = (is_readable($dir))?scandir($dir):array();
       $filter = ($filterExt != "")?str_replace(",","|",$filterExt):"";
       $filter = str_replace(".","\.",$filter);
       foreach ($cdir as $value) 
       {
    
	  if (!in_array($value,array(".",".."))) 
	  { 
	     if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
	     {
		$result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value, $filterExt);
	     } 
	     else 
	     {
		if(preg_match("/({$filter})$/",$value)){
		    $result[] = $dir . DIRECTORY_SEPARATOR . $value; 
		}           
	     } 
	  } 
       } 
       //syslog(1, "Result: " . json_encode($result));
       return $result; 
    }
    function arrayToJson($arr){
	$result = array();
	foreach($arr as $k => $value){
	    $record = array();
	    if(is_array($value)){
		$record['title'] = $k . "~" . sizeof($value);
		$record["folder"] = true;
		$record["lazy"] = true;
		$record["children"] = array();
		if(sizeof($value) > 0){
		    if(is_int($k)){
			$record['title'] = basename($value);
			$record = array_shift($record);
			$record['data'] = array($value);
		    }else{
			$record["children"] = $this->arrayToJson($value);                    
		    }
		}else{
		    //$record["childen"] = null;
		    $record = array();
		}
		if(sizeof($record["children"]) == 0){
		    $record = array();
		}
	    }else{
		$perms = fileperms($value);
		if(file_exists($value) && ($perms & 0x0004) && (filesize($value) > 0)){
		    $filedate = date ("d-M-Y H:i:s.", filemtime($value));
		    $filesize = round(filesize($value) / 1024);
		    $filesize = ($filesize > 0)?$filesize . 'KB':'0KB';
		    $record['href'] =  urlencode(basename($value));		
		    $record['title'] = basename($value) . "~" . $filesize . "~" . $filedate;// . "~" . date('d-M-Y',filemtime($value)) . "~" . filesize($value);
		    $record['folder'] = false;
		    $record['data'] = array($value);// . "~" . date('d-M-Y',filemtime($value)) . "~" . filesize($value));
		}else{
		    //$record['href'] = #;
		    $record = array();
		}
	    }
	    if(!empty($record)){
		array_push($result,$record);
	    }
	}
	return $result;
    }
    function bash2html($string) {
	    $result = null;
	    
	    if(!is_null($string)) {
		    $pattern = array(
			    '/\\033\[30m(.*?)\\033\[39;49m/s',
			    '/\\033\[31m(.*?)\\033\[39;49m/s',
			    '/\\033\[32m(.*?)\\033\[39;49m/s',
			    '/\\033\[33m(.*?)\\033\[39;49m/s',
			    '/\\033\[34m(.*?)\\033\[39;49m/s',
			    '/\\033\[35m(.*?)\\033\[39;49m/s',
			    '/\\033\[36m(.*?)\\033\[39;49m/s',
			    '/\\033\[37m(.*?)\\033\[39;49m/s',
			    '/\\033\[90m(.*?)\\033\[39;49m/s',
			    '/\\033\[91m(.*?)\\033\[39;49m/s',
			    '/\\033\[92m(.*?)\\033\[39;49m/s',
			    '/\\033\[93m(.*?)\\033\[39;49m/s',
			    '/\\033\[94m(.*?)\\033\[39;49m/s',
			    '/\\033\[95m(.*?)\\033\[39;49m/s',
			    '/\\033\[96m(.*?)\\033\[39;49m/s',
			    '/\\033\[97m(.*?)\\033\[39;49m/s',
			    '/\\033\[0;31m(.*?)\\033\[0m/s',
			    '/\\033\[0;33m(.*?)\\033\[0m/s',
			    '/\\033\[0;32m(.*?)\\033\[0m/s'
		    );
		    $replace = array(
                            '<span style="color:#000000">$1</span>',
			    '<span style="color:#FF0000">$1</span>',
			    '<span style="color:#008000">$1</span>',
			    '<span style="color:#FFD700">$1</span>',
			    '<span style="color:#0000FF">$1</span>',
			    '<span style="color:#FF00FF">$1</span>',
			    '<span style="color:#00FFFF">$1</span>',
			    '<span style="color:#d3d3d3">$1</span>',
			    '<span style="color:#A9A9A9">$1</span>',
			    '<span style="color:#FF4500">$1</span>',
			    '<span style="color:#90EE90">$1</span>',
			    '<span style="color:#FFFFE0">$1</span>',
			    '<span style="color:#ADD8E6">$1</span>',
			    '<span style="color:#EE82EE">$1</span>',
			    '<span style="color:#E0FFFF">$1</span>',
			    '<span style="color:#FFFFFF">$1</span>',
			    '<span style="color:#FF0000">$1</span>',
			    '<span style="color:#FFD700">$1</span>',
			    '<span style="color:#008000">$1</span>'
		    );
    
		    $result = preg_replace($pattern, $replace, $string);
	    }
    
	    return $result;
    }
}
?>
