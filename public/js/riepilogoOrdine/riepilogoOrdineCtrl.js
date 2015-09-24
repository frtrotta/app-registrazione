angular.module("riepilogoOrdineMdl")
.controller("riepilogoOrdineCtrl", ["$resource", "$log", "$location", "ordineFct", function($resource, $log, $location, ordineFct){

    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/Gara/:id/default",{id:"@id"});
    vm.riepilogoOrdine = [];
    
    vm.riepilogo = function(){
        angular.forEach(ordineFct.iscrizioni, function(iscrizione, index){
            gara.get({id:iscrizione.idGara}, function(gara){
                var elemento = {
                    costoGara:getCostoGara(gara, iscrizione.eseguitaIl),
                    costoTesseramenti:vm.getCostoTesseramento(gara, iscrizione)
                };
                vm.riepilogoOrdine.push(elemento);
            });
        });
        vm.setCostoTotale();
    }();
    
    vm.setCostoTotale = function(){
        var totale = null;
        angular.forEach(vm.riepilogoOrdine, function(iscrizione, index){
            totale += iscrizione.costoGara;
            totale += iscrizione.costoTesseramenti;
        });
        ordineFct.totale = totale;
        console.log(ordineFct);
    };
    
    vm.getCostoTesseramento = function(gara, iscrizione){
        var r = null;
        
        if(iscrizione.squadra){
            angular.forEach(iscrizione.squadra.adesioniPersonali, function(adesionePersonale, index){
                var idTipoTesseramento = adesionePersonale.richiestaTesseramento.tesseramento.idTipoTesseramento;
                angular.forEach(gara.abilitazioneTipoRichiestaTesseramento, function(abilitazione, index){
                    if(abilitazione.tipoRichiestaTesseramento.id === idTipoTesseramento){
                        r += abilitazione.costo;
                    }
                });
            });
        }else{
            var idTipoTesseramento = iscrizione.adesionePersonale.richiestaTesseramento.tesseramento.idTipoTesseramento;
            angular.forEach(gara.abilitazioneTipoRichiestaTesseramento, function(abilitazione, index){
                if(abilitazione.tipoRichiestaTesseramento.id === idTipoTesseramento){
                    r = abilitazione.costo;
                }
            });
            
        }
        
        return r;
    };
    
}]);

function getCostoGara(gara, iscrizioneEseguitaIl){
    var eseguitaIl = new Date(Date.parse(iscrizioneEseguitaIl));
    var r = null;
    
    for(var i=0; i<gara.abilitazioneTipoIscrizione.length; i++){
        if(new Date(Date.parse(gara.abilitazioneTipoIscrizione[i].finoAl)) > eseguitaIl){
            r = gara.abilitazioneTipoIscrizione[i];
            break;
        }
    }
    return r.costo;
}