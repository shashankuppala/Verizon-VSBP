/*appRoot.controller('clockController', function($scope, $interval) {
var tick = function() {
	var d = new Date();
   $scope.clock = d.toUTCString();
}
tick();
$interval(tick, 1000);
});*/


//local format
appRoot.controller('clockController', function($scope, $interval) {
var tick = function() {
	$scope.clock = Date.now();
}
tick();
$interval(tick, 1000);
});