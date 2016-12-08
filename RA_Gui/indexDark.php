<?php
  header('Pragma: private');
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title>VSBP RA TOOL DASHBOARD</title>
    <link rel="stylesheet" href="css/bootstrap_wp.css">      
    <link rel="stylesheet" href="css/bootstrap.light.min.css" title="Light">     
    <link href="css/ui.fancytree_vista.css" rel="stylesheet" type="text/css"/>
    <link href="css/jquery.ui.timepicker.addon.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/jquery.dataTables.css"/>
    <link rel="stylesheet" href="css/splitter.css"/>    
    <link rel="stylesheet" href="css/dark_styles.css"/>    
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
		      <h5><img src="img/small.png" bs-tooltip="VSBP RA TOOL DASHBOARD" data-placement="bottom"></h5>
		  </span>
		</div>
		<div class="span6 nav-collapse collapse">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
		  <tr>
			  <td width="80%">
			    <h4 class="pull-right" style="color:#ffffff">VSBP RA Tool Dashboard&nbsp;&nbsp;&nbsp;&nbsp;</h4>
			  </td>
		  </tr>
		  <tr><td valign="bottom">
		      <ul class="nav pull-right">	    
			<li class="dropdown">
			    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left: 5px;padding-right: 25px; color: white;">
				    <strong>Time Filter&nbsp;&nbsp;</strong><span id="filter" style="color: white; text-decoration: inherit;"></span><b class="caret"></b>
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
		<form class="form-search" style="position:relative;margin:5px 0">
		    <span class="begin-query">
			<i class="icon-remove-sign pointer remove-query" id="btnClear1" name="btnClear1"></i>
		    </span>
		    <span><input class="search-query panel-query" id="searchMDN" data-min-length="0" data-items="100" placeholder="Search by Username or MDN" type="text"></span>
		    <span class="end-query"><i class="icon-search pointer" id="btnSearch1" name="btnSearch1"></i></span>
		</form>
	    </div>
	  <div class="panel-content">
	    <div id="users-tree"></div>		
	  </div>
	</div>
	<div class="panel-footer"><span id='matches1'></span></div>
      </div>
      <div class="span3">
	<div class="panel-container">
	    <div class="short-query">
		<form class="form-search" style="position:relative;margin:5px 0">
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
	<div class="panel-container">
	  <div class="panel-heading"><h6 class="panel-title" id="sessionDetails-title">Session Accounting Record Summary:</h6></div>
	  <div class="panel-content">
	    <div class="alert alert-info pull-left" id="sessionInfo" style="display: inline-block;">Select one of a session on the left panel to view its accounting record summary.</div>
	    <div class="span3"><table class="table" width="100%" id="ingressDetails" cellspacing="0" cellpadding="0" ></table></div>
	    <div class="span3" style="margin-left: 400px;"><table class="table" width="100%" id="egressDetails" cellspacing="0" cellpadding="0"></table></div>
	  </div>	  
	</div>
	<div class="panel-footer pull-right" style="border: none;"><button id="showDiff" style="padding:5px;display:none;">Show Differences for All Records</button></div>
      </div>
    </div>
    <div class="row-fluid controls-row">
	<div class="panel-heading">
	    <h5 class="panel-title" id="showDiff-title" style="display:none;">Session's Accounting Records Diff:</h5>
	</div>
	<div class="panel-container" id="pane1" style='max-height:550px; display: none;'>
	  <div class="span6 panel-heading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h5 class="panel-title">INGRESS:</h5></div>
	  <div class="span6 panel-heading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h5 class="panel-title">EGRESS:</h5></div>
	  <div class="span12 panel-content" style='margin-left: -10px;'><div id="diff1"></div></div>
	</div>
      </div>
</div>
</body>
</html>
