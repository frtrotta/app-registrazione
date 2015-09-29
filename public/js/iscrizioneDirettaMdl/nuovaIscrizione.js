angular.module("iscrizioneDirettaMdl")
.controller("nuovaIscrizioneCtrl", ["$http", "$filter", "$log", "$location", "ordineFct", function($http, $filter, $log, $location, ordineFct){
    
    var vm = this;
    vm.datiNuovaIscrizione = null;
        
    vm.nuovaIscrizione = function(){
        
        var iscrizione = {
            eseguitaIl: $filter('date')(new Date(), 'yyyy/MM/dd HH:mm:ss'),
            pettorale:vm.datiNuovaIscrizione.pettorale,
            motto:vm.datiNuovaIscrizione.motto,
            haImmagine:false //TODO da gestire: Come la faccio inserire? Dove la salvo?
        };
        ordineFct.iscrizioni.push(iscrizione);
        console.log(ordineFct);
        $location.path("/sceltaGara");
    };
    
}]);