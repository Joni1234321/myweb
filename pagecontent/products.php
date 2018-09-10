<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "myweb";

    echo "  <div id='headline'>
                <h2>Products</h2>
            </div>
            <div id='content'>
            <div class='productgrid'>";
    


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT id, thumbnail, title, descr FROM products";
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
    $conn->close();

    //Closing divs
    echo "</div> </div>"
?>