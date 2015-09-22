angular.module("iscrizioneDirettaMdl", ["ngResource", "ngRoute", "SceltaGaraMdl", "adesioneSeStessoMdl", "aggiuntaDatiSquadraMdl"])
.factory("ordineFct", ["$http", function($http){
        
    var ordine = {
        recevutoIl: new Date(),
        totale:null,
        pagato:false,
        ricevutaInviata:false,
        ricevutaInviataIl:null, 
        note:null,
        iscrizioni:[]
        //copyByAdesionePersonale
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
    $routeProvider.otherwise({
        templateUrl: "../views/nuovoOrdine.html"
    });
}]);


