$("div").click(function() {
    var divID = this.id;//Or $(this).attr("id");
    console.log($(this).attr("id"));
    //Your code here 
});



var currentlySelected;
function sectionEdit (sectionId) {
    //If the person clicked on a new element


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

function exitSectionEdit (){
    document.getElementById('section-edit').submit();
}