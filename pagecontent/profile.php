<?php
    echo file_get_contents("C:/Users/Jonas/OneDrive - AARHUS TECH/Programmer/Websites/MyWeb/pagecontent/html/profile.html");
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "myweb";

    echo "  <div id='headline'>
                    <h2>Profile</h2>
            </div>
            <div id='content'>";


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT id, st, title, descr, link FROM projects";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
            echo "
            <a href='" . $row["link"] . "'><div class='project project-" . $row["st"] . "' id='" . $row["id"] . "'>
            <div class='status'> </div>
            <p class='title'>" . $row["title"] . " </p>
            <p class='description'>" . $row["descr"] . " </p>
            </div> </a>";
            }
    } else {
            echo "0 results";
    }
    $conn->close();

    //Closing divs
    echo "</div> </div>"
?>