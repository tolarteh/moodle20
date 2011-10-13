document.getElementsByClassName = function(cl) {
    var retnode = [];
    var myclass = new RegExp('\\b'+cl+'\\b');
    var elem = this.getElementsByTagName('*');
    for (var i = 0; i < elem.length; i++) {
        var classes = elem[i].className;
        if (myclass.test(classes)) retnode.push(elem[i]);
    }
    return retnode;
};

function show(which){
    var old_elem = document.getElementsByClassName("active")[0];
    old_elem.style.display = "none";
    old_elem.className = "";
    var el = document.getElementById(which);
    el.style.display = "block";
    el.className = "active";
}

/*--- RESERVATION TIMER ---*/

function displayAlert(timeLeft) {
  // Change if required (time before alert)
  window.alertTime = 600; // 10 minutes

  timeLeft = parseFloat(timeLeft);
  if (timeLeft > window.alertTime) {
    timeLeft = timeLeft - window.alertTime;
    window.end = window.alertTime;
    // setTimeout requires milliseconds
    timerID1 = setTimeout("displayClock()", timeLeft*1000);
  }
  else if (timeLeft > 0) {
    alert("Faltan menos de 10 minutos para finalizar la reserva");
    window.end = timeLeft;
    displayClock();
  }
}

function displayClock() {
  if (window.end >= 0) {
    // Refresh timer every second
    timerID2 = setTimeout("displayTimer()", 1000);
    if (window.alertTime <= window.end) {
      alert("Faltan 10 minutos, cierre las aplicaciones del laboratorio");
    }
  }
  else {
    // TODO: Call the labserver to finish the experiment
    alert("La reserva ha finalizado");
    location.reload(true);
  }
}

function displayTimer() {
  var mins = Math.floor(window.end / 60);
  var secs = Math.floor(window.end - (mins*60));

  var x = ((mins<10) ? "0"+mins : mins) + ":"
        + ((secs<10) ? "0"+secs : secs);
  document.getElementById('timer').innerHTML = "Restan " + x;

  window.end = window.end - 1;
  displayClock();
}
