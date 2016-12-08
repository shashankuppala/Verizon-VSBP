var $width = $(window).width();
var $height = $(window).height();
var selKeys = null;
var $url = "php/classes/requestHandler.php";
var $timefilter;
var $iselected = false;
var $eselected = false;
function toggleShowDiff(){
    var $btntxt = "Show Differences for All Records";
    if ($iselected && !$eselected) {
	$btntxt = "Show Selected INGRESS Records";
    }else if(!$iselected && $eselected) {
	$btntxt = "Show Selected EGRESS Records";
    }else if ($iselected && $eselected) {
	$btntxt = "Show Differences for Selected Records";
    }
    $("#showDiff").text($btntxt).button("refresh");
}
function getChkboxId($objID){
    var ids = [];
    $("input." + $objID).each(function(){
	if($(this).is(":checked")){
	    ids.push($(this).attr('id'));
	}
    });
    return ids;
}
function clearSelection($objID) {
    $objID = ($objID)?$objID:"";
  $("#" + $objID + "tree").fancytree("getRootNode").visit(function(node){
	//node.selected = false;
	node.setSelected(false);
	node.setActive(false);
  }); 
}
function doSearch(e,$treeID, $objID){
    var n,
    tree = $("#" + $treeID).fancytree("getTree"),
    match = $("#" + $objID).val(),
    $option = "-" + $treeID.charAt(0),
    $btnNum = ($option == '-u')?1:2;
    match = ($.trim(match) === "")?".*":match;
//alert("dosearch " + $option);
    if(e && (e.which == 13 || e.which == 1)){
	e.preventDefault();
	if(e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === ""){
	    $('#btnClear' + $btnNum).click();
	    return;
	}else{
	    if ($option == '-u') {
		clearSelection("sessions-");
                if ($('#searchUname').prop('checked') == false) {
                    $option = '-m';
                }
	    }else{
		clearSelection("users-");
	    }
	    tree.reload({
		type: "POST",
		url: $url,
		data: { obj:'showSessions',
			option: $option,
			filter: match,
			from: $timefilter
		    }		    
	    }).done(function(){
		n = tree.count();
		var txt= (n > 1)?"matches":"match";
		$('#btnClear' + $btnNum).attr("disabled", false);
		$('#' + $objID).val(match);
		$("span#matches"+$btnNum).text(n + " " + txt);
		// expand all
		$("#" + $treeID).fancytree("getRootNode").visit(function(node){
		    node.setExpanded(true);
		});
		showDetail(false);
	    });
	}
    }    
}

function doClear($treeID, $objID, $reload){
    var tree = $("#" + $treeID).fancytree("getTree");
    var $option = "-" + $treeID.charAt(0);
    var $btnNum = ($option == '-u')?1:2;
    $('#' + $objID).val("");
    $("span#matches"+$btnNum).text("");
//alert("doClear " + $option);
    if ($treeID == "users-tree") {
        if ($('#searchUname').prop('checked') == false) {
            $option = '-m';
        }
    }
    if ($reload) {
	tree.reload({
	  type: "POST",
	    url: $url,
	    data: {obj:'showSessions',option: $option, from: $timefilter}
	}).done(function(){
	    var n = tree.count();
	    var txt= (n > 1)?"records":"record";
	    $("span#matches"+$btnNum).text(n + " " + txt + " found");
	    $("#" + $treeID).fancytree("getActiveNode");
	});
    }
    if ($treeID == "sessions-tree") {
	showDetail(false);
    }
}

function showDetail($flag){
    if ( $.fn.dataTable.isDataTable( "#ingressDetails" ) ) {
	$("#ingressDetails").DataTable().destroy();
	$iselected = false;
    }
    if ( $.fn.dataTable.isDataTable( "#egressDetails" ) ) {
	$("#egressDetails").DataTable().destroy();
	$eselected = false;
    }  
    if ($flag) {
	$("#sessionInfo").hide();
	$("#ingressDetails").addClass("table-bordered");
	$("#egressDetails").addClass("table-bordered");	
	toggleShowDiff();
	$("#showDiff").show();
    }else{
	$("#sessionInfo").show();
	$("#showDiff").hide();
	$("span#bytedata").text("");
	$("#showDiff-title").hide();
	$("#pane1").hide();
	$("#pane1-footer").hide();
	$("#ingressDetails").removeClass("table-bordered");
	$("#egressDetails").removeClass("table-bordered");
	$("#ingressDetails").html("");
	$("#egressDetails").html("");
    }  
}

