<?php 
session_start();
if(isset($_POST['submit'])){
	function generateRandomString($length = 20) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = 'a';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	$randomStringDone = generateRandomString();
	
	require('database_conn.php');
	$sqlvariable1 = $_POST['email'];
	$conn = new Connection();
	$query = 'SELECT user_id, email FROM users WHERE email = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('s', $sqlvariable1);
	$stmt->execute();
	$stmt->bind_result($user_id, $email);
	while ($stmt->fetch())
	{
	}
	$stmt->close();
	$conn->mysqli->close();
	if (isset($email)) {
		$to = $email; // this is the users Email address
		$from = 'noreply@dezignsnow.com'; // this is the site's Email address
		$subject = "Truthfill - Password Reset";
		$message = "<!DOCTYPE html><html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'><head>  <title></title>  <!--[if !mso]><!-- -->  <meta http-equiv='X-UA-Compatible' content='IE=edge'>  <!--<![endif]--><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><style type='text/css'>  #outlook a { padding: 0; }  .ReadMsgBody { width: 100%; }  .ExternalClass { width: 100%; }  .ExternalClass * { line-height:100%; }  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }  p { display: block; margin: 13px 0; }</style><!--[if !mso]><!--><style type='text/css'>  @media only screen and (max-width:480px) {    @-ms-viewport { width:320px; }    @viewport { width:320px; }  }</style><!--<![endif]--><!--[if mso]><xml>  <o:OfficeDocumentSettings>    <o:AllowPNG/>    <o:PixelsPerInch>96</o:PixelsPerInch>  </o:OfficeDocumentSettings></xml><![endif]--><!--[if lte mso 11]><style type='text/css'>  .outlook-group-fix {    width:100% !important;  }</style><![endif]--><!--[if !mso]><!-->    <link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>    <style type='text/css'>        @import url(https://fonts.googleapis.com/css?family=Cabin);    </style>  <!--<![endif]--><style type='text/css'>  @media only screen and (min-width:480px) {    .mj-column-per-100 { width:100%!important; }.mj-column-per-50 { width:50%!important; }  }</style></head><body style='background: #FFFFFF;'>    <div class='mj-container' style='background-color:#FFFFFF;'><!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='600' align='center' style='width:600px;'>        <tr>          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>      <![endif]--><table role='presentation' cellpadding='0' cellspacing='0' style='background:#FFFFFF;font-size:0px;width:100%;' border='0'><tbody><tr><td><div style='margin:0px auto;max-width:600px;'><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' align='center' border='0'><tbody><tr><td style='text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px 0px 0px 0px;'><!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0'>        <tr>          <td style='vertical-align:top;width:600px;'>      <![endif]--><div class='mj-column-per-100 outlook-group-fix' style='vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;'><table role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'><tbody><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;' align='center'><table role='presentation' cellpadding='0' cellspacing='0' style='border-collapse:collapse;border-spacing:0px;' align='center' border='0'><tbody><tr><td style='width:540px;'><img alt='' title='' height='auto' src='https://topolio.s3-eu-west-1.amazonaws.com/uploads/5b10652215523/1527801343.jpg' style='border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;' width='540'></td></tr></tbody></table></td></tr><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;' align='center'><div style='cursor:auto;color:#000000;font-family:Cabin, sans-serif;font-size:14px;line-height:22px;text-align:center;'><h2 style='color: #757575; line-height: 100%;'><span style='color:#000000;'>Welcome to the only anonymous feedback website with anonymous chatting!</span></h2></div></td></tr><tr><td style='word-wrap:break-word;font-size:0px;padding:1px 0px 1px 0px;' align='center'><table role='presentation' cellpadding='0' cellspacing='0' style='border-collapse:separate;' align='center' border='0'><tbody><tr><td style='border:none;border-radius:24px;color:#fff;cursor:auto;padding:10px 25px;' align='center' valign='middle' bgcolor='#68B3C8'><a href='http://truthfill.com/verify?a=" . $randomStringDone . "' style='text-decoration:none;background:#68B3C8;color:#fff;font-family:Arial, sans-serif;font-size:18px;font-weight:normal;line-height:120%;text-transform:none;margin:0px;' target='_blank'>Activate Account!</a></td></tr></tbody></table></td></tr><tr><td style='word-wrap:break-word;font-size:0px;'><div style='font-size:1px;line-height:50px;white-space:nowrap;'>&#xA0;</div></td></tr><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;' align='center'><table role='presentation' cellpadding='0' cellspacing='0' style='border-collapse:collapse;border-spacing:0px;' align='center' border='0'><tbody><tr><td style='width:198px;'><img alt='' title='' height='auto' src='https://topolio.s3-eu-west-1.amazonaws.com/uploads/5939020faa057/1496908846.jpg' style='border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;' width='198'></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='600' align='center' style='width:600px;'>        <tr>          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>      <![endif]--><table role='presentation' cellpadding='0' cellspacing='0' style='background:#59c4f4;font-size:0px;width:100%;' border='0'><tbody><tr><td><div style='margin:0px auto;max-width:600px;'><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' align='center' border='0'><tbody><tr><td style='text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:24px 0px 24px 0px;'><!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0'>        <tr>          <td style='vertical-align:top;width:600px;'>      <![endif]--><div class='mj-column-per-100 outlook-group-fix' style='vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;'><table role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'><tbody><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;' align='center'><div style='cursor:auto;color:#000000;font-family:Cabin, sans-serif;font-size:14px;line-height:22px;text-align:center;'><h2 style='color: #757575; line-height: 100%;'><span style='color:#ffffff;'>Now you can chat anonymously, rate your friends. like messages, and much more!</span></h2></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='600' align='center' style='width:600px;'>        <tr>          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>      <![endif]--><table role='presentation' cellpadding='0' cellspacing='0' style='background:#ffffff;font-size:0px;width:100%;' border='0'><tbody><tr><td><div style='margin:0px auto;max-width:600px;'><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' align='center' border='0'><tbody><tr><td style='text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:37px 0px 37px 0px;'><!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0'>        <tr>          <td style='vertical-align:top;width:300px;'>      <![endif]--><div class='mj-column-per-50 outlook-group-fix' style='vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;'><table role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'><tbody><tr><td style='word-wrap:break-word;font-size:0px;'><div style='font-size:1px;line-height:50px;white-space:nowrap;'>&#xA0;</div></td></tr><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;' align='left'><div style='cursor:auto;color:#000000;font-family:Cabin, sans-serif;font-size:14px;line-height:22px;text-align:left;'><h2 style='color: #757575; line-height: 100%;'><span style='color:#000000;'>Don&apos;t forget to share your personal link!</span></h2><p><span style='color:#000000;'>You can find your personal link on your Truthfill dashboard!</span></p><p></p></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td><td style='vertical-align:top;width:300px;'>      <![endif]--><div class='mj-column-per-50 outlook-group-fix' style='vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;'><table role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'><tbody><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 0px 0px 0px;' align='center'><table role='presentation' cellpadding='0' cellspacing='0' style='border-collapse:collapse;border-spacing:0px;' align='center' border='0'><tbody><tr><td style='width:300px;'><img alt='' title='' height='auto' src='https://topolio.s3-eu-west-1.amazonaws.com/uploads/5b10652215523/1527804825.jpg' style='border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;' width='300'></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='600' align='center' style='width:600px;'>        <tr>          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>      <![endif]--><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' border='0'><tbody><tr><td><div style='margin:0px auto;max-width:600px;'><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' align='center' border='0'><tbody><tr><td style='text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;'><!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0'>        <tr>          <td style='vertical-align:top;width:600px;'>      <![endif]--><div class='mj-column-per-100 outlook-group-fix' style='vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;'><table role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'><tbody><tr><td style='word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:54px;padding-left:54px;'><p style='font-size:1px;margin:0px auto;border-top:1px solid #000000;width:100%;'></p><!--[if mso | IE]><table role='presentation' align='center' border='0' cellpadding='0' cellspacing='0' style='font-size:1px;margin:0px auto;border-top:1px solid #000000;width:100%;' width='600'><tr><td style='height:0;line-height:0;'> </td></tr></table><![endif]--></td></tr><tr><td style='word-wrap:break-word;font-size:0px;'><div style='font-size:1px;line-height:29px;white-space:nowrap;'>&#xA0;</div></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='600' align='center' style='width:600px;'>        <tr>          <td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>      <![endif]--><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' border='0'><tbody><tr><td><div style='margin:0px auto;max-width:600px;'><table role='presentation' cellpadding='0' cellspacing='0' style='font-size:0px;width:100%;' align='center' border='0'><tbody><tr><td style='text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;'><!--[if mso | IE]>      <table role='presentation' border='0' cellpadding='0' cellspacing='0'>        <tr>          <td style='vertical-align:top;width:600px;'>      <![endif]--><div class='mj-column-per-100 outlook-group-fix' style='vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;'><table role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'><tbody><tr><td style='word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;' align='center'><div style='cursor:auto;color:#949494;font-family:Cabin, sans-serif;font-size:14px;line-height:22px;text-align:center;'><p><span style='font-size:11px;'>Copyright &#xA9; 2018 Truthfill.com, All rights reserved. </span></p></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]--></div></body></html>";
//		$message = '<link href="https://fonts.googleapis.com/css?family=Muli:300,400" rel="stylesheet"><style>body { font-family: "Muli", sans-serif; text-align: center; } a { font-size: 14px; border-radius: 20px; font-weight: 500; padding: 7px 18px; background: #269abc; color: #fff; text-decoration: none; transition: all 150ms linear; box-sizing: border-box; border-width: 2px; } </style><body><img src="http://dezignsnow.com/truthfill/assets/img/logo.png" width="300px"><h3>Someone has submitted a password reset request for an account associated with this email.</h3><p>If it was you, please continue by clicking the link below. If not, please disregard this email.</p><br><a href="http://dezignsnow.com/truthfill/passwordreset?a=' . $randomStringDone . '">Reset my password!</a></body>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($to,$subject,$message,$headers);

		$conn = new Connection();
		$query = "UPDATE users SET confirm_id= ? WHERE user_id= ? ";
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('si', $randomStringDone, $user_id);
		$stmt->execute();
		$stmt->close();
		$conn->mysqli->close();

		$resetAction = "sent";
	} else {
		$resetAction = "missing";
	}
}

$title="Password Reset"; $thisPage="reset"; require('header.php');
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
						<?php if (isset($resetAction)) {
							  if ($resetAction == 'missing') { ?>
							<div class="card">
								<div class="alert alert-warning">
									<span>There is no account registered with this email!</span>
								</div>
							</div>
						<?php } ?>
						<?php if ($resetAction == 'sent') { ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Link Sent!</h4>
                            </div>
                            <div class="content">
								<p>You have been sent the password reset link!</p>
								<p>Please check your email to reset your password. If you don't receive an email within a few minutes, please check your spam/junk folder.</p></br>
								<p>You can now close this page.</p>
                            </div>
                        </div>
						<?php $removeForm = true; } else { $removeForm = false; }} ?>
						<?php if (!$removeForm) { ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Password Reset</h4>
                            </div>
                            <div class="content">
                                <form action="reset" method="POST">
									<div class="form-group">
										<label>Registered Email</label>
										<input type="email" name="email" class="form-control border-input" placeholder="example@email.com" <?php if(isset($_POST['employee_name'])) { echo "value='" . $_POST['employee_name'] . "'"; } else { echo "autofocus"; }  ?> required>
									</div>
                                    <div class="text-center">
									<p><a href="register">Not Registered?</a> or just <a href="login">Login Now</a>.</p>
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Reset</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); ?>