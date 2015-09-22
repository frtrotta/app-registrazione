angular.module("riepilogoOrdineMdl")
.controller("riepilogoOrdineCtrl", ["$resource", "$log", "$location", "ordineFct", function($resource, $log, $location, ordineFct){

    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/Gara/:id/default",{id:"@id"});
    vm.riepilogoOrdine = [];
   
    
    vm.riepilogo = function(){
        angular.forEach(ordineFct.iscrizioni, function(iscrizione, index){
            gara.get({id:iscrizione.idGara}, function(gara){
                var elemento = {
                    costoGara:getAbilitazioneTipoIscrizione(gara, iscrizione.eseguitaIl).costo,
                    costoTesseramento:
                };
            });
        });
    };
    
    
}]);

function getAbilitazioneTipoIscrizione(gara, iscrizioneEseguitaIl){
    var eseguitaIl = new Date(Date.parse(iscrizioneEseguitaIl));
    var r = null;
    console.log(eseguitaIl);
    for(var i=0; i<gara.abilitazioneTipoIscrizione.length; i++){
        if(gara.abilitazioneTipoIscrizione[i] > eseguitaIl){
            r = gara.abilitazioneTipoIscrizione[i];
            break;
        }
    }
    return r;
}