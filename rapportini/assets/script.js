function clearInput(){
    for(var x=0; x<document.getElementsByTagName('input').length-1; x++){
       if(document.getElementsByTagName('input')[x].type != "hidden"){
            document.getElementsByTagName('input')[x].value = "";
       }       
    }
    for(var k=0; k<document.getElementsByTagName('textarea').length; k++){
       document.getElementsByTagName('textarea')[k].value="";
    }
}

function validate(evt){
    var theEvent = evt;
    // Handle key press
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}

//invia i dati al server e pulisce i campi di input
function localStorageDataSend(localKey){
    saveToLocalStorage()
    setTimeout(function(){
        var jsonData = localStorage.getItem(localKey);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/rapportini/objects/load.php', false);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.send(jsonData);
        if(xhr.status == 200){
            localStorage.removeItem(localKey);
            clearInput();
            alert("Rapportino inserito nel database con successo!");
        }else{
            alert("Devi inserire il nome, la data e il turno");
        } 
    }, 700)
}

function checkDatabase(data, turno, reparto){
    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', "/rapportini/objects/api.php?date="+data+"&reparto="+reparto+"&turno="+turno, true);
        xhr.send();
        xhr.onload = function (e) {
            return resolve(xhr.responseText)
        }
    })   
}

function saveToLocalStorage(){
    var nome = document.getElementsByName('NOME')[0].value;
    var data = document.getElementsByName('DATA')[0].value;
    var turno = document.getElementsByName('TURNO')[0].value;
    var reparto = document.getElementsByName('reparto')[0].value
    if(nome != '' && data != '' && turno != ''){
        checkDatabase(data, turno, reparto).then(function(isPresent){
            isPresent = JSON.parse(isPresent)
            if(!isPresent['isInside']){
                var inputs = document.getElementsByTagName('input');
                var textareas = document.getElementsByTagName('textarea');
                localStorageData = {};
                localStorageData['TURNO'] = turno;
                for(var i = 0; i<inputs.length-1; i++){
                    if(inputs[i].type=='checkbox')
                        localStorageData[inputs[i].name] = inputs[i].checked;
                    else
                        localStorageData[inputs[i].name] = inputs[i].value
                }
                for(var i = 0; i<textareas.length; i++)
                    localStorageData[textareas[i].name] = textareas[i].value
                localStorage.setItem(reparto, JSON.stringify(localStorageData))
                alert("Dati salvati")
            }else{
                alert("Nel database esiste un rapportino con questa data e turno.")
                if(localStorage[reparto]){
                    document.getElementsByName('DATA')[0].value = JSON.parse(localStorage[reparto])['DATA']
                }else{
                    document.getElementsByName('DATA')[0].value = ''
                }
            }
        })
    }else{
        alert("Inserisci il nome, la data e il turno");
    }
}
