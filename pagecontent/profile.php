<?php
    
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
    //=============================================================================//
    echo "<h2> Products </h2>
        <div class='viewer productgrid'>
    ";

    $sql = "SELECT id, thumbnail, title, descr FROM prod";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "
            <a href='products.php'><div class='product' id='" . $row["id"] . "'>
            <img class='thumbnail' src='". $row["thumbnail"] . "'>
            <p class='title'>" . $row["title"] . " </p>
            <p class='description'>" . $row["descr"] . " </p>
            </div> </a>";
        }
    } else {
        echo "0 results";
    }

    //=============================================================================//

    echo " </div>
    <h2>Projects: </h2>
    <div class='viewer projectgrid'>";
    
    $sql = "SELECT id, st, title, descr, link FROM proj";
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