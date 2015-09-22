angular.module("iscrizioneDirettaMdl")
.controller("nuovaIscrizioneCtrl", ["$http", "$log", "$location", "ordineFct", function($http, $log, $location, ordineFct){
    
    var vm = this;
    vm.datiNuovaIscrizione = null;
        
    vm.nuovaIscrizione = function(){
        
        var iscrizione = {
            eseguitaIl:new Date(),
            pettorale:vm.datiNuovaIscrizione.pettorale,
            motto:vm.datiNuovaIscrizione.motto,
            haImmagine:vm.datiNuovaIscrizione.haImmagine
        };
        ordineFct.iscrizioni.push(iscrizione);
        console.log(ordineFct);
        $location.path("/sceltaGara");
    };
    
}]);