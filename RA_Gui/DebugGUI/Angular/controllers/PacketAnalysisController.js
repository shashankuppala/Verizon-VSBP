appRoot.controller('PacketAnalysisController', ['$scope','$http','RequestService', function ($scope,$http,RequestService) {                      
       	   $scope.dprotocol="diameter";
            $scope.ingressport=3998;
            $scope.egressport=3868;
            $scope.dt="2015-09-20";
            $scope.fromampm="AM";
            $scope.toampm="AM";
            $scope.fromTime="01:00";
            $scope.toTime="12:00";            
             $scope.inSelDownload = function () {
            	RequestService.getPcapData.get({action:"ingresscapfiles"}, function (response) {
            //console.log(response); 
            $scope.inDownloadFiles = response;           
            });  
            $('#myModal3').modal('show'); 
             };
            $scope.egSelDownload= function () {
            	RequestService.getPcapData.get({action:"ingresscapfiles"}, function (response) {
            //console.log(response); 
            $scope.enDownloadFiles = response;           
            });  
            $('#myModal4').modal('show'); 
            
            };
           
            $scope.FnDownloadIngressFile=function(file){
                console.log(file); 
                $('#myModal1').modal('hide');  
                
            
             jQuery.get('tsharkFiles/sh12.txt', function (data) {
              $('#ingressfilecontent').html(data);
                   });
            
            };
            
            
            $scope.FnDownloadEgressFile=function(file){
                console.log(file); 
                $('#myModal2').modal('hide');  
            
             jQuery.get('tsharkFiles/sh12.txt', function (data) {
        $('#egressfilecontent').html(data);
    });
            
            };  
            
             $scope.FnGetRecordsByTime = function() {

	            if ($scope.dt!==undefined && $scope.fromTimeHours!==undefined && $scope.fromTimeMinutes!==undefined && $scope.fromampm!==undefined && $scope.dtto!==undefined && $scope.toTimeHours!==undefined && $scope.toTimeMinutes!==undefined && $scope.toampm!==undefined) 
	           {
	                if ($scope.fromampm == "PM") {
	                    $scope.fromDate = $scope.FormatDate($scope.dt);
	                    $scope.time = parseInt($scope.fromTimeHours) + 12;
	                    $scope.finalFromTime=($scope.fromDate +"T"+$scope.time+":"+ $scope.fromTimeMinutes+":00.00");
	                    $scope.epoch1 = ($scope.fromDate + "T" + $scope.time + ":" + $scope.fromTimeMinutes);
	                } else {
	                    $scope.fromDate = $scope.FormatDate($scope.dt);
	                    $scope.finalFromTime = ($scope.fromDate + "T" + $scope.fromTimeHours + ":" + $scope.fromTimeMinutes+":00.00");
	                    $scope.epoch1 = ($scope.fromDate + "T" + $scope.fromTimeHours + ":" + $scope.fromTimeMinutes);
	                }
	                if ($scope.toampm == "PM") {
	                    $scope.toDate = $scope.FormatDate($scope.dtto);
	                    $scope.time = parseInt($scope.toTimeHours) + 12;
	                    $scope.finalToTime=$scope.toDate +"T"+$scope.time+":"+ $scope.toTimeMinutes+":00.00"; 
	                    $scope.epoch2 = ($scope.toDate + "T" + $scope.time + ":" + $scope.toTimeMinutes);
	                } else {
	                    $scope.toDate = $scope.FormatDate($scope.dtto);
	                    $scope.finalToTime=$scope.toDate +"T"+$scope.toTimeHours+":"+ $scope.toTimeMinutes+":00.00"; 
	                    $scope.epoch2 = ($scope.toDate + "T" + $scope.toTimeHours + ":" + $scope.toTimeMinutes);
	                }
	                var myDate = new Date($scope.epoch1); // Your timezone!
	                $scope.fromepoch = myDate.getTime() / 1000.0;
	                //console.log($scope.fromepoch);
	                var myDate1 = new Date($scope.epoch2); // Your timezone!
	                $scope.toepoch = myDate1.getTime() / 1000.0;
	                //console.log($scope.fromepoch);
	                //console.log($scope.toepoch);
	                //console.log($scope.finalFromTime);
	                //console.log($scope.finalToTime);
	                //console.log($scope.dprotocol);
	                //console.log($scope.cmdCode);
	                //console.log($scope.ingressport);
	                //console.log($scope.egressport);
	                //console.log($scope.SessionId);
	                	RequestService.getData.get({
	                    obj: 'pcapStats',
	                    option: '-p',
	                    from1:$scope.finalFromTime,
	                    to1:$scope.finalToTime,
	                    from2: $scope.fromepoch,
	                    to2: $scope.toepoch,
	                    sessionid:$scope.sessionid,
	                    cmdcode:$scope.cmdcode,
	                    ingressport:$scope.ingressport,
	                    egressport:$scope.egressport,
	                    
	                }, function(response) {
	                    $scope.SessionsData = response;
	                });
	              
	            } //if condition ending  
	            else {
	               alert("please enter all the fields");
	            }
	        }
       }]);
