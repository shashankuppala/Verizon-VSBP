appRoot.factory('RequestService', ['$resource', '$q', '$routeParams',
    function ($resource, $q, $routeParams) {

        var RequestService = {
        	   /*service to get data for the MDN analysis part*/
            getData: $resource('/RA_Gui/php/classes/requestHandler.php'),
            getPcapData: $resource('/DebugGUI/Angular/PHP/PcapAnalysis.php'),
             /*service to call pcap to text script*/
            getShellData: $resource('/DebugGUI/Angular/PHP/Shell.php')                    
        };
        return RequestService;

    }]);