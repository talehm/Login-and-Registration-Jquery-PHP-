<?php
php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dbHost = 'localhost'; // usually localhost
$dbUsername = 'test';
$dbPassword = 'test';
$dbDatabase = 'Dump';
$maindb = mysqli_connect($dbHost, $dbUsername, $dbPassword,$dbDatabase) or die ("Unable to connect to Database Server.");
mysqli_set_charset($maindb,"utf8");
date_default_timezone_set("Asia/Baku");

if(isset($_POST['process'])) {

$process=strip_tags(mysqli_real_escape_string($maindb,$_POST['process']));
	if($process=="signup") {
		$name=filter_var(mysqli_real_escape_string($maindb,$_POST['name']),FILTER_SANITIZE_STRING);
		$surname=filter_var(mysqli_real_escape_string($maindb,$_POST['surname']),FILTER_SANITIZE_STRING);
		$email=filter_var(mysqli_real_escape_string($maindb,$_POST['email']),FILTER_SANITIZE_EMAIL);
		$password=hash('sha256', mysqli_real_escape_string($maindb,$_POST['password']));
		// Genereate username.
    $date=date('smdh');
		$username= str_replace(" ", ".", strtolower($name)).''.$date;	
		$registration_status =sha1($email.$username);
    //Check username duplication
		$username_check="SELECT username from user where username='".$username."'";	
    //Check email duplication
		$email_check = "SELECT email FROM user WHERE email= '".$email."'";
    mysqli_query($maindb, $email_check);
		
		if(mysqli_num_rows(mysqli_query($maindb, $username_check))>0)
		{
			echo "username_error";
		}
		else {
			
			if(mysqli_num_rows(mysqli_query($maindb, $email_check))) {
				echo "mail_error";
			}
      }
			else {
				
				include_once 'class.verifyEmail.php'; // Email verification 
				//$email = 'test@example.com';
				$vmail = new verifyEmail();
				$vmail->setStreamTimeoutWait(20);
				$vmail->Debug= False;
				$vmail->Debugoutput= 'html';

				$vmail->setEmailFrom('talehmuzaffer@gmail.com');
				 //if ($vmail->check($email)) {
					$adduser="INSERT INTO user (id,name, surname ,username, email, password,registration_date,address,poct_id, phone,id_number) VALUES (NULL, '".$name."', '".$surname."', '".$username."', '".$email."','".$password."', NOW(),NULL,NULL, NULL, NULL)";

          //Registration successfull       
          if (mysqli_query($maindb, $adduser)){
						
						echo "successfull";
						session_start();
						$_SESSION['email']=$email;
						
										
					//}
				//echo 'email &lt;' . $email . '&gt; exist!';
				} elseif (verifyEmail::validate($email)) {
					echo utf8_encode( ' &lt;' . $email . '&gt; emaili mövcud deyil. Xahiş edirik təkrar yoxlayın!');
				} else {
					echo 'email &lt;' . $email . '&gt; not valid and not exist!';
				}
				
				
			}	
		}
	}
  // Logout Part
  else if($process=="logout"){ // if logout is issued, the session is destroyed.
		session_start();
		$_SESSION['email']=NULL;
		session_destroy();
		echo "exit";
		
	}
  //Logout Part End 
  // Login Part
	else if($process=="login") {
	 	//echo "ok";
	 	$login_email=filter_var(mysqli_real_escape_string($maindb,$_POST['login_email']),FILTER_SANITIZE_EMAIL);
		$login_pwd=hash('sha256', mysqli_real_escape_string($maindb,$_POST['login_pwd']));
		$acc_email_check="SELECT email from user where email='".$login_email."'"; // Query: Check email existence in database 
		$acc_pwd_check="SELECT password from user where email='".$login_email."' and password='".$login_pwd."'"; // Verif password query
		
		
		if(mysqli_num_rows(mysqli_query($maindb, $acc_email_check))>0) // Check password verification
		{
			if(mysqli_num_rows(mysqli_query($maindb, $acc_pwd_check))) {
				echo "successfull";
				session_start();
				$_SESSION['email']=$login_email;
			}
			else {
				echo "pwd_error";			
			}
		}
		else {
			echo "mail_error";		
		}
	 	
 	}
