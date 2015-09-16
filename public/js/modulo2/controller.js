angular.module("registrazione")
.controller("registrazioneController", ["$resource", function($resource){
    
    var vm = this;
    var utenti = $resource("http://localhost/app-registrazione/rest-api/Utente/:id", {id:"@id"});
    vm.nuovoUtente = null;
    
    vm.registraUtente = function(){
        var utente = {
            id:null,
            password:vm.nuovoUtente.password,
            gettoneAutenticazione:null,
            gettoneAutenticazioneScadeIl:null,
            nome:vm.nuovoUtente.nome,
            cognome:vm.nuovoUtente.cognome,
            sesso:vm.nuovoUtente.sesso,
            natoIl:vm.nuovoUtente.dataDiNascita.getFullYear() + "/" + (vm.nuovoUtente.dataDiNascita.getMonth()+1) + "/" + vm.nuovoUtente.dataDiNascita.getDate(),
            email:vm.nuovoUtente.email,
            telefono:vm.nuovoUtente.telefono,
            eAmministratore:false,
            facebookId:null
        };        
        console.log(utente);
        new utenti(utente).$save().then(
            function(result){
                console.log(result);
                vm.nuovoUtente = null;
            }, function(reject){
               console.log(reject);
            }
        );

        
    };
    
}]);


