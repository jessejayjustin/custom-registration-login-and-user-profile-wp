<?php 

get_header();

/* Template Name: reset-setting-pass */

?>

<?php

if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") { 
	$reset_key = $_GET['key'];
	$user_login = $_GET['login'];
 	$user_data = $wpdb->get_row("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = '".$reset_key."' AND user_login = '". $user_login."'");
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	if(!empty($reset_key) && !empty($user_data)) {
		if(isset($_POST['reset_pass_Sbumit'])) {
			$_POST = array_map('stripslashes_deep', $_POST);
			$npass = sanitize_text_field($_POST['npass']);
			$cfpass = sanitize_text_field($_POST['cfpass']);
			$errors = array();
			$current_user = get_user_by('id', $user_data->ID);
			// Check for errors
            if (empty($npass) && empty($cfpass)) {
              $_SESSION['errors'] = array("All fields are required");
		      $errors[] = 'All fields are required';
		    }
			if($npass != $cfpass){
				$_SESSION['errors'] = array("Password does not match");
				$errors[] = 'Password does not match';
			} 
			if(empty($errors)){
				wp_set_password( $npass, $current_user->ID );
				$_SESSION['success'] = array("Password successfully changed!");
				//echo '<h2>Password successfully changed!</h2>';
			} else {
				/* Echo Errors
				echo '<h3>Errors:</h3>';
			    foreach($errors as $error){
			        echo '<p>';
			        echo "<strong>$error</strong>";
			        echo '</p>';
			    }
			    */
			}
            
            /*
            if($empty_pw  || $passwords_dont_match) {
			 	$_SESSION['errors'] = array("Fields Shouldn't be empty.");
			} else if(wp_check_password($cpass, $user_data->user_pass, $user_data->ID )) {
               wp_set_password($npass, $user_data->ID);
               //$userid = $user_data->ID;

			//  $user = wp_signon(array('user_login' => $user_data->user_login, 'user_password' => $npass));

			   //$userdata['ID'] = $userid; //user ID
			   //$userdata['user_pass'] = $npass;
			   //wp_update_user( $userdata );

			   //$password_changed_ok = true;
			} else {
			   $_SESSION['errors'] = array("Invalid password.");
			}
			*/

			/*
		    if($npass < 6){
				$_SESSION['errors'] = array("Password must be at least 6 characters");
				$errors[] = 'Password must be at least 6 characters';
			}
		    
		    if($current_user && wp_check_password($cpass, $current_user->data->user_pass, $current_user->ID)){
			//match
			} else {
				$_SESSION['errors'] = array("Password is incorrect");
				$errors[] = 'Password is incorrect';
			}
			*/
	    } else {
	    	if(empty($npass) && empty($cfpass)) {
			 	unset($npass);
			 	unset($cfpass);
			}
	    }

	} else {
       $_SESSION['errors'] = array("Not a Valid Key");
	}
 	//else exit('Not a Valid Key.'); 
} else {
   $_SESSION['errors'] = array("Not a Valid Key");

}

?>

<div id="primary" class="content-area">
	<main id="main" class="site-main"> 
		<div class="text-center result-message"></div>
		<?php if (isset($_SESSION['errors'])): ?>
			<div style="background-color: white" class="form-errors">
			    <?php foreach($_SESSION['errors'] as $error): ?>
			        <div class="text-center alert-danger"><?php echo $error ?></div>
			    <?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if (isset($_SESSION['success'])): ?>
			<div style="background-color: white" class="form-success">
			    <?php foreach($_SESSION['success'] as $success): ?>
			        <div class="text-center alert-success"><?php echo $success ?></div>
			    <?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div id="reset_pass_banner">
	        <form id="reset_pass_form" action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']; ?>" method="post">
	       	  <div class="reset_form_npass">
	       	  	<label for="npass">New Password</label>
	       	    <input class="required" id="npass" type="password" name="npass" value="">
	       	  </div>
	       	  <div class="reset_form_cfpass">
                <label for="cfpass">Confirm Password</label>
	       	    <input class="required" id="cfpass" type="password" name="cfpass" value="">
	       	  </div>
	       	  <input type="hidden" name="reset_pass_Sbumit" value="reset_yes" >
	       	  <input type="submit" id="reset_pass_btn" name="submit" value="Update Password">
	          <div class="log_in">
	            <a id="log_in_lnk" href="<?php echo home_url(); ?>">Log in</a> </p>
	          </div>
	        </form>
	    </div>
	</main>
</div>


<?php 
get_footer();