function getSessions(node, $treeID){
    if ($treeID) {
	var tree = $("#" + $treeID).fancytree("getTree");
	var $option = "-" + $treeID.charAt(0);
	var $q;
	if (node.selected) {
            if ($('#searchUname').prop('checked') == false) {
	        $q = "Subscription-Id-Data:" + node.title;
            } else {
	        $q = "User-Name:" + node.title;
            }
	}else{
	    $q = "";
	}
	tree.reload({
	    type: "POST",
	    url: $url,
	    data: { obj:'showSessions',
		    option: $option,
		    query: $q,
		    from: $timefilter
	    }		    
	}).done(function(){
	    var n = tree.count();
	    var txt= (n > 1)?"records":"record";
	    $("span#matches2").text(n + " " + txt + " found");
	});	    
    }
}

function getDiffs(node, $treeID){
    if ($treeID) {
        var tree = $("#" + $treeID).fancytree("getTree");
        var $q = "Session-Id:" + node.title;
        $("#showDiff").hide();
	$("span#bytedata").text("");
        $("#showDiff-title").hide();
        $("#pane1").hide();
        $("#pane1-footer").hide();
        $("span#matches2").html('<div id="loading" style="padding-left: 10px; color:#000;"><img src="img/load_big.gif">&nbsp;&nbsp;Getting Records ...</img></div>');
	$.ajax({
	    type: "POST",
	    url: $url,
	    data: { obj:'findDiff',
		    option: '-f',
		    query: $q
	    },		    
            dataType: "json",
	    success: function(txt) {
                // works! alert(txt);
                var res = txt.split(",");
	        $("span#matches2").text(res[0]);
	        $("span#bytedata").text(res[1] + " _____________________________________ " + res[2]);
	        //$("span#bytedata").html(res[1]);
                $("#showDiff").show();
	    }
	});	    
    }
}

function getSessionDetails(node, $objID1, $objID2){
    var $output;
    if ($objID1 && $objID2) {
	var params = {};
	params['obj'] = "showSessionsDetails";
	params['option'] = "-r";
	params['query'] = "Session-Id:" + node.title;
	if (node.selected) {
	    $("#sessionDetails-title").html("Session '" + node.title + "' -- Accounting Records Summary:");
	    showDetail(true);
	    
	    //params['obj'] = "showSessionsDetails";
	    //params['option'] = "-r";
	    //params['query'] = "Session-Id:" + node.title;
	    var table1 = $("#" + $objID1).DataTable( {
		    "processing": true,
		    "serverSide": true,
		    "paging":   false,
		    "ordering": false,
		    "searching":     false,
		    "info": false,
		    "ajax": {
			"type"	  : "POST",
			"cache"	  : false,
			"url"	  : $url,
			"data"	  : {
			    "obj": "showSessionsDetails",
			    "option": "-r",
			    "filter": "ingress",
			    "query": "Session-Id:" + node.title
			},
			"dataType": 'json'
		    },
		    "columns": [
			{
			    data:   "id", "title": "<input type='checkbox' id='iselectAll'></input>",
			    render: function ( data, type, row ) {
		                var inbytes, outbytes;
				if ( type === 'display' ) {
				    return '<input type="checkbox" class="ingress-chkbox" id="'+data+'">';
				}
				return data;
			    },
			    className: "dt-body-center", "width": "3%"
			},
			{ "data": "time", "title": "Time", "class":"dt-center", "width": "10%"},
			{ "data": "_type", "title": "Ingress", "class":"dt-center", "width": "10%"},
			{ "data": "Accounting-Record-Type", "title": "Record Number/Type", "class":"dt-center", "width": "10%"},
			{ "data": "srcport", "title": "SrcPort", "class":"dt-center", "width": "10%"},
			{ "data": "dstport", "title": "DstPort", "class":"dt-center", "width": "10%"}
			//{ "data": "Accounting-Input-Octets", "title": "InBytes", "class":"dt-center", "width": "10%"},
			//{ "data": "Accounting-Output-Octets", "title": "OutBytes", "class":"dt-center", "width": "10%"}
		    ]
		} );
	    var table2 = $("#" + $objID2).DataTable( {
		    "processing": true,
		    "serverSide": true,
		    "paging":   false,
		    "ordering": false,
		    "searching":     false,
		    "info": false,
		    "ajax": {
			"type"	  : "POST",
			"cache"	  : false,
			"url"	  : $url,
			"data"	  : {
			    "obj": "showSessionsDetails",
			    "option": "-r",
			    "filter": "egress",
			    "query": "Session-Id:" + node.title
			},
			"dataType": 'json'
		    },
		    "columns": [
			{
			    data:   "id", "title": "<input type='checkbox' id='eselectAll'></input>",
			    render: function ( data, type, row ) {
				if ( type === 'display' ) {
				    return '<input type="checkbox" class="egress-chkbox" id="'+data+'">';
				}
				return data;
			    },
			    className: "dt-body-center", "width": "3%"
			},			
			{ "data": "time", "title": "Time", "class":"dt-center", "width": "10%" },
			{ "data": "_type", "title": "Egress", "class":"dt-center", "width": "10%" },
			{ "data": "Accounting-Record-Type", "title": "Record Number/Type", "class":"dt-center", "width": "10%" },
			{ "data": "srcport", "title": "SrcPort", "class":"dt-center", "width": "10%" },
			{ "data": "dstport", "title": "DstPort", "class":"dt-center", "width": "10%" }
			//{ "data": "Accounting-Input-Octets", "title": "InBytes", "class":"dt-center", "width": "10%"},
			//{ "data": "Accounting-Output-Octets", "title": "OutBytes", "class":"dt-center", "width": "10%"}
		    ]
		} );
	}else{
	    showDetail(false);
	}
    }
}

