angular.module("SceltaGara", ["ngResource"])
.filter("uniqueTipoGara", function () {
    return function (arrayGare) {
        if(angular.isArray(arrayGare)){
            var r = [];
            var adesso = new Date();
            var aggiungiTipoIscrizione = true;
            
            angular.forEach(arrayGare, function(gara, index){
                if(esisteTipoIscrizioneAttivo(gara)){
                    addUniqueWithId(r, gara.tipo);
                }
            });
            
            return r;
        }else{
            return arrayGare;
        }
    };
});

function esisteTipoIscrizioneAttivo(gara){
    var adesso = new Date();
    var r = false;
    
    for(var i=0; i<gara.abilitazioneTipoIscrizione.length; i++){
        var abilitazioneTipoIscrizione = gara.abilitazioneTipoIscrizione[i];
        if(Date.parse(abilitazioneTipoIscrizione.finoAl) > adesso){
            r=true;
            break;
        }
    }
    return r;
}
function addUniqueWithId(array, newElement){
    var exists = false;
    for(var i=0; i<array.length; i++){
        var currentElement = array[i];
        if(currentElement.id === newElement.id){
            exists = true;
            break;
        }
    }
    if(!exists){
        array.push(newElement);
    }
}