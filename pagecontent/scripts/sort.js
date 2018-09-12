const SORT_DATE = 0;
const SORT_STATUS = 1;

const PROJ_THINK = 0;
const PROJ_WORK = 1;
const PROJ_DONE = 2;

var projectsort = SORT_DATE;
function sortbystatus (){
    //BREAK IF ALREADY SORTED BY STATUS
    if (projectsort == SORT_STATUS) {
        return;
    }
    else { 
        projectsort = SORT_STATUS;
    }

    //All the projects
    var projects = [];
    projects[0] = document.getElementsByClassName("project-think");
    projects[1] = document.getElementsByClassName("project-work");
    projects[2] = document.getElementsByClassName("project-done");

    var projectgrid = document.getElementsByClassName("projectgrid")[0];

    //Create a coloumn for every project status
    var coloumns = [projects.length];
    for (var i = 0; i < projects.length; i++) {
        coloumns[i] = document.createElement("div");
        coloumns[i].classList.add("projectsubgrid");
        projectgrid.appendChild(coloumns[i]);
    }

    console.log (coloumns);

    console.log(projects);

    for (var x = 0; x < projects.length; x++) {
        for (var y = 0; y < projects[x].length; y++) {
            //THIS IS JUST HOW IT WORKS DONT CHANGE THE 0
            coloumns[x].appendChild(projects[x][0].parentNode);
        }
    }


 
}