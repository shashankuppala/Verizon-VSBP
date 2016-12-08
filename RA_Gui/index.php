<?php
  header('Pragma: private');
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
?>
<!--<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title>VSBP RA TOOL DASHBOARD</title>     
   <link href="css/jquery-ui-1.10.3.bootstrap.custom.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap_wp.css">     
    <link href="css/ui.fancytree_vista.css" rel="stylesheet" type="text/css"/>
    <link href="css/jquery.ui.timepicker.addon.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/jquery.dataTables.css"/>
    <link rel="stylesheet" href="css/splitter.css"/>
    <script src="js/jquery/jquery-1.9.0.js" type="text/javascript"></script>
    <script src="js/jquery/jquery-splitter.js" type="text/javascript"></script>
    <script src="js/jquery/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="js/jquery/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="js/jquery/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/jquery/jquery.fancytree.js" type="text/javascript"></script>
    <script src="js/jquery/jquery.fancytree.filter2.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row-fluid" style="height:100px">
      <nav class="navbar navbar-default navbar-static-bottom" role="navigation">
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">   	
                <div class="span6">
		  <span class="brand">
		      &nbsp;&nbsp;&nbsp;<img src="img/verizon_logo.png" bs-tooltip="VSBP RA TOOL DASHBOARD" data-placement="bottom" style="border: none">
		  </span>
		</div>
		<div class="span6 nav-collapse collapse">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
		  <tr>
			  <td width="80%">
			    <h4 class="pull-right" style="color:#000000">VSBP RA Tool Dashboard Version 1.8 &nbsp;&nbsp;&nbsp;&nbsp;</h4>
			  </td>
		  </tr>
		  <tr><td valign="bottom">
		      <ul class="nav pull-right">	    
			<li class="dropdown">
			    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 5px;padding-right: 25px; color: #000000;">
				    <strong>Time Filter&nbsp;&nbsp;</strong><span id="filter" style="text-decoration: inherit;"></span><b class="caret"></b>
			    </a>	    
			    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" >
			      <li><a role="menuitem" tabindex="-1" id="1h">Last 1 hour</a></li>
			      <li><a role="menuitem" tabindex="-1" id="6h">Last 6 hours</a></li>
			      <li><a role="menuitem" tabindex="-1" id="12h">Last 12 hours</a></li>
			      <li class="divider"></li>
			      <li><a role="menuitem" tabindex="-1" id="1d">Last 1 day</a></li>
			      <li><a role="menuitem" tabindex="-1" id="2d">Last 2 days</a></li>
			      <li><a role="menuitem" tabindex="-1" id="7d">Last 7 days</a></li>
			      <li><a role="menuitem" tabindex="-1" id="30d">Last 30 days</a></li>
			    </ul>
			</li>
		      </ul>
		    </td>
		  </tr>
		</table>
		</div>
            </div>
        </div>
      </nav>
    </div>
    <div class="row-fluid controls-row" id="top-frame">
        <div class="span6" id="top-frame1">
            <div class="span3">
              <div class="panel-container">
                  <div class="short-query">
                      <form class="form-search" style="position:relative;margin:5px 10px">
                          <span class="begin-query">
                              <i class="icon-remove-sign pointer remove-query" id="btnClear1" name="btnClear1"></i>
                          </span>
                          <span><input class="search-query panel-query" id="searchMDN" data-min-length="0" data-items="100" placeholder="Search by MDN or IMSI" type="text"></span>
                          <span class="end-query"><i class="icon-search pointer" id="btnSearch1" name="btnSearch1"></i></span>
                      </form>
                  </div>
		<div> Check if IMSI: <input type='checkbox' name='searchUname' id="searchUname"></input></div>                  
                <div class="panel-content">
                  <div id="users-tree"></div>		
                </div>
              </div>
              <div class="panel-footer"><span id='matches1'></span></div>
            </div>
            <div class="span3">
              <div class="panel-container">
                  <div class="short-query">
                      <form class="form-search" style="position:relative;margin:5px 10px">
                          <span class="begin-query">
                              <i class="icon-remove-sign pointer remove-query" id="btnClear2" name="btnClear2"></i>
                          </span>
                          <span><input class="search-query panel-query" id="searchSession" data-min-length="0" data-items="100" placeholder="Search by Session ID" type="text"></span>
                          <span class="end-query"><i class="icon-search pointer" id="btnSearch2" name="btnSearch2"></i></span>
                      </form>
                  </div>
                <div class="panel-content">
                      <div id="sessions-tree"></div>
                </div>
                </div>
                <div class="panel-footer"><span id='matches2'></span></div>
            </div>
        </div>
      <div class="span6">
	<div class="panel-container panel-default">
	  <div class="panel-heading"><h6 class="panel-title" id="sessionDetails-title">Session Accounting Record Summary:</h6></div>
	  <div class="alert alert-info pull-left" id="sessionInfo" style="display: inline-block; margin: 10px;"><i class="icon-info-sign"></i>&nbsp;&nbsp;Select one of a session on the left panel to view its accounting record summary.</div>          
	  <div class="panel-content" id="detail-frame" style="margin-left: 20px; margin-top: 20px;">
	    <div class="span3"><table class="display" width="90%" id="ingressDetails" cellspacing="0" cellpadding="0" ></table></div>
	    <div class="span3"><table class="display" width="90%" id="egressDetails" cellspacing="0" cellpadding="0"></table></div>
	  </div>	  
	</div>
        <div class="panel-footer" style="padding:10px;"><span id='bytedata' style="color: green; font-size: 10pt"></span></div>
	<div class="panel-footer pull-right" style="background: inherit;border: none;"><button class="ui-button-large" id="showDiff" style="margin:5px;padding:5px;display:none;">Show Differences for All Records</button></div>
      </div>
    </div>
    <div class="row-fluid controls-row" style="margin-top: -30px;">
	<div class="panel-heading">
	    <h5 class="panel-title" id="showDiff-title" style="display:none;">Session's Accounting Records Diff:</h5>
	</div>
	<div class="panel-container panel-default" id="pane1" style='display:none;'>
          <div class="span1"></div>
	  <div class="span5 panel-heading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h5 class="panel-title">INGRESS:</h5></div>
          <div class="span1"></div>
	  <div class="span5 panel-heading">&nbsp;<h5 class="panel-title">EGRESS:</h5></div>
	  <div class="span12 panel-content" style='margin-left: -10px;'><div class="tabs-left" id="diff1"></div></div>
	</div>
        <div class="panel-footer" id="pane1-footer" style='display:none;'></div>
      </div>
