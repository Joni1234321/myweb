<?php
        echo "
			<div id='content'>";

        $cookie_name = "auth_token";
        if (isset($_COOKIE[$cookie_name])) {
			$user_id = $this->get_user_id();
			if (isset($user_id)){

				//Renew token
				$token = $this->generate_token;
				
				//Update db with new hash
				$this->update_hash($user_id, $token, $_SERVER["REMOTE_ADDR"]);

				//Update cookie with new hash
				$time_stored =  time() + (60 * 60 * 24 * 30);
				setcookie("auth_token", $token, $time_stored, "/");

				//Redirect to the last page 
				$this->redirect_to_last_page ();
			}
			else {
				//ERROR MESSAGE
				print ("Sorry, there has been an error, and the login could not be completed <br>");
				print ("Please enter your credentials again if you wish to log in");
			}
        }
        else {
			print("<p> Login to continue </p> ");
        }
		

		$this->print_login_form();
		
		echo "
		</div>
		";
?>