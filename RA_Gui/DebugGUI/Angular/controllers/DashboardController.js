        appRoot.controller('DashboardController', ['$scope','$http', '$rootScope','$interval',"RequestService", function ($scope,$http, $rootScope,$interval,RequestService) {
//alert($scope.FilePath);
        $scope.date = new Date();
        $scope.isUpdateStats=true;
        /****For Ingress data******/   
        $scope.IngressReport=function(){
        	$http.get($scope.FilePath+"/IngressReport.php")
            .success(function(response) {  
                      	
            	$scope.datax=response.datax;
                $scope.sum1=response.sum1;
            	$scope.sum2=response.sum2;  
            });
        };

     //Put in interval, first trigger after 10 seconds 
     
      function GetLiveStats(){
      	$('#loader').css('display','block');
          $scope.IngressReport();
          $scope.EngressReport();
          $scope.IngressAcr();
          $scope.EngressAcr();
        $scope.date = new Date();	
        $('#loader').css('display','none');
         // console.log("Refreshed"); 
             } 
        $scope.stopTime = $interval(GetLiveStats, 10000);
        $rootScope.stopTime=$scope.stopTime;// to overcome override of calls
        //console.log($scope.stopTime);
         //invoke initialy
       $scope.IngressReport();
       //this.EngressReport();/*not working*/
            /*****For Engress data******/
            $scope.EngressReport=function(){$http.get($scope.FilePath+"/EngressReport.php")
            .success(function(response) {
                //console.log(response);  
                $scope.datay=response.datay;
                $scope.sum3=response.sum3;
                $scope.sum4=response.sum4;
            });
        };
        $scope.EngressReport();   
            /*for Ingress ACR Report*/
            $scope.IngressAcr=function(){$http.get($scope.FilePath+"/IngressAcr.php")
            .success(function(response) {
                //console.log(response); 
                $scope.dataA=response.dataA;               
            });
        };
        $scope.IngressAcr();
            /*for Engress ACR Report*/
            $scope.EngressAcr=function(){$http.get($scope.FilePath+"/EngressAcr.php")
            .success(function(response) {
                //console.log(response); 
                $scope.dataB=response.data;
            });
        };
        $scope.EngressAcr();
         $scope.FnGetRecordsByTime=function()
         {  
         $interval.cancel($scope.stopTime);
         $('#loader').css('display','block');
         $('#btnLiveStats').css('display','block');
         if($scope.fromampm=="PM"){
         	$scope.fromDate=$scope.FormatDate($scope.dt);
         	$scope.time=parseInt($scope.fromTimeHours)+12;
         	$scope.finalFromTime=($scope.fromDate +"T"+$scope.time+":"+ $scope.fromTimeMinutes+":00.00");   
         	$scope.epoch1=($scope.fromDate+"T"+$scope.time+":"+$scope.fromTimeMinutes);  
         } else{
         	$scope.fromDate=$scope.FormatDate($scope.dt);
         	$scope.finalFromTime=($scope.fromDate +"T"+$scope.fromTimeHours+":"+ $scope.fromTimeMinutes+":00.00"); 
         	$scope.epoch1=($scope.fromDate+"T"+$scope.fromTimeHours+":"+$scope.fromTimeMinutes);         
         }
          if($scope.toampm=="PM"){
          	 $scope.toDate=$scope.FormatDate($scope.dtto);
          	$scope.time=parseInt($scope.toTimeHours)+12;
         	$scope.finalToTime=$scope.toDate +"T"+$scope.time+":"+ $scope.toTimeMinutes+":00.00"; 
         	$scope.epoch2=($scope.toDate+"T"+$scope.time+":"+$scope.toTimeMinutes); 
         }   else{
         	$scope.toDate=$scope.FormatDate($scope.dtto);
         $scope.finalToTime=$scope.toDate +"T"+$scope.toTimeHours+":"+ $scope.toTimeMinutes+":00.00"; 
         $scope.epoch2=($scope.toDate+"T"+$scope.toTimeHours+":"+$scope.toTimeMinutes);  
         }     	
        
         var myDate = new Date($scope.epoch1); // Your timezone!
         $scope.fromepoch = myDate.getTime()/1000.0;
         //console.log($scope.fromepoch);
         var myDate1 = new Date($scope.epoch2); // Your timezone!
         $scope.toepoch = myDate1.getTime()/1000.0;
         //console.log($scope.toepoch);
                                    
         RequestService.getData.get({ obj:'showStats', option:'-t',from1:$scope.finalFromTime,to1:$scope.finalToTime,from2:$scope.fromepoch,to2:$scope.toepoch}, function (response) {
         if(response.status="success"){
          $scope.statsDetails=response.record.split('/n');
          $scope.splitData=$scope.statsDetails[0].split(';');
          //console.log($scope.splitData[0]);
          $scope.datax=$scope.splitData[0].replace('INGRESS_TIME_FILTER.pcap','').split(',');
          //console.log($scope.datax);
          var ingressSl=$scope.splitData[1].split(',');
          var ingressAcrArrayData=[];
          ingressAcrArrayData.push(0);
          for(var i=1;i<=ingressSl.length;i++){          	
          ingressAcrArrayData.push(parseInt(ingressSl[i]));
          }
          $scope.dataA=ingressAcrArrayData;  
          
          $scope.datay=$scope.splitData[2].split(',');
          //console.log($scope.splitData[2]);
          var dataArray=$scope.splitData[3].split(','); 
          var egressAcrArrayData=[];
          egressAcrArrayData.push(0);
          for(var i=1;i<=dataArray.length;i++){          	
          egressAcrArrayData.push(parseInt(dataArray[i]));
          }
          $scope.dataB=egressAcrArrayData;     
          
           $('#loader').css('display','none');
                       }
                       else
                           {
                              alert("An error occurred while processing your request");
                              }
                                           });                                    
                                         };               
                     $scope.FnGetLiveStats=function(){
                     	$('#btnLiveStats').css('display','none');
                     	GetLiveStats();
                     $scope.stopTime = $interval(GetLiveStats, 10000);
                     }          
        }]);