</div>
</body>
</html>-->
<!DOCTYPE html>
<html lang="en" data-ng-controller="RootController" data-ng-app="main">
<head>
    <title>VSBP Tool</title>
    <script type="text/javascript" src="/RA_Gui/DebugGUI/Angular/lib/jquery-1.10.2.min.js"></script><!---jquery.min.js----->
    <link rel="stylesheet" href="/RA_Gui/DebugGUI/Angular/lib/jquery-ui.min.css"/><!--jquery-ui.min.css------>
    <script src="/RA_Gui/DebugGUI/Angular/lib/jquery-ui.min.js"></script>
    <script src="/RA_Gui/DebugGUI/Angular/lib/bootstrap.min.js"></script>
    <script src="/RA_Gui/DebugGUI/Angular/lib/angular.min.js"></script> 
    <script src="/RA_Gui/DebugGUI/Angular/lib/angular-route.js"></script>  
    <script src="/RA_Gui/DebugGUI/Angular/lib/angular-animate.min.js"></script>
    <!--<script src="/DebugGUI/Angular/lib/angular-touch.min.js"></script>-->
    <script src="/RA_Gui/DebugGUI/Angular/lib/angular-resource.min.js"></script>
    <script type="text/javascript" src="/RA_Gui/DebugGUI/Angular/lib/ui-bootstrap-tpls-0.13.3.min.js"></script>
    <script src="/RA_Gui/DebugGUI/Angular/lib/datetimepicker_working.js"></script>
     <script src="/RA_Gui/DebugGUI/Angular/lib/jquery.cookie.js"></script>   
    
   <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="/RA_Gui/DebugGUI/Angular/css/styles.css" />
    
     <script src="/RA_Gui/DebugGUI/Angular/lib/bootstrap.min.js"></script>
     
     <link rel="stylesheet" type="text/css" href="/RA_Gui/DebugGUI/Angular/css/bootstrap.css" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/RA_Gui/DebugGUI/Angular/css/datetimepicker_working.css" />
</head>
<body>
        <div id="custom-bootstrap-menu" class="navbar navbar-default " role="navigation">
        <div class="container-fluid">
            <div class="collapse navbar-collapse navbar-menubuilder">
                <img src="img/verizon_logo.png" style="padding-top:23px; width:125px;	"class="img">
                <ul class="nav navbar-nav navbar-left">
                    <!--<li><a href="#DashBoard"><span class="glyphicon glyphicon-stats">DashBoard</span></a></li>-->
                    <li><a href="#Mdnanalysis"><span class="glyphicon glyphicon-earphone">MDNAnalysis</span></a></li>
                    <!--<li><a href="#Packetanalysis"><span class="glyphicon glyphicon-file">PacketAnalysis</span></a></li>-->
                   <!-- <li><a href="#loganalysis">Log Analysis</a></li>-->
                    <!--<li><a href="#Configuration">Configuration</a></li>-->
                    <li><a href="" onclick="FnLogout()"><span class="glyphicon glyphicon-log-out">Logout</span></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right"><li><h3>VSBP Tool Version 1.10</h3></li></ul>
                
            </div>
        </div>
    </div>
    <div class="col-md-12">    
    
    
<div id="loader" style="display:none;" class="loading">Loading&#8230;Please wait........</div>    
    
        
        <div ng-view></div>
    </div>
</body>
</html>
<!--links for controllers,services-->
<script src="/RA_Gui/DebugGUI/Angular/routing/App.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/controllers/DashboardController.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/controllers/AnalysisController.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/controllers/configController.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/controllers/PacketAnalysisController.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/controllers/clockController.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/directives/timePicker.js"></script>
<script src="/RA_Gui/DebugGUI/Angular/services/RequestService.js"></script>

<script>
function FnLogout(){
if (confirm("Do you want to redirect to the Home page")) {

	window.location.href="https://www.google.com";
}
  else {
          return false; 	
}
}
</script>





