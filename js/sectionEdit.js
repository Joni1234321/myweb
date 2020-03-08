$(".section").click(function() {
    sectionEdit($(this).attr("id"));
});

$(document).click(function () {
    if (currentlySelected != null && currentlySelected != sectionEdit){ // Exit the section edit
        //exitSectionEdit();
        console.log("Exit1: " + currentlySelected);

    }
});

var currentlySelected = null;
function sectionEdit (sectionId) {
    console.log("Section Id: " + sectionId);
    if (currentlySelected != null && currentlySelected != sectionEdit){ // Exit the section edit
        //exitSectionEdit();
        console.log("Exit2: " + currentlySelected);

    }
    //If the person clicked on a new element
    if (currentlySelected != sectionId){ // Only if its the first time the current is  clicked

        currentlySelected = sectionId;

        //FORM
        var f = document.createElement('form');
        var actionURL = 'sectionedit.php?sect_id=' + sectionId; 
        f.setAttribute('id', 'section-edit');
        f.setAttribute('action', actionURL);
        f.setAttribute('method', 'post');


        var text = document.getElementById(sectionId).childNodes[0];
        var area = document.createElement('textarea');
        area.setAttribute('name','text');
        area.innerHTML = text.innerHTML;

        f.appendChild(area);

        text.parentNode.replaceChild(f, text);
    }
}

function exitSectionEdit (){

    document.getElementById('section-edit').submit();
}