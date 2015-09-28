angular.module("iscrizioneDirettaMdl", ["ngResource", "ngRoute", "SceltaGaraMdl", "adesioneSeStessoMdl", "aggiuntaDatiSquadraMdl", "riepilogoOrdineMdl", "invitoAtletiMdl"])
.factory("ordineFct", ["$http", "$filter", function($http, $filter){
        
    var ordine = {
        ricevutoIl: $filter('date')(new Date(), 'yyyy/MM/dd HH:mm:ss'),
        totale:null,
        pagato:false,
        ricevutaInviata:false,
        ricevutaInviataIl:null, 
        note:null,
        iscrizioni:[],
        clienteIndirizzoCap:null,
        clienteIndirizzoCitta:null,
        clienteIndirizzoPaese:null,
        copyByAdesionePersonale:function(){
            if(!ordine.iscrizioni[ordine.iscrizioni.length-1].squadra){
                ordine.clienteIndirizzoCap = ordine.iscrizioni[ordine.iscrizioni.length-1].adesionePersonale.indirizzoCap;
                ordine.clienteIndirizzoCitta = ordine.iscrizioni[ordine.iscrizioni.length-1].adesionePersonale.indirizzoCitta;
                ordine.clienteIndirizzoPaese = ordine.iscrizioni[ordine.iscrizioni.length-1].adesionePersonale.indirizzoPaese;
            }else{
                ordine.clienteIndirizzoCap = ordine.iscrizioni[ordine.iscrizioni.length-1].squadra.adesioniPersonali[0].indirizzoCap;
                ordine.clienteIndirizzoCitta = ordine.iscrizioni[ordine.iscrizioni.length-1].squadra.adesioniPersonali[0].indirizzoCitta;
                ordine.clienteIndirizzoPaese = ordine.iscrizioni[ordine.iscrizioni.length-1].squadra.adesioniPersonali[0].indirizzoPaese;
            }
            
        }
    };
    
    return ordine;
    
    

}]).config(['$routeProvider', function($routeProvider) {
  
    $routeProvider.when("/sceltaGara", {
        templateUrl: "../views/selezionaGara.html"
    });
    $routeProvider.when("/adesioneSeStesso", {
        templateUrl: "../views/adesioneSeStesso.html"
    });
    $routeProvider.when("/datiSquadra", {
        templateUrl: "../views/datiSquadra.html"
    });
    $routeProvider.when("/nuovaIscrizione", {
        templateUrl: "../views/nuovaIscrizione.html"
    });
    $routeProvider.when("/invitoAtleti", {
        templateUrl: "../views/invitoAtleti.html"
    });
    $routeProvider.when("/riepilogo", {
        templateUrl: "../views/riepilogoOrdine.html"
    });
    $routeProvider.otherwise({
        templateUrl: "../views/nuovoOrdine.html"
    });
}]);


