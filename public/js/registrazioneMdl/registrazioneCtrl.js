angular.module("registrazioneMdl")
.controller("registrazioneCtrl", ["$resource", "$filter", function($resource, $filter){
    
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
            natoIl:$filter('date')(vm.nuovoUtente.dataDiNascita, 'yyyy/MM/dd'),
            email:vm.nuovoUtente.email,
            telefono:vm.nuovoUtente.telefono,
            eAmministratore:false,
            facebookId:null
        };        
        console.log(utente);
        new utenti(utente).$save().then(
            function(result){
                console.log(result);
                //vm.nuovoUtente = null;
            }, function(reject){
               console.log(reject);
            }
        );

        
    };
    
}]);


