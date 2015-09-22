angular.module("SceltaGaraMdl")
.controller("gareCtrl", ["$resource", "$log", "ordineFct", "$location", function($resource, $log, ordineFct, $location){
        
    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/Gara/default");
    vm.tipoGaraSelezionata = null;
    vm.garaSelezionata = null;
    
    gara.query(function(gare){
        vm.gare = gare;
    });
    
    vm.selezionaTipoGara = function(tipoGara){
        vm.tipoGaraSelezionata = tipoGara;
        vm.garaSelezionata = null;
    };
    
    vm.selezionaGara = function(gara){
        vm.garaSelezionata = gara;
        ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].eseguitaIl = new Date();
        ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].idGara=gara.id;
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