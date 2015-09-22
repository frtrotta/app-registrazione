angular.module("adesioneSeStessoMdl")
.filter("tipiRichiestaTessermaneto", function () {
    return function (gara) {
        if(gara){
            var r = [];
            var adesso = new Date();
            angular.forEach(gara.abilitazioneTipoRichiestaTesseramento, function(tipoRichiesta, index){
                if(Date.parse(tipoRichiesta.finoAl) > adesso){
                    r.push(tipoRichiesta);
                }
            });
            return r;
        }else{
            return gara;
        }
    };
});


