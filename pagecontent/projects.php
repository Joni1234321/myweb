<?php
	echo "  
	<div id='content'><div class='viewer projectgrid'>
	";


    //Get the projects from the user who is currently logged in
    $projects = $this->get_projects($this->get_user_id());

    //No projects error message
    if ($projects == null){
        echo "<p> There are 0 projects here </p>";
    }

    //Print all the projects
    else { 
        while($row = $projects->fetch_assoc()) {
            $this->create_project($row);
        }
    }  
	//Closing divs
	echo "</div> 
	<input type='button' onclick='sortbystatus()' value='Sort by status' style='width: 100px;float:right;'></input>
	</div>
	";
?>