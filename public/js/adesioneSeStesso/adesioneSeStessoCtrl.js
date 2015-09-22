angular.module("adesioneSeStessoMdl")
.controller("adesioneSeStessoCtrl", ["$resource", "$filter", "$http", "$log", "$location", "ordineFct", function($resource,$filter, $http, $log, $location, ordineFct){
    
    var vm = this;
    var gara = $resource("http://localhost/app-registrazione/rest-api/Gara/:id/default",{id:"@id"});
    var tesseratiFitri = $resource("http://localhost/app-registrazione/rest-api/TesseratiFitri/:tessera", {tessera:"@tessera"});
    vm.indirizzi = null;
    vm.utente = null;
    vm.garaSelezionata = null;
    vm.iscrizioneTerminata = false;
    
    //Prendo l'utente corrente
    $http.get("http://localhost/app-registrazione/rest-api/Me").then(
        function(me){
            vm.utente = me.data;
        }, function(error){
            vm.utente = error;
        }
    );
    
    //prendo la gara selezionata
    gara.get({id:ordineFct.iscrizioni[0].idGara}, function(gara){
        vm.garaSelezionata = gara;
    });
    
    vm.selezionaTipoRichiestaTessermaneto = function (tipoRichiesta){
        switch(tipoRichiesta.id) {
            case 1:
                //tesseramento di giornata
                if(!ordineFct.iscrizioni[0].squadra){
                    ordineFct.iscrizioni[0].adesione_personale.richiestaTesseramento.tipoRichiestaTesseramento = 1;
                    var finoAl = new Date(Date.parse(vm.garaSelezionata.disputataIl));
                    ordineFct.iscrizioni[0].adesione_personale.richiestaTesseramento.tesseramento = {
                        finoAl:finoAl,
                        tipo_tesseramento:1
                    };
                }else{
                    ordineFct.iscrizioni[0].squadra.adesione_personale[0].richiestaTesseramento.tipoRichiestaTesseramento = 1;
                    var finoAl = new Date(Date.parse(vm.garaSelezionata.disputataIl));
                    ordineFct.iscrizioni[0].squadra.adesione_personale[0].richiestaTesseramento.tesseramento = {
                        finoAl:finoAl,
                        tipo_tesseramento:1
                    };
                }
                
                console.log(ordineFct);
                break;
            case 2:
                //tesseramento Fitri
                vm.ricercaTesserati(vm.utente.nome, vm.utente.cognome, vm.utente.natoIl, vm.utente.sesso);
                break;
            default:
                alert("Tipo richiesta tesseramento non supportato");
        }
    };
    
    vm.setTesseramentoByTessera = function(tessera){
        tesseratiFitri.get({tessera:tessera}, function(tesserato){
            var finoAl = new Date(Date.parse(tesserato.DATA_EMISSIONE));
            finoAl.setFullYear(finoAl.getFullYear() + 1);
            var tesseramento = {
                finoAl:finoAl,
                matricola:tesserato.TESSERA,
                societaFitri:tesserato.CODICE_SS,
                tipoTesseramento:2
            };
            if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
                ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesione_personale.richiestaTesseramento.tipoRichiestaTesseramento = 2;
                ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesione_personale.richiestaTesseramento.tesseramento = tesseramento;
                vm.iscrizioneTerminata = true;
            }else{
                ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesione_personale[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesione_personale.length-1].richiestaTesseramento.tipoRichiestaTesseramento = 2;
                ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesione_personale[ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesione_personale.length-1].richiestaTesseramento.tesseramento = tesseramento;
                //CU: invito 1/2 atleti
            }
            console.log(ordineFct);
        });
    };
    
    vm.setIndirizzi = function(){
        var adesionePersonale = {
            indirizzoCap:vm.indirizzi.cap,
                indirizzoCitta:vm.indirizzi.citta,
                indirizzoPaese:vm.indirizzi.paese,
                idUtente:vm.utente.id,
                nomeCategoriaFitri:getCategoriaFitriByUtente(vm.utente),
                richiestaTesseramento:{
                    eseguitaIl: new Date(),
                    verificata: false
                }
        };
        if(!ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra){
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].adesione_personale = adesionePersonale;
        }else{
            ordineFct.iscrizioni[ordineFct.iscrizioni.length-1].squadra.adesione_personale.push(adesionePersonale);
        }
        
        console.log(ordineFct);
    };
    
    vm.ricercaTesseratiConCodiceTessera = function(codiceTessera){
        tesseratiFitri.query({TESSERA:codiceTessera}, function(tesserati){
            console.log(tesserati);
            vm.risultatoRicercaTesserati = tesserati;
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




