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

function displayRemoteVideo(){
	//if(navigator.appName.indexOf('Microsoft Internet Explorer') != -1){
		//window.showModelessDialog('http://200.12.180.126/gpibcamera.html','','dialogTop:50px;dialogLeft:50px;dialogHeight:400px;dialogWidth:500px');
	//}
	
	//if(navigator.appName.indexOf('Netscape') != -1){
		window.open('http://200.12.180.126/gpibcamera.html','GPIBcamera','width=400,height=500');
	//} 
	
	//return false;
}