function showDiff($treeID, $objID){
    var $output;
    var node = $("#" + $treeID).fancytree("getActiveNode");
    if ($objID) {
	var params = {};
	params['obj'] = "showDiff";
	params['sessionID'] = node.title;
	params['option'] = "-d";
	$("#pane1").css("min-height", Math.floor($height * 0.4));
	$("#pane1").show();
	$("#pane1-footer").show();
        $("#showDiff-title").html("Session '" + node.title + "' -- Accounting Records Diff:");
        $("#showDiff-title").show();
        $("#" + $objID).html('<div id="loading" style="padding-left: 50px; color:#fff;"><img src="img/load_big.gif">&nbsp;&nbsp;Getting INGRESS and EGRESS information ...</img></div>');
	$ingressFiles = getChkboxId("ingress-chkbox");
	$egressFiles = getChkboxId("egress-chkbox");
	if($ingressFiles.length > 0)
	    params['ingressFiles'] = $ingressFiles.join(",");
	if($egressFiles.length > 0)
	    params['egressFiles'] = $egressFiles.join(",");
	$.ajax({
		type	: "POST",
		cache	: false,
		url	: $url,
		data	: $.param(params, true),
		dataType: 'json',
		success: function(data) {
		  if(data.status == "success"){
		    $("#" + $objID).html(data.record);
		    $("#" + $objID).scrollTop( 0 );
		    $( "#" + $objID ).tabs();
		  }else{
		    alert(data.message);
		    $("#" + $objID).html("");
		    $("#showDiff-title").hide();
		    $("#pane1").hide();
		    $("#pane1-footer").hide();
		  }	    	
		}
	});	    
    }
}

