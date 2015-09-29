angular.module("SceltaGaraMdl")
.controller("gareCtrl", ["$resource", "$filter", "$log", "ordineFct", "$location", function($resource, $filter, $log, ordineFct, $location){
        
    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/Gara/default");
    vm.tipoGaraSelezionata = null;
    vm.garaSelezionata = null;
    
    gara.query(function(gare){
        vm.gare = gare;
    });
    
    
    vm.setGara = function(){
        ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].idGara=vm.garaSelezionata.id;
        console.log(ordineFct);
    
        switch(vm.tipoGaraSelezionata.id) {
            case 1:
                //individuale
                $location.path("/adesioneSeStesso");
                break;
            case 2:
                //a squadre
                $location.path("/datiSquadra");
                break;
            default:
                alert("Tipo gara non supportato");
        }        
    };
    
    
    
}]);