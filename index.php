<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>
<html>
<head>
<title>Forgot Password Recovery (Reset) using PHP and MySQL</title>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
</head>
<body>
<div style="width:700px; margin:50 auto;">

<h2>Forgot Password Recovery (Reset) using PHP and MySQL</h2>   

<?php
include('db.php');
if(isset($_POST["email"]) && (!empty($_POST["email"]))){
$email = $_POST["email"];
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$email) {
  	$error .="<p>Invalid email address please type a valid email address!</p>";
	}else{
	$sel_query = "SELECT * FROM `users` WHERE email='".$email."'";
	$results = mysqli_query($con,$sel_query);
	$row = mysqli_num_rows($results);
	if ($row==""){
		$error .= "<p>No user is registered with this email address!</p>";
		}
	}
	if($error!=""){
	echo "<div class='error'>".$error."</div>
	<br /><a href='javascript:history.go(-1)'>Go Back</a>";
		}else{
	$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
	$expDate = date("Y-m-d H:i:s",$expFormat);
	$key = md5(2418*2);
	$addKey = substr(md5(uniqid(rand(),1)),3,10);
	$key = $key . $addKey;
// Insert Temp Table
mysqli_query($con,
"INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
VALUES ('".$email."', '".$key."', '".$expDate."');");

$output='<p>Dear user,</p>';
$output.='<p>Please click on the following link to reset your password.</p>';
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p><a href="localhost/password_reset/reset-password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">localhost/password_reset/reset-password.php?key='.$key.'&email='.$email.'&action=reset</a></p>';		
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p>Please be sure to copy the entire link into your browser.
The link will expire after 1 day for security reason.</p>';
$output.='<p>If you did not request this forgotten password email, no action 
is needed, your password will not be reset. However, you may want to log into 
your account and change your security password as someone may have guessed it.</p>';   	
$output.='<p>Thanks,</p>';
$body = $output; 
$subject = "Password Recovery";

$email_to = $email;
$fromserver = "noreply@yourwebsite.com"; 
require 'vendor/autoload.php';
$mail = new PHPMailer(true);
try {
        //Server settings
        //$mail->SMTPDebug = 2;                                     // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';    // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                     // Enable SMTP authentication
        $mail->Username = 'Your Email address';                     // SMTP username
        $mail->Password = 'Your email Password';                              // SMTP password
        $mail->SMTPSecure = 'TLS';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                          // TCP port to connect to

        //Recipients
        $mail->setFrom('no-reply@digitaltradingusa.com', 'US-IT SOLUTION LLC');
        $mail->addAddress($email);                // Name is optional
        // $mail->addReplyTo('info@digitaltradingusa.com', 'Digital Trading USA');
        $mail->addAddress( $email);       // Add a recipient
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        $mail->isHTML(true);  
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($email_to);
		$mail->Send();
		echo "<script>alert('Your password has been sent in your email')</script>";
		} catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    	}

		}	

}else{
?>
<form method="post" action="" name="reset"><br /><br />
<label><strong>Enter Your Email Address:</strong></label><br /><br />
<input type="email" name="email" placeholder="username@email.com" />
<br /><br />
<input type="submit" value="Reset Password"/>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php } ?>



</div>
</body>
</html>
