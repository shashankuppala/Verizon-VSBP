	appRoot.controller('AnalysisController', ['$scope','$rootScope', '$http','$interval', 'RequestService', function($scope,$rootScope, $http,$interval, RequestService) {	
     		
       $interval.cancel($rootScope.stopTime);// $interval scope from the dashboard.js
	    $scope.date = new Date();
	    $scope.buttontext = "Show Differences for all records";
	    $scope.IngressSelectedValues = '';
	    $scope.ingressarray = [];
	    $scope.egressarray = [];
	    $scope.ingressvaluesstring = "";
	    $scope.egressvaluesstring = "";
	    //$scope.showLoader=false;
	    //opening the popup box when we click compare in mdnanalysis page
	    $scope.opendailog = function() {
	            //console.log($scope.ingressarray);
	            //console.log($scope.egressarray);
	            if ($scope.ingressarray.length == 0 && $scope.egressarray.length == 0) {
	                RequestService.getData.get({
	                    obj: "showDiff",
	                    sessionID: $scope.CurrentSessionId,
	                    option: "-d"
	                }, function(response) {
	                    console.log(response);
	                    if (response.status == "success") {
	                        $('#diff1').empty();
	                        $('#diff1').html(response.record);
	                        $('.nav-tabs a').click(function(e) {
	                            e.preventDefault();
	                            $(this).tab('show');
	                        });
	                        // $( "#diff1").tabs();
	                        $('#myModal').modal('show');
	                    } else {
	                        alert(response.message);
	                    }
	                });
	                //when only ingress table is checked
	            } else if ($scope.ingressarray.length != 0 && $scope.egressarray.length == 0) {
	                $.each($scope.ingressarray, function(index, item) {
	                    $scope.ingressvaluesstring += item + ",";
	                });
	                $scope.ingressvaluesstring = $scope.ingressvaluesstring.slice(0, -1);

	                RequestService.getData.get({
	                    obj: "showDiff",
	                    sessionID: $scope.CurrentSessionId,
	                    option: "-d",
	                    ingressFiles: $scope.ingressvaluesstring
	                }, function(response) {
	                    console.log(response);
	                    if (response.status == "success") {
	                        $('#diff1').empty();
	                        $('#diff1').html(response.record);
	                        $('.nav-tabs a').click(function(e) {
	                             e.preventDefault();
	                            $(this).tab('show');
	                        });
	                        //$( "#diff1" ).tabs();               
	                        $('#myModal').modal('show');
	                    } else {
	                        alert(response.message);

	                    }
	                    $scope.ingressvaluesstring = "";
	                });

	            } //when only egress table is checked
	            else if ($scope.ingressarray.length == 0 && $scope.egressarray.length != 0) {
	                $.each($scope.egressarray, function(index, item) {
	                    $scope.egressvaluesstring += item + ",";
	                });
	                $scope.egressvaluesstring = $scope.egressvaluesstring.slice(0, -1);
	                RequestService.getData.get({
	                    obj: "showDiff",
	                    sessionID: $scope.CurrentSessionId,
	                    option: "-d",
	                    egressFiles: $scope.egressvaluesstring
	                }, function(response) {
	                    console.log(response);
	                    if (response.status == "success") {
	                        $('#diff1').empty();
	                        $('#diff1').html(response.record);
	                        $('.nav-tabs a').click(function(e) {
	                            e.preventDefault();
	                            $(this).tab('show');
	                        });
	                        //$( "#diff1").tabs();
	                        //$( "#diff1" ).tabs();               
	                        $('#myModal').modal('show');
	                    } else {
	                        alert(response.message);

	                    }
	                    $scope.egressvaluesstring = "";
	                });
	            } //when both the tables are been checked
	            else if ($scope.ingressarray.length != 0 && $scope.egressarray.length != 0) {
	                $.each($scope.ingressarray, function(index, item) {
	                    $scope.ingressvaluesstring += item + ",";
	                });
	                $scope.ingressvaluesstring = $scope.ingressvaluesstring.slice(0, -1);
	                $.each($scope.egressarray, function(index, item) {
	                    $scope.egressvaluesstring += item + ",";
	                });
	                $scope.egressvaluesstring = $scope.egressvaluesstring.slice(0, -1);
	                RequestService.getData.get({
	                    obj: "showDiff",
	                    sessionID: $scope.CurrentSessionId,
	                    option: "-d",
	                    ingressFiles: $scope.ingressvaluesstring,
	                    egressFiles: $scope.egressvaluesstring
	                }, function(response) {
	                    console.log(response);
	                    if (response.status == "success") {
	                        $('#diff1').empty();
	                        $('#diff1').html(response.record);
	                        $('.nav-tabs a').click(function(e) {
	                            e.preventDefault();
	                            $(this).tab('show');
	                        });
	                        // $( "#diff1" ).tabs();
	                        // $( "#diff1 li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );               
	                        $('#myModal').modal('show');
	                    } else {
	                        alert(response.message);
	                    }
	                    $scope.egressvaluesstring = "";
	                    $scope.ingressvaluesstring = "";
	                });
	            }

	        },
	        //show Diff Button text functionality
	        $scope.FnCheckSelectedData = function() {
	            if ($scope.ingressarray.length == 0 && $scope.egressarray.length == 0) {
	                $scope.buttontext = "Show Differences for All Records";
	            } else if ($scope.ingressarray.length != 0 && $scope.egressarray.length == 0) {
	                $scope.buttontext = "Show Selected Ingress Records";
	            } else if ($scope.ingressarray.length == 0 && $scope.egressarray.length != 0) {
	                $scope.buttontext = "Show Selected Egress Records";
	            } else if ($scope.ingressarray.length != 0 && $scope.egressarray.length != 0) {
	                $scope.buttontext = "Show Differences for Selected Records";
	            }
	        },
	        $scope.FnIngressChecked = function(value, sender) {	            
	            if (value == "All") {
	                $scope.ingressarray = [];
	                if ($('#selectAllIn').is(":checked")) {
	                    $.each($scope.IngressSessionDetails, function(index, item) {
	                        $scope.ingressarray.push(item.pcap);
	                    });
	                } else {
	                    $scope.ingressarray = [];
	                }
	            } else {
	                $scope.ingressarray = [];
	                $("#ingressBody input").each(function() {
	                    if ($(this).is(":checked")) {
	                        $scope.ingressarray.push($(this).attr('id'));
	                    }
	                });
	                //console.log($scope.ingressarray);
	            }
	            $scope.FnCheckSelectedData();
	        },

	        $scope.FnEgressChecked = function(value) {

	            if (value == "All") {
	                $scope.egressarray = [];
	                if ($('#selectAllEg').is(":checked")) {
	                    $.each($scope.EgressSessionDetails, function(index, item) {
	                        $scope.egressarray.push(item.pcap);
	                    });
	                } else {
	                    $scope.egressarray = [];
	                }
	            } else {
	                $scope.egressarray = [];
	                $("#egressBody input").each(function() {
	                    if ($(this).is(":checked")) {
	                        $scope.egressarray.push($(this).attr('id'));
	                    }
	                });
	                //console.log($scope.egressarray);
	            }
	            $scope.FnCheckSelectedData();
	        },
	        $scope.FnFsessionKeyPress=function($event){
	        console.log($event.key);
	        },
	        $scope.GetNewSessions = function() {
               /*MDN search*/
                if($('#myCheckbox').is(':checked'))
                {
                	if ($scope.fromepoch == undefined || $scope.toepoch == undefined) {
	                console.log("without timeStamp sessions");
	                //console.log($scope.finalFromtime);
	                //console.log($scope.finalTotime);
	                console.log("imsi without ts");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-u',
	                    filter: $scope.MDN
	                }, function(data) {
	                    $scope.SessionsData = data;
	                });
	            } else {
	                console.log("timestamp sessions");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-u',
	                    from: $scope.fromepoch,
	                    to: $scope.toepoch,
	                    filter: $scope.MDN
	                }, function(data) {
	                    $scope.SessionsData = data;

	                });
	            }
                	}else{
                		console.log($scope.fromepoch +" "+$scope.toepoch);
	            //if condition to search MDN without time stamp and without time stamp.
	            if ($scope.fromepoch == undefined || $scope.toepoch == undefined) {
	                console.log("without timeStamp sessions");
	                
	                //console.log($scope.finalFromtime);
	                //console.log($scope.finalTotime);
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-m',
	                    filter: $scope.MDN
	                }, function(data) {
	                    $scope.SessionsData = data;
	                });
	            } else {
	                console.log("timestamp sessions");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-m',
	                    from: $scope.fromepoch,
	                    to: $scope.toepoch,
	                    filter: $scope.MDN
	                }, function(data) {
	                    $scope.SessionsData = data;

	                });
	            }
	            }

	        },
	        $scope.ShowSessions = function(mdnId) 
	        {
	        	 $('#loader').css('display', 'block');
	        	if ($('#myCheckbox').is(':checked')) 
	        	
	        	{
	        	          if ($scope.fromepoch == undefined || $scope.toepoch == undefined) {
	                console.log("fetching without time stamp");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-s',
	                    query: "User-Name:" + mdnId,
	                    filter: $scope.SessionFilter
	                }, function(data) {
	                    $scope.SessionId = data;
	                    $('#loader').css('display', 'none');
	                });
	            } else {
	                console.log("fetching with time stamp");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-s',
	                    from: $scope.fromepoch,
	                    to: $scope.toepoch,
	                    query: "User-Name:" + mdnId,
	                    filter: $scope.SessionFilter
	                }, function(data) {
	                    $scope.SessionId = data;
	                    $('#loader').css('display', 'none');
	                });
	            }
	        	}
	        	else{
	        		
	        		    
                   if ($scope.fromepoch == undefined || $scope.toepoch == undefined) {
	                console.log("fetching without time stamp");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-s',
	                    query: "Subscription-Id-Data:" + mdnId,
	                    filter: $scope.SessionFilter
	                }, function(data) {
	                    $scope.SessionId = data;
	                    $('#loader').css('display', 'none');
	                });
	            } else {
	                console.log("fetching with time stamp");
	                RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-s',
	                    from: $scope.fromepoch,
	                    to: $scope.toepoch,
	                    query: "Subscription-Id-Data:" + mdnId,
	                    filter: $scope.SessionFilter
	                }, function(data) {
	                    $scope.SessionId = data;
	                    $('#loader').css('display', 'none');
	                });
	            }	        	        		        	
	        	}
	        },
	        
	        
	        
	        $scope.SearchSessions = function () {
	        	 
	               RequestService.getData.query({
	                    obj: 'showSessions',
	                    option: '-s',
	                    filter: $scope.search.id
	                }, function(data) {
	                    $scope.SessionId = data;
	                });
                      
	        
	        }
	        $scope.FnGetSessionReport = function(sessionId) {
	            $scope.CurrentSessionId = sessionId;
	            $('#loader').css('display', 'block');
	            $('#ingressBody tr').empty();
	            $('#egressBody tr').empty();
	            //Get session details--ingress
	           /* RequestService.getData.get({
	                obj: 'showSessionsDetails',
	                option: '-r',
	                query: "Session-Id:" + sessionId,
	                filter: 'ingress'
	            }, function(response) {
	            	
	                if (response.data.length > 0) {
	                    $scope.IngressSessionDetails = response.data;
	                } else {
	                    $scope.IngressSessionDetails = [];
	                }
	            });*/
	            //Get session details-- ingress


	            //Get session details--egress
	           /* RequestService.getData.get({
	                obj: 'showSessionsDetails',
	                option: '-r',
	                query: "Session-Id:" + sessionId,
	                filter: 'egress'
	            }, function(response) {
	                if (response.data.length > 0) {
	                    $scope.EgressSessionDetails = response.data;
	                } else {
	                    $scope.EgressSessionDetails = [];
	                }
	            });*/

	            /*TO GET THE SESSION DETAILS IN THE TABLES*/
	            $http.get("/RA_Gui/php/classes/requestHandler.php", {
	                params: {
	                    obj: 'findDiff',
	                    option: '-f',
	                    query: "Session-Id:" + sessionId
	                }
	            }).
	            then(function(response) {
	            	//console.log("into the response");
	            	// console.log(response.data.status);
	            	if(response.data.status=="success")
	            	{
                  $.each(response.data.data,function(index,item){                  	                 
                  	item["pcap"]=item["name"]+"_"+item["pcap"]+"-"+item["frame"]+".txt";                  		
                  	})	     ;    
                  	$.each(response.data.dataegress,function(index,item){                  	                 
                  	item["pcap"]=item["name"]+"_"+item["pcap"]+"-"+item["frame"]+".txt";                  		
                  	})	     ;    	
	            	//console.log(response.data.data);
	            	$scope.IngressSessionDetails =response.data.data;
                  $scope.EgressSessionDetails =response.data.dataegress;
            	   //console.log(response.data);
                  	
	            	//console.log(response.data.outbuf);
	                var splitData = response.data.outbuf.split('@');
	                console.log(splitData);
	                var inoutarray = splitData[0].replace('"', '').split(' ');
	                console.log(inoutarray);
	                $scope.name=inoutarray[0];
	                $scope.recordpgw=inoutarray[3];
	                var inoutarray1=splitData[1].split(' ');
	                 $scope.name1=inoutarray1[1];
	                 $scope.recordccf=inoutarray1[4];
	                var inoutarray2=splitData[2];
	                console.log(inoutarray2);
	                //$scope.inputpgw=inoutarray2[4];
	                var inoutarray3=splitData[3].split(' ');
	                $scope.inputpgw=inoutarray3[4];
	                console.log(inoutarray3);
	                var inoutarray4=splitData[4].split(' ');
	                $scope.outputpgw=inoutarray4[3];
	                console.log(inoutarray4);
	                var inoutarray5=splitData[5].split(' ');
	                console.log(inoutarray5);
	                $scope.sdc1=inoutarray5[3];
	                var inoutarray6=splitData[6].split(' ');
	                console.log(inoutarray6);
	                $scope.inputccf=inoutarray6[4];
	                var inoutarray7=splitData[7].split(' ');
	                $scope.outputccf=inoutarray7[3];
	                var inoutarray8=splitData[8].split(' ');
	                $scope.sdc2=inoutarray8[3]
	               // var inoutarrayRecordData=splitData[1].split('=');
	               // var result = inoutarrayRecordData[1].substring(3);	                
	                $scope.recordstatus = " "+inoutarray2;
	                $('#loader').css('display', 'none');
                         }//if ending	
                    else{
                      $('#loader').css('display', 'none');
                      alert(response.data.message);                
                    }     
                                     
	                
	            })
	        },
           
	        RequestService.getData.query({
	            obj: 'showSessions',
	            option: '-m'
	        }, function(data) {
	        	   $('#loader').css('display', 'block');	        	   
	            $scope.SessionsData = data;
	            $('#loader').css('display', 'none');
	        });

	    //For selecting all the Ingress text boxes   
	    $('#selectAllIn').click(function(e) {
	        var table = $(e.target).closest('table');
	        $('td input:checkbox', table).prop('checked', this.checked);
	    });
	    //For selecting all the Egress text boxes 
	    $('#selectAllEg').click(function(e) {
	        var table = $(e.target).closest('table');
	        $('td input:checkbox', table).prop('checked', this.checked);
	    });

	    $scope.printToCart = function() {
	            var innerContents = document.getElementById('showDiff-title').innerHTML;
	            var popupWinindow = window.open('', '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
	            popupWinindow.document.open();
	            popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
	            popupWinindow.document.close();
	        },
	        
	        $scope.FnGetRecordsByTime = function() {
	        	        $scope.SessionId=null;
                    $('#loader').css('display', 'block');
                    if ($scope.dt!==undefined && $scope.fromTimeHours!==undefined && $scope.fromTimeMinutes!==undefined && $scope.fromampm!==undefined && $scope.dtto!==undefined && $scope.toTimeHours!==undefined && $scope.toTimeMinutes!==undefined && $scope.toampm!==undefined)
                   {
                        if ($scope.fromampm == "PM") {
                            $scope.fromDate = $scope.FormatDate($scope.dt);
                            $scope.time = parseInt($scope.fromTimeHours) + 12;
                            $scope.epoch1 = ($scope.fromDate + "T" + $scope.time + ":" + $scope.fromTimeMinutes+":"+"00.00");
                            //console.log($scope.epoch1);
                        } else {
                            $scope.fromDate = $scope.FormatDate($scope.dt);
                           //console.log($scope.fromDate);
                            $scope.epoch1 = ($scope.fromDate + "T" + $scope.fromTimeHours + ":" + $scope.fromTimeMinutes+":"+"00.00");
                            console.log($scope.epoch1);
                        }
                        if ($scope.toampm == "PM") {
                            $scope.toDate = $scope.FormatDate($scope.dtto);
                            $scope.time = parseInt($scope.toTimeHours) + 12;                            
                            $scope.epoch2 = ($scope.toDate + "T" + $scope.time + ":" + $scope.toTimeMinutes+":"+"00.00");
                        } else {
                            $scope.toDate = $scope.FormatDate($scope.dtto);
                            $scope.epoch2 = ($scope.toDate + "T" + $scope.toTimeHours + ":" + $scope.toTimeMinutes+":"+"00.00");
                        }
                       // console.log($scope.epoch1);
                        //var myDate = new Date($scope.epoch1); // Your timezone!
                        //console.log(myDate);
                        //$scope.fromepoch = myDate.getTime() / 1000.0;
                        //console.log($scope.fromepoch);
                        //var myDate1 = new Date($scope.epoch2); // Your timezone!
                       // $scope.toepoch = myDate1.getTime() / 1000.0;
                        //console.log($scope.toepoch);
                        
                        $scope.fromepoch=$scope.epoch1;
                        console.log($scope.fromepoch);
                        $scope.toepoch=$scope.epoch2;
                        console.log($scope.toepoch);
                        
                        if($('#myCheckbox').is(':checked')){
                                RequestService.getData.query({
                            obj: 'showSessions',
                            option: '-u',
                            from: $scope.fromepoch,
                            to: $scope.toepoch
                     }, function(response) {
                            $scope.SessionsData = response;
                             $('#loader').css('display', 'none');
                            //console.log("from time stamp");
                        });
                        }
                        else {
                        RequestService.getData.query({
                            obj: 'showSessions',
                            option: '-m',
                            from: $scope.fromepoch,
                            to: $scope.toepoch
                        }, function(response) {
                            $scope.SessionsData = response;
                             $('#loader').css('display', 'none');
                            //console.log("from time stamp");
                        });
                        }//else end

                    } //if condition ending  
                    else {
                       alert("please enter all the fields");
                    }
                }
        }]);
	
	
