appRoot.controller('configController',['$scope','$http', '$rootScope',function ($scope,$http,$rootScope) {

$scope.exe=function()
  {
	$http.get("/var/www/html/DebugGUI/PHP/version.php")
	{
      success(function(response) {            	
            	$scope.vdata=response;
                
            });
            };
            
};
}]);