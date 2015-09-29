angular.module("iscrizioneDirettaMdl")
.controller("nuovoOrdineCtrl", ["$http", "$log", "$location", "ordineFct", function($http, $log, $location, ordineFct){
    
    var vm = this;
        
    vm.nuovoOrdine = function(){
        $http.get("http://localhost/app-registrazione/rest-api/Me").then(
        function(me){
            ordineFct.idCliente = me.data.id;
            console.log(ordineFct);
            $location.path("/nuovaIscrizione");
        }, function(error){
            console.log(error);
        });
    };
    
}]);