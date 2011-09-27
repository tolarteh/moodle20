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
