<? 
/*
	Copyright (C) 2013  xtr4nge [_AT_] gmail.com

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?
//include "../login_check.php";
include "../_info_.php";
include "/usr/share/FruityWifi/www/config/config.php";
include "/usr/share/FruityWifi/www/functions.php";

include "options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($iface_wifi, "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];

if($service != "") {
    
    if ($action == "start") {
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" );
            
            $exec = "echo '' > $mod_logs";
            exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" );
        }
    
        // ADD selected options
        $tmp = array_keys($mode_ngrep);
        for ($i=0; $i< count($tmp); $i++) {
             if ($mode_ngrep[$tmp[$i]][0] == "1") {
                $options .= " -" . $tmp[$i] . " " . $mode_ngrep[$tmp[$i]][2];
            }
        }
    
        //$exec = "/usr/bin/ngrep -q -d wlan0 -W byline -t $mode $options >> $mod_logs &";
        //$exec = "/usr/bin/ngrep -q -d wlan0 -W byline -t 'Cookie' 'tcp and port 80' >> $mod_logs &";
        
        $filename = "$mod_path/includes/templates/".$ss_mode;
        $data = open_file($filename);
        
        $exec = "/usr/bin/ngrep -q -d wlan0 -W byline $options -t $data >> $mod_logs &";        
        exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" );
        
    } else if($action == "stop") {
        // STOP MODULE
        $exec = "killall ngrep";
        exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" );
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" );
            
            $exec = "echo '' > $mod_logs";
            exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"" );
        }

    }

}

//header('Location: ../index.php?tab=0');
header('Location: ../../action.php?page=ngrep');

?>