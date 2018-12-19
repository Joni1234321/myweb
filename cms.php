<?php    
    class CMS {
        public $servername = "localhost";
        public $username = "root";
        public $password = "";
        public $dbname = "myweb";


        function __construct ($title, $headline, $pagecontent) {
            
            //If the user is not logged in
            if ($this->get_user_id() == null) 
            {
                $lastpage = $_SERVER["REQUEST_URI"];
                //Last visited page
                if (!strpos($lastpage, "login")) {
                    setcookie("lastpage", $lastpage, 0, "/");
                    header("location:login");
                }
            }

            $this->create_webpage($title, $headline, $pagecontent);

        }

        //DIFFERENCE BETWEEN PRINT AND CREATE IS THE PARAMETER
        //============================================================//
        #region PRINT

        //Prints the form used to login with        
        public function print_login_form () {
            echo "
			<form action='logincheck.php' method='post'>
			<input type='text' name='username' placeholder='Username'> <br>
			<input type='password' name='password' placeholder='Password'> <br>
			<input type='checkbox' name='remember' value='remember'> Remember me <br>
			<button type='submit'> Submit </button>
            ";
        }

        //Prints the form to create a product
        public function print_create_product_form () {
            echo "  
            <form action='productcheck.php' method='post' enctype='multipart/form-data'>
            <input type='text' name='title' placeholder='Title'> <br>
            <input type='text' name='description' placeholder='Description'> <br>
            <input type='file' name='fileToUpload' id='fileToUpload'> <br>
            <button type='submit'> Submit </button>
            ";
        }

        //Prints the form to create a project
        public function print_create_project_form () {
            
        }

        #endregion
        //============================================================//
        #region CREATE

        private function create_webpage ($title, $headline, $pagecontent) {
                echo "<html>";

                $this->create_webpage_template($title);

                $this->create_webpage_content($pagecontent, $headline);

            echo "</html>";
        }

        //Creates the title, css and sidebar
        private function create_webpage_template ($title) {
            echo '  
            <head> 
                <title>' . $title . '</title>
                ' . $this->css . '
            </head>
            <body>
                <div class="page-sidebar"> 
            ';
                
                require('templates/sidebar.php');

                echo "
                </div>
            ";
        }

        //Creates the headline and requires the pagecontent file
        private function create_webpage_content ($pagecontent, $headline) {
            echo '<div class="page-content">';
            
            //Create headline
            echo '
            <div id="headline">
            <h2>' . $headline . '</h2>
            </div>
            ';

            require($pagecontent);
            
                echo '</div> </body>';
        }

        //Requires an array of 1 product as parameter
        public function create_product ($prod) {
            echo "
            <a href='prodview.php?id=" . $prod["id"] . "'><div class='product' id='" . $prod["id"] . "'>
                <img class='thumbnail' src='". $prod["thumbnail"] . "'>
                <p class='title'>" . $prod["title"] . " </p>
                <p class='description'>" . $prod["description"] . " </p>
            </div> </a>
            ";
        }

        //Requires an array of 1 project as parameter
        public function create_project ($proj) {
            echo "
            <a href='" . $proj["link"] . "'><div class='project project-" . $proj["st"] . "' id='" . $proj["id"] . "'>
                <div class='status'> </div>
                <p class='title'>" . $proj["title"] . " </p>
                <p class='description'>" . $proj["description"] . " </p>
            </div> </a>
            ";
        }

        //Requires an array of 1 page as parameter
        public function create_page ($page) {
            $title = htmlspecialchars($page["title"], ENT_QUOTES, 'UTF-8');
            //Makes every space to a +
            $url = str_replace (" ", "+", $title);
            echo "
            <a href='prodview.php?id=1&page=" . $url ."'>
                <div class='page'>
                    <p> " . $title . " </p>
                </div>
            </a>            
            ";
        }        

        //Requires an array of 1 project as parameter
        public function create_product_tableofcontent ($prod_id) {
            echo "    
            <div class='tableofcontent'>
            ";
            //Get the pages from the user who is currently logged in
            $pages = $this->get_product_pages($prod_id);


            //Print all the pages
            //NO PAGES ERROR
            if ($pages == null){
                echo "<p> There are 0 Pages here </p>";
            }
            else {
                foreach ($pages as $page) {
                    $this->create_page($page);
                }
            } 

            echo" </div> ";

        }        

        #endregion
        //============================================================//
        #region GET

        //Returns an array of the products the user has
        public function get_products ($user_id) {
            //Get the product ids which the user is connected to
            $sql = "SELECT prod_id FROM prod_acc WHERE user_id = ?";
            $product_ids = $this->db_query($sql, "i", array($user_id));
            
            //NO PRODUCTS CONNECTED TO USER
            if (!isset($product_ids)){
                return null;
            }
            else {
                //Make the array sql friendly
                $product_ids = implode(",", array_column($product_ids, "prod_id"));

                //Get the products data
                $products = $this->sql_query("
                SELECT id, thumbnail, title, description FROM prod  
                WHERE id IN (" . $product_ids . ")"
                );

                //Returns final products
                return $products;
            }
        }

        //Returns the state of the product   
        public function get_product_access ($prod_id){
            //Get the state of the product access
            $sql = "SELECT state FROM prod_acc WHERE user_id = ? AND prod_id = ?";
            $state = $this->db_query($sql, "ii", array($this->get_user_id(), $prod_id));

            //Return null if no access is found
            if (!isset($state)) {
                return null;
            }

            return $state[0]["state"];
        }
        
        //Returns an array of the projects the user has
        public function get_projects ($user_id) {
            //Get the product ids which the user is connected to
            $sql = "SELECT proj_id FROM proj_acc WHERE user_id = ?";
            $project_ids = $this->db_query($sql, "i", array($user_id));
        
            //NO PRODUCTS CONNECTED TO USER
            if (!isset($project_ids)){
                return null;
            }
            else {
                //Make the array sql friendly
                $project_ids = implode(",", array_column($project_ids, "proj_id"));

                //Get the projects data
                $projects = $this->sql_query("
                SELECT id, st, title, description, link FROM proj  
                WHERE id IN (" . $project_ids . ")"
                );

                //Returns final projects
                return $projects;
            }
        }

        //Returns an array of the pages the product has
        public function get_product_pages ($prod_id) {
            //Get the pages which the user is connected to
            $sql = "SELECT id, title FROM page WHERE prod_id = ?";
            $pages = $this->db_query($sql, "i", array($prod_id));
            
            //NO PAGES CONNECTED TO PRODUCT
            if (!isset($pages)){
                return null;
            }
            //GET THE PAGE
            return $pages;          
        }
        

        //Return the user id
        public function get_user_id (){
            if (!isset($_COOKIE["auth_token"]))
            {
                return null;
            }

            $token = $_COOKIE["auth_token"];
            $result = $this->db_query(
                "SELECT user_id FROM user_auth WHERE token = ? AND ip_address = ?", 
                "ss", 
                array($token, $_SERVER["REMOTE_ADDR"])
            );

            while($sqlvalue = $result){
                //USERDATA['id'] is the id of the user that logged in
                return ($sqlvalue[0]['user_id']);
            }
        }

        //Retuns the page id
        public function get_page_id ($prod_id, $page_title) {
            //Get id of the selected page
            $sql = "
            SELECT page.id
            FROM page
            INNER JOIN prod_acc
            ON page.prod_id = prod_acc.prod_id
            WHERE prod_acc.user_id = ?  AND page.prod_id = ? AND page.title = ?                     
            ";
            $user_id = $this->get_user_id();
            $result = $this->db_query ($sql, "iis", array($user_id, $prod_id, $page_title));

            while($sqlvalue = $result){
                //USERDATA['id'] is the id of the user that logged in
                return ($sqlvalue[0]['id']);
            }
        }        

        #endregion
        //============================================================//
        #region INSERT

        //Put product in db
        public function insert_product ($imagepath, $title, $description) {

            $imagepath = htmlspecialchars($imagepath, ENT_QUOTES, 'UTF-8');
            $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');


            $sql = "
                INSERT INTO prod ( thumbnail, title, description) VALUES ( ?, ?, ? )
            ";
            //PROD
            $prod_id = $this->db_query_id($sql, "sss", array ($imagepath, $title, $description));
            
            //PROD_ACC
            $this->insert_product_acc($this->get_user_id(), $prod_id);    
            
            //PAGE
            $page_id = $this->insert_page($prod_id, $title);

            //PAGE_SECT
            $sect_id = $this->insert_page_sect($page_id, $description, "bold");

        }

        //Put product access in db
        private function insert_product_acc ($user_id, $product_id) {

            $sql = "SELECT state FROM prod_acc WHERE user_id = ? AND prod_id = ?";
            $access_state = $this->db_query($sql, "ii",array ($user_id, $product_id));

            //TODO Implement view option
            //CHECK IF THE ACCES ALREADY EXISTS, IF SO THEN DONT DO ANYTHING
            if (!isset($access_state)) {
                $sql = "
                INSERT INTO prod_acc (user_id, prod_id, state) VALUES ( ?, ?, ? )
                ";

                $this->db_query_no_return($sql, "iis", array ($user_id, $product_id, "edit"));
            }
        }

        //Put page in db
        public function insert_page ($prod_id, $title) {
            //TODO WRITE THIS METHOD
        }

        
        //Put page section in db
        public function insert_page_sect ($page_id, $text, $font) {
            //Font is set to normal by default
            if ($font === null) {
                $font = "normal";    
            }
            
            $sql = "
            INSERT INTO page_sect (text, font)
            VALUES ( ?, ? )
            ";

            $sect_id = $this->db_query_id ($sql, "ss", array($text, $font));

            //PAGE_SECT_ACC
            $this->insert_page_sect_acc($page_id, $sect_id);

            return $sect_id;
            
        }        

        //Put page section access in db
        private function insert_page_sect_acc ($page_id, $sect_id) {
            
            $sql = "
            INSERT INTO page_sect_acc (page_id, sect_id)
            VALUES ( ?, ? )
            ";

            $this->db_query_no_return ($sql, "ii", array($page_id, $sect_id));

        }

        #endregion
        //============================================================//
        #region DELETE
        
        //Deletes the product access from the db
        //If it is the last access to the product then the product would also be deleted
        public function delete_product ($product_id){

            $user_id = $this->get_user_id();
            
            //If product access exists
            if ($this->exist_product_acc($product_id) == 1)
            {
                //Delete product acces from product access table
                $sql = "DELETE FROM prod_acc WHERE prod_id = ? AND user_id = ?";
                $this->db_query_no_return($sql, "ii", array($product_id, $user_id));

                //If all connections to the product has been erased, delete the product
                $sql = "SELECT id FROM prod_acc WHERE prod_id = ?";
                $remaining_connections = $this->db_query($sql, "i", array($product_id));

            
                if (!isset($remaining_connections)){
                    //Delete Prod, Page, Page_sect and Page_sect_acc by joining them together
                    $sql = "
                    DELETE page_sect, page_sect_acc, page, prod
                    FROM page_sect 
                    INNER JOIN page_sect_acc
                    ON page_sect.id = page_sect_acc.sect_id
                    INNER JOIN page
                    ON page_sect_acc.page_id = page.id
                    INNER JOIN prod 
                    ON page.prod_id = prod.id
                    WHERE prod_id = ?
                    ";
                    $this->db_query_no_return($sql, "i", array($product_id));

                    //Delete
                    return "The product was deleted";
                }

                return "The product connection was deleted";
            }
            else {
                return "No product found";
            }
        }

        
        //Deletes the page from the db
        public function delete_page ($page_id){
       
            //Delete Prod, Page, Page_sect and Page_sect_acc by joining them together
            $sql = "
            DELETE page_sect, page_sect_acc, page
            FROM page_sect 
            INNER JOIN page_sect_acc
            ON page_sect.id = page_sect_acc.sect_id
            INNER JOIN page
            ON page_sect_acc.page_id = page.id
            WHERE page_id = ?
            ";
            $this->db_query_no_return($sql, "i", array($page_id));

            //Delete
            return "The page was deleted";

        }        

        #endregion
        //============================================================//
        #region EXIST
        

        //Returns 1 or 0
        public function exist_product ($product_id) {
            $sql = "SELECT id FROM prod WHERE id = ?";
            $exist = $this->db_query($sql, "i", array($product_id));
            if (isset($exist)){
                return 1;
            }
            return 0;
        }

        //Returns 1 or 0
        public function exist_product_acc ($product_id) {
            $sql = "SELECT id FROM prod_acc WHERE prod_id = ? AND user_id = ?";
            $exist = $this->db_query($sql, "ii", array($product_id, $this->get_user_id()));

            if (isset($exist)){
                return 1;
            }
            return 0;
        }        

        #endregion
        //============================================================//
        #region REDIRECT

        //Redirects to the last page that wasn't a page with login
        public function redirect_to_last_webpage (){
            if (isset($_COOKIE["lastpage"])){
                header("location:" . urldecode($_COOKIE["lastpage"]));
            }
            else {
                header("location:profile");
            }
        }

        #endregion
        //============================================================//
        #region MYSQL QUERIES

        //UNSAFE: NOT SAFE FOR USER INPUT
        //ONLY USE FOR STATIC PURPOSES
        public function sql_query ($sql) {
            //Create connection
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            //Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query($sql);
            
            //Close it again
            $conn->close();

            return $result;
        }

        //Do MYSQL
        public function db_query ($sql, $param_type, $params){
    
            // Create connection
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            // Check connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    return;
            }
    
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param($param_type, ...$params);
            $stmt->execute();
    
            $meta = $stmt->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $result_names[] = &$row[$field->name];
            }
        
            call_user_func_array(array($stmt, 'bind_result'), $result_names);
            $returnvalue = NULL;
            while ($stmt->fetch()) {
                foreach($row as $key => $val)
                {
                    $c[$key] = $val;
                }
                $returnvalue[] = $c;
            }
    
            $stmt->close();
            $conn->close();
            
            return $returnvalue;
        }

        //DO MYSQL WITHOUT RETURNS
        public function db_query_no_return ($sql, $param_type, $params){                

            // Create connection
            $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            // Check connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    return;
            }

            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param($param_type, ...$params);
            $stmt->execute();

            $stmt->close();
            $conn->close();
            
        }

        //Returns the id of the inserted object
        public function db_query_id ($sql, $param_type, $params){                
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "myweb";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    return;
            }

            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param($param_type, ...$params);
            $stmt->execute();
            
            $id = $conn->insert_id;

            $stmt->close();
            $conn->close();

            return $id;
        }

        #endregion
        //============================================================//
        #region MYSQL SCRIPTS

        //Generates a token
        public function generate_token ($length){
            return bin2hex(openssl_random_pseudo_bytes ($length));
        }

        //Update the users hash in the DB
        public function update_hash ($user_id, $token, $ip_address){
            $sql = "SELECT * from user_auth where user_id= ? AND ip_address= ?";
            //If the auth doesn't already exsist
            
            if ($this->db_query($sql, "ss", array($user_id, $ip_address)) == NULL){
                echo "Doesn't exist";

                $sql = "
                INSERT INTO user_auth (user_id, token, ip_address, time)
                VALUES ( ? , ? , ? , DATE_ADD(CURDATE(), INTERVAL 1 MONTH));
                ";
                $params = array($user_id, $token, $ip_address);
            }
            else{
                echo "Does exist";
                $sql = "
                UPDATE user_auth 
                SET token= ?, time=DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                WHERE user_id= ? AND ip_address= ?
                ";
                $params = array($token , $user_id , $ip_address);
            }
            $this->db_query_no_return($sql, "sss", $params);
        }

        #endregion
        //============================================================//
        #region FILE MANAGEMENT

        //Checks if it is ok to upload image
        public function upload_image ($image_filetype){
            $uploadOk = 1;
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            // Check for file size 
            if ($_FILES["fileToUpload"]["size"] > MAXFILESIZE) {
                echo "Sorry, your file is too large. <br>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($image_filetype != "jpg" && $image_filetype != "png" && $image_filetype != "jpeg"
            && $image_filetype != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. <br>";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.  <br>";
            }

            if ($uploadOk == 1) 
            { return true; }
            else 
            { return false; }
        }

        #endregion
        //============================================================//


        private $css = '
        <link rel="stylesheet" href="style/home.css">
        <link rel="stylesheet" href="style/prod.css">
        <link rel="stylesheet" href="style/prod-view.css">
        <link rel="stylesheet" href="style/prof.css">
        <link rel="stylesheet" href="style/proj-view.css">
        <link rel="stylesheet" href="style/proj.css">
        <link rel="stylesheet" href="style/style.css">'
        ; 
    }

    //htmlspecialchars($imagepath, ENT_QUOTES, 'UTF-8')

?>