angular.module("adesioneSeStessoMdl")
.controller("adesioneSeStessoCtrl", ["$resource", "$filter", "$http", "$log", "$location", "ordineFct", function($resource,$filter, $http, $log, $location, ordineFct){
    
    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/Gara/:id/default",{id:"@id"});
    var tesseratiFitri = $resource("http://localhost/app-registrazione/rest-api/TesseratiFitri/:tessera", {tessera:"@tessera"});
    vm.indirizzi = null;
    vm.utente = null;
    vm.mostraSceltaTesseramento = null;
    vm.garaSelezionata = null;
    vm.iscrizioneTerminata = false;
    vm.codiceTessera = null;
    
    //Prendo l'utente corrente
    $http.get("http://localhost/app-registrazione/rest-api/Me").then(
        function(me){
            vm.utente = me.data;
        }, function(error){
            vm.utente = error;
        }
    );
    
    //prendo la gara selezionata
    gara.get({id:ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].idGara}, function(gara){
        vm.garaSelezionata = gara;
    });
    
    vm.tesseramentoFuturo = function(){
        if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesionePersonale.richiestaTesseramento.tesseramento = null;
        }else{
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesioniPersonali[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesioniPersonali.length].richiestaTesseramento.tesseramento = null;
        }
        console.log(ordineFct);
    };
    
    vm.selezionaTipoRichiestaTessermaneto = function (tipoRichiesta){
        var richiestaTesseramento = {
            eseguitaIl:new Date(),
            idTipoRichiestaTesseramento:tipoRichiesta,
            verificata:false
        };
        switch(tipoRichiesta.id) {
            case 1:
                //tesseramento di giornata
                var finoAl = new Date(Date.parse(vm.garaSelezionata.disputataIl));
                finoAl.setDate(finoAl.getDate() + 1);
                var tesseramentoDiGiornata = {
                    societaFitri:null,
                    idTipoTesseramento:1,
                    finoAl:finoAl,
                    matricola:null,
                    stranieroSocieta:null,
                    stranietoStato:null
                };
                if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
                    ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesionePersonale.richiestaTesseramento = richiestaTesseramento;
                    ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesionePersonale.richiestaTesseramento.tesseramento = tesseramentoDiGiornata;
                    vm.iscrizioneTerminata = true;
                }else{
                    ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali.length-1].richiestaTesseramento = richiestaTesseramento;
                    ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali.length-1].richiestaTesseramento.tesseramento = tesseramentoDiGiornata;
                    $location.path("/invitoAtleti");
                }
                break;
            case 2:
                //tesseramento Fitri
                vm.ricercaTesserati(vm.utente.nome, vm.utente.cognome, vm.utente.natoIl, vm.utente.sesso);
                if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
                    ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesionePersonale.richiestaTesseramento = richiestaTesseramento;
                }else{
                    ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali.length-1].richiestaTesseramento = richiestaTesseramento;
                }
                break;
            default:
                alert("Tipo richiesta tesseramento non supportato");
        }
        console.log(ordineFct);
    };
    
    vm.setTesseramentoByTessera = function(tessera){
        tesseratiFitri.get({tessera:tessera}, function(tesserato){
            var finoAl = new Date(Date.parse(tesserato.DATA_EMISSIONE));
            finoAl.setFullYear(finoAl.getFullYear() + 1);
            var tesseramento = {
                finoAl:finoAl,
                matricola:tesserato.TESSERA,
                societaFitri:tesserato.CODICE_SS,
                idTipoTesseramento:2
            };
            if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
                ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesionePersonale.richiestaTesseramento.tesseramento = tesseramento;
                vm.iscrizioneTerminata = true;
            }else{
                ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali.length-1].richiestaTesseramento.tesseramento = tesseramento;
                $location.path("/invitoAtleti");
            }
            console.log(ordineFct);
        });
    };
    //setIndirizzi, idUtente e categoriaFitri
    vm.setIndirizzi = function(){
        var adesionePersonale = {
            indirizzoCap:vm.indirizzi.cap,
            indirizzoCitta:vm.indirizzi.citta,
            indirizzoPaese:vm.indirizzi.paese,
            idUtente:vm.utente.id,
            categoriaFitri:getCategoriaFitriByUtente(vm.utente)
        };
        
        if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesionePersonale = adesionePersonale;
        }else{
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali = [];
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesioniPersonali.push(adesionePersonale);
        }
        ordineFct.copyByAdesionePersonale();
        vm.mostraSceltaTesseramento = true;
        console.log(ordineFct);
    };
    
    vm.ricercaTesseratiConCodiceTessera = function(codiceTessera){
        tesseratiFitri.query({TESSERA:codiceTessera}, function(tesserati){
            vm.risultatoRicercaTesserati = tesserati;
            vm.codiceTessera = null;
        });
    };
    vm.ricercaTesserati = function(nome, cognome, natoIl, sesso){
        var natoIl = $filter('date')(new Date(Date.parse(vm.utente.natoIl)), 'yyyy/MM/dd');
        tesseratiFitri.query({nome:nome, cognome:cognome, data_nascita:natoIl, sesso:sesso}, function(tesserati){
            vm.risultatoRicercaTesserati = tesserati;
        });
    };
    
}]);


function getCategoriaFitriByUtente(utente){
    
    var adesso = new Date();
    var natoIl = new Date(Date.parse(utente.natoIl));
    
    //TODO trovare la categoria quando avrò l'API per le categorie fitri
    return "S1";
}




