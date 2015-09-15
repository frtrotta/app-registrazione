angular.module("SceltaGara")
.controller("gareController", ["$resource", "$log", function($resource, $log){
        
    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/gara");
    
    gara.query(function(gare){
        vm.gare = gare;
    });
    
    
}]);