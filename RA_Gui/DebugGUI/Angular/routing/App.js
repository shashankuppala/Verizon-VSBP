// Main configuration file. Sets up AngularJS module and routes and any other config objects

var appRoot = angular.module('main', ['ngRoute','ui.bootstrap','ngResource','ui.bootstrap.datetimepicker']);     //Define the main module


appRoot.config(['$routeProvider', function ($routeProvider) {
      
      $routeProvider.
        when('/DashBoard', {
            templateUrl: 'templates/mdnanalysis.html',
            controller: 'DashboardController'
        }).
        when('/Mdnanalysis', {
            templateUrl: 'DebugGUI/Angular/templates/mdnanalysis.html',
            //controller: 'AnalysisController'
        }).
        when('/Packetanalysis', {
            templateUrl: 'templates/packetanalysis.html',
            //controller: 'BlogController'
        }).
        when('/loganalysis', {
            templateUrl: 'templates/loganalysis.html',
            //controller: 'BlogController'
        }).
        when('/Configuration', {
            templateUrl: 'templates/configuration.html',
            //controller: 'BlogController '
        }).
       
        otherwise({
            redirectTo: '/Mdnanalysis'
        });

}]).controller('RootController', ['$scope', '$route', '$routeParams', '$location', function ($scope, $route, $routeParams, $location) {
    $scope.$on('$routeChangeSuccess', function (e, current, previous) {
        $scope.activeViewPath = $location.path();
        $scope.showLoader=true;
    });

    //$scope.FilePath="/Angular/PHP";
    $scope.FilePath="/DebugGUI/Angular/PHP";
    
       
    
}]);




