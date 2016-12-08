appRoot.directive('timePicker',[function()
{
   var controller = ['$scope', function ($scope) {
   $scope.today = function() {
    //$scope.dt = $scope.FormatDate(new Date());
    //$scope.dtto=$scope.FormatDate(new Date());
    
    $scope.dt = new Date();
    $scope.dtto=new Date();
    
    //$scope.fromampm="AM";
    //$scope.toampm="AM";
    
 $scope.fromampm="AM";
  $scope.toampm="AM";
 $scope.fromTimeHours="01";
 $scope.fromTimeMinutes="01";
 $scope.toTimeHours="11";
 $scope.toTimeMinutes="01";     
    
   };
   
   $scope.FrampmChange=function () {
   	if($scope.fromampm=="PM")
          	if($scope.fromTimeHours==12){
            $scope.fromTimeMinutes="00";       	
          	}   
          	
          	if($scope.toampm=="PM")       	
          	if($scope.toTimeHours==12){
            $scope.toTimeMinutes="00";       	
          	}
          	
   	
   	
   	
   	}
   
   $scope.FormatDate=function(date){
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    //console.log(day);
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    //console.log(year +" "+month+" "+day);
    return [year, month, day].join('-');
   }
   
  $scope.today();

  $scope.clear = function () {
    $scope.dt = null;
  };

  $scope.toggleMin = function() {
    //$scope.minDate = $scope.minDate ? null : new Date();
  };
  $scope.toggleMin();

  $scope.open = function($event) {
    $scope.status.opened = true;
  };
  
   $scope.openToTime = function($event) {
    $scope.status.openedto = true;
  };

 /** $scope.dateOptions = {
    formatYear: 'yy',
    startingDay: 1
  };*/

  $scope.formats = ['MM/dd/yyyy', 'yyyy-MM-dd', 'dd.MM.yyyy', 'shortDate','MMMM d, y'];
  $scope.format = $scope.formats[1];

  $scope.status = {
    opened: false
  };
  
  $scope.FnHoursValidate=function(){
          	if($scope.fromTimeHours>12){
          	alert("Hours value should not be greater than 12");
          	$scope.fromTimeHours="";
          	$('#fromTimeHours').focus();
          	}
          	
          	if($scope.toTimeHours>12){
          	alert("Hours value should not be greater than 12");
          	$scope.toTimeHours="";
          	$('#toTimeHours').focus();
          	}
          	
          	if($scope.fromTimeHours.length==1 && $scope.fromTimeHours<=9)
          	$scope.fromTimeHours=0+$scope.fromTimeHours;
          	
          	if($scope.fromampm=="PM")
          	if($scope.fromTimeHours==12){
            $scope.fromTimeMinutes="00";       	
          	}   
          	
          	if($scope.toampm=="PM")       	
          	if($scope.toTimeHours==12){
            $scope.toTimeMinutes="00";       	
          	}
          	
          	if($scope.toTimeHours.length==1 && $scope.toTimeHours<=9)
          	$scope.toTimeHours=0+$scope.toTimeHours;
          	
          	};
          	
          	 $scope.FnMinutesValidate=function(){
          	if($scope.fromTimeMinutes>60){
          	alert("Minutes value should not be greater than 60");
          	$scope.fromTimeMinutes="";
          	$('#fromTimeMinutes').focus();
          	}
          	
          	if($scope.toTimeMinutes>60){
          	alert("Minutes value should not be greater than 60");
          	$scope.toTimeMinutes="";
          	$('#toTimeMinutes').focus();
          	}
          	
          		if($scope.fromampm=="PM")
          	if($scope.fromTimeHours==12){
            $scope.fromTimeMinutes="00";       	
          	}   
          	
          	if($scope.toampm=="PM")       	
          	if($scope.toTimeHours==12){
            $scope.toTimeMinutes="00";       	
          	}
          	
            if($scope.fromTimeMinutes.length==1 && $scope.fromTimeMinutes<=9)
          	$scope.fromTimeMinutes=0+$scope.fromTimeMinutes;
          	
          	if($scope.toTimeMinutes.length==1 && $scope.toTimeMinutes<=9)
          	$scope.toTimeMinutes=0+$scope.toTimeMinutes;          	
          	
          	},
          	$scope.FnFromDateChange=function(){
          		var d = new Date($scope.dt);
          		var d1 = new Date($scope.dtto);
          		console.log(d); 
          		console.log(d1); 
          		
          		if(d>d1){
    alert("Fromdate should not be greater than Todate");   
          		 $scope.dt = new Date();
    $scope.dtto=new Date();
          		}
          	}

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 2);
  $scope.events =
    [
      {
        date: tomorrow,
        status: 'full'
      },
      {
        date: afterTomorrow,
        status: 'full'
      }
    ];

  /*$scope.getDayClass = function(date, mode) {
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i=0;i<$scope.events.length;i++){
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
 }*/
 /*end for time picker*/
          
      }];
      
    return{
        restrict :"A,E,C",
        templateUrl:"DebugGUI/Angular/templates/timePicker.html",        
        controller:controller        
      }
    }]);