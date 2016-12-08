<?php
require_once("recordHandler.php");
$rh = new recordHandler();

#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', json_encode($_REQUEST) . "\n", FILE_APPEND | LOCK_EX);
//syslog(1, json_encode($_REQUEST));
if(isset($_REQUEST['option']) && ($_REQUEST['option'] != "")){
    switch ($_REQUEST['option']) {
            case '-d':
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Before showDiff\n", FILE_APPEND | LOCK_EX);
                $result = $rh->showDiff($_REQUEST);
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "After showDiff\n", FILE_APPEND | LOCK_EX);
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $result . "\n", FILE_APPEND | LOCK_EX);
                print $result;
                break;
            case '-r':
                $result = $rh->showSessionDetails($_REQUEST);
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $result . "\n", FILE_APPEND | LOCK_EX);
                print $result;
                break;      
            case '-f':
                #$result = $rh->showSessionDetails($_REQUEST);
                #print $result;
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "Before findDiff\n", FILE_APPEND | LOCK_EX);
                $result = $rh->findDiff($_REQUEST);
                print $result;
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "After findDiff: " . "$result" . "\n", FILE_APPEND | LOCK_EX);
                break;      
            case '-s':
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "OPTION -s\n", FILE_APPEND | LOCK_EX);
                $result = $rh->showSessions($_REQUEST, 0);
                print $result;
                break;       
            case '-u':
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "OPTION -u\n", FILE_APPEND | LOCK_EX);
                $result = $rh->showSessions($_REQUEST, 1);
                print $result;
                break;              
            case '-m':
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "OPTION -m\n", FILE_APPEND | LOCK_EX);
                $result = $rh->showSessions($_REQUEST, 2);
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $result . "\n", FILE_APPEND | LOCK_EX);
                print $result;
                break;              
            case '-t':
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "OPTION -t\n", FILE_APPEND | LOCK_EX);
                $result = $rh->showStats($_REQUEST);
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $result . "\n", FILE_APPEND | LOCK_EX);
                print $result;
                break;              
                
            case '-p':
#GNDEBUG
file_put_contents('/var/www/html/RA_Gui/gnlog.txt', "OPTION -p\n", FILE_APPEND | LOCK_EX);
                $result = $rh->pcapStats($_REQUEST);
#GNDEBUG
#file_put_contents('/var/www/html/RA_Gui/gnlog.txt', $result . "\n", FILE_APPEND | LOCK_EX);
                print $result;
                break;              

            default:
                break;                
                
    }
}
?>