$(function () {
    $("#users-tree").fancytree({
	extensions: ["filter"],
	checkbox: true,
	selectMode: 1,
        source: {
            type: "POST",
            url: $url,
            //data: {obj:'showSessions',option: "-u"}
            data: {obj:'showSessions',option: "-m"}
        },
        filter: {
            mode: "dimm",
            leavesOnly: false
        },
        activate: function(event, data){
	    //alert(data.node.title);
	  if( data.node.isLazy()){
	    selectedNode = null;
	    activeNode = data.node;
            return false;
          }else{
	    data.node.setSelected(true);
	    selectedNode = data.node;
	    activeNode = null;
	  }
	},
	click: function(event, data) {
	    // We should not toggle, if target was "checkbox", because this
	    // would result in double-toggle (i.e. no toggle)
	    if( data.targetType == "checkbox" ){
		//data.node.toggleSelected();
	    }else{
		var anchor, idx, inc,
			tree = data.tree,
			node = data.node;

		 if ( event.ctrlKey || event.altKey || event.metaKey ) {
			node.toggleSelected();
		} else {		    
		    data.tree.options.selectMode = 1;	    
		    clearSelection('users-');
		    node.setSelected(true);
		}
		if (node.selected) {
		    doClear("sessions-tree","searchSession", false);
		}
		getSessions(node,'sessions-tree');				
	    }
	},
	keydown: function(event, data) {
	    if( event.which === 32 ) {
		data.node.toggleSelected();
		return false;
	    }
	}
	
    });

    $('#searchMDN').keypress(function(e){
//alert($('#searchUname').prop('checked'));
	doSearch(e,"users-tree", "searchMDN");
    });
    
    $('#btnSearch1').button().click(function(e){
	doSearch(e,"users-tree", "searchMDN");
    });	
    
    $('#btnClear1').button().click(function(e){
	e.preventDefault();
	if ($('#searchMDN').val() != "") {
	    doClear("users-tree","searchMDN", true);
	}else{
	    $(this).attr("disabled", true);
	}
    }).attr("disabled", true);
    
    $("#sessions-tree").fancytree({
	extensions: ["filter"],
	checkbox: true,
	selectMode: 1,
        source: {
            type: "POST",
            url: $url,
            data: {obj:'showSessions',option: "-s"}
        },
        filter: {
            mode: "dimm",
            leavesOnly: false
        },
        activate: function(event, data){
	  if( data.node.isLazy()){
            return false;
          }else{
	    data.node.setSelected(true);
	  }
	},
	click: function(event, data) {
	    // We should not toggle, if target was "checkbox", because this
	    // would result in double-toggle (i.e. no toggle)
	    if( data.targetType == "checkbox" ){
		//data.node.toggleSelected();
	    }else{
		var anchor, idx, inc,
			tree = data.tree,
			node = data.node;
		if ( event.ctrlKey || event.altKey || event.metaKey ) {
			node.toggleSelected();
		} else {
		    data.tree.options.selectMode = 1;	    
		    clearSelection('sessions-');
		    node.setSelected(true);
		}
		getSessionDetails(node,"ingressDetails", "egressDetails");
                getDiffs(node, "sessions-tree");
//data.node.setTitle(data.node.title + " testing");
	    }
	},
	keydown: function(event, data) {
	    if( event.which === 32 ) {
		data.node.toggleSelected();
		return false;
	    }
	}
	
    });

    $('#searchSession').keypress(function(e){
	doSearch(e,"sessions-tree", "searchSession");
    });
    
    $('#btnSearch2').button().click(function(e){
	doSearch(e,"sessions-tree", "searchSession");
    });	
    
    $('#btnClear2').button().click(function(e){
	e.preventDefault();
	if ($('#searchSession').val() != "") {
	    doClear("sessions-tree","searchSession", true);
	}else{
	    $(this).attr("disabled", true);
	}
    }).attr("disabled", true);
    
    $('#showDiff').button().click(function(e){
	e.preventDefault();
	showDiff("sessions-tree",'diff1');
    });

    $("ul.dropdown-menu a").click(function(e) {
	var $id = $(this).attr("id");
	var minutes = 1000 * 60;
	var hours = minutes * 60;
	var days = hours * 24;
	var years = days * 365;
	var d = new Date();
	var t = d.getTime();	
	var filter;
	var time_options = {"1h": (1*hours),
			    "6h": (6*hours),
			    "12h": (12*hours),
			    "1d": (1*days),
			    "2d": (2*days),
			    "7d": (7*days),
			    "30d": (30*days)
			    };
	
	e.stopPropagation();
	filter = t - time_options[$id];
	d = new Date(filter);
	$("#filter").text(": > " + d.toLocaleString());
	$("a.dropdown-toggle").dropdown("toggle");
	$("ul.dropdown-menu li.active").removeClass("active");
	$(this).parent().addClass("active");
	$timefilter = $id;
	doClear("users-tree","searchMDN", true);
	doClear("sessions-tree","searchSession", true);
	return false;
    });
    $("#ingressDetails").on( 'change', 'input.ingress-chkbox', function () {
	$iselected = ($("input.ingress-chkbox:checked").length > 0)?true:false;
	if(!$(this).is(":checked")){
	    $('input#iselectAll').prop("checked",false);
	}
	toggleShowDiff();
    });    
    $("#egressDetails").on( 'change', 'input.egress-chkbox', function () {
	$eselected = ($("input.egress-chkbox:checked").length > 0)?true:false;
	if(!$(this).is(":checked")){
	    $('input#eselectAll').prop("checked",false);
	}		
	toggleShowDiff();
    });
    $("#ingressDetails").on( 'change', 'input#iselectAll', function () {
	if($(this).is(":checked")){
	    $('input.ingress-chkbox').prop("checked",true);
	    $iselected = true;
	}else{
	    $('input.ingress-chkbox').prop("checked",false);
	    $iselected = false;
	}
	toggleShowDiff();
    });
    $("#egressDetails").on( 'change', 'input#eselectAll', function () {
	if($(this).is(":checked")){
	    $('input.egress-chkbox').prop("checked",true);
	    $eselected = true;
	}else{
	    $('input.egress-chkbox').prop("checked",false);
	    $eselected = false;
	}
	toggleShowDiff();
    });
    $("input#searchMDN").val("");
    $("input#searchSession").val("");
    $("#top-frame").height(360).split({orientation:'vertical', limit:10, position:'40%'}); 
    $("#top-frame1").height(320).split({orientation:'vertical', limit:10, position:'50%'});
    $("#detail-frame").height(320).split({orientation:'vertical', limit:10, position:'50%'});   
});
