angular.module("aggiuntaDatiSquadraMdl")
.controller("aggiuntaDatiSquadraCtrl", ["$resource", "$log", "$location", "ordineFct", function($resource, $log, $location, ordineFct){
    
    var vm = this;
    
    vm.setNomeSquadra = function(nomeSquadra){
        ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra = {
            nome:nomeSquadra,
            adesioniPersonali:[],
        };
        ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].inviti = [];
        console.log(ordineFct);
        $location.path("/adesioneSeStesso");
    };
    
}]);