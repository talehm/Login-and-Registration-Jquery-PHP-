	// Login Part
  $("#login").click(function(e){ 
 		e.preventDefault();
 		var process="login";
 		var login_email=$("#login_email").val();
 		var login_pwd=$("#login_pwd").val();
		if (login_email==""){ // Check if email input is empty
			$("#registration_status").addClass("alert-warning");
			$("#registration_status").html("<strong><i class='fa fa-info'></i> Attention!</strong> Enter the Email.");
			
		}
		else if(login_pwd==""){ // Check if password input is empty
			$("#registration_status").addClass("alert-warning");
			$("#registration_status").html("<strong><i class='fa fa-info'></i> Attention!</strong> Enter the Password");
			
		}
		else{
		$.ajax({  
				type: 'POST',  
				url: 'connect.php',  
				data: {process:process,login_email:login_email, login_pwd:login_pwd}, 
				success: function(respond){ 
							if (respond=="mail_error") {	// Check the email existence in database
								$("#registration_status").addClass("alert-warning");
								$("#registration_status").html("<strong><i class='fa fa-info'></i> Attention!</strong> Email is wrong");
							}
							else if(respond=="pwd_error"){ // Password password in the database
								$("#registration_status").addClass("alert-warning");
								$("#registration_status").html("<strong><i class='fa fa-info'></i> Attention!</strong> Password is wrong");
							}
							else if (respond=="successfull") {
								window.location.href="http://www.example.az/myaccount";
							}
					}
			});
		}
 	});

End Login Part
	//Logout
	// Logout function closes the session and redirects to Login page
	$('#logout').click(function(e){
		e.preventDefault();
		
		var process="logout";
		$.ajax({
			type: 'POST',
			url:  'connect.php',
			data: {process:process},
			success: function(respond){
				if(respond=="exit"){
					window.location.href="http://www.example.az/login";
				}
			}
			
			
		});	
	});
	//Logout END
  
  // signup
  $("#signup").click(function(e){ 
 		e.preventDefault();
 				var checked=false;
            if(checkalphabetic("name") && checkalphabetic("surname") && checkemail("new_email") && checkpassword("password") ) { // Validating inputs by Regular Expression
					checked=true;
					var process="signup";
			 		var name=$("#name").val();
			 		var surname=$("#surname").val();
			 		var email=$("#new_email").val();
			 		var password=$("#password").val();
			 		
			 		$.ajax({  
							type: 'POST',  
							url: 'connect.php',  
							data: {process:process, name:name, surname:surname, email:email, password:password}, 
							beforeSend: function(){
						    	$("#loader").show(); // Show loader icon
						  	},
							success: function(respond){ 
										if (respond=="username_error") {	
			
											$("#registration_status").html("* Please try again.");
										}
										else if(respond=="mail_error"){
											
											$("#registration_status").html("<strong><i class='fa fa-info'></i> Attention!</strong> This email is already used. ");
											$("#registration_status").addClass("alert-warning");
										}
										else if (respond=="successfull") { Redirect to Myaccount part if successfull.
											window.location.href="http://www.example.az/myaccount";
										}
										else{ // Notification about errors.
											$("#registration_status").removeClass("alert-warning");
											$("#registration_status").addClass("alert-danger");
											$("#registration_status").html(respond);
										}
									},
							complete:function(data){
						    	$("#loader").hide(); // Process completed then hide icon
						    },
							error: function (xmlHttpRequest, textStatus, errorThrown) {
										 alert(errorThrown);
									}
						});               
              
            } else {
					 checked=false;               
            }
 	});
  // End Signup Part
