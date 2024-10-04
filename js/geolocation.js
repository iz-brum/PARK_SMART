function obterLocalizacao(callbackSucesso, callbackErro) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(callbackSucesso, callbackErro);
    } else {
        alert("Geolocalização não é suportada por este navegador.");
    }
}
