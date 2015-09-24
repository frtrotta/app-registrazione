angular.module("invitoAtletiMdl")
.controller("invitoAtletiCtrl", ["$resource", "$location", "ordineFct", function($resource, $location, ordineFct){
    
    var vm = this;
    var utenti = $resource("http://localhost/app-registrazione/rest-api/Utente/:id", {id:"@id"});
    vm.utenteCorrente = null;
    vm.inviti = {};
    vm.ordineFct = ordineFct;
    vm.iscrizioneTerminata = false;
    
   
    
    utenti.get({id:(ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali[0].idUtente)}, function(utente){
        vm.utenteCorrente = utente;
    });
    
    vm.setInvitati = function(){
        
        var invitoAmico = {
            nome:vm.inviti.nome,
            cognome:vm.inviti.congome,
            email:vm.inviti.email
        };
        ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.inviti.push(invitoAmico);
        vm.inviti = null;        
        console.log(ordineFct);
        
        vm.iscrizioneTerminata = true;
        
    };
    
        
    
}]);
