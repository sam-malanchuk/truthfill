<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-31967345-2"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-31967345-2');
	</script>
	<base href="<?php echo $dir_path; ?>/" />
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Truthfill - <?php echo $title; ?></title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<!--    <meta name="viewport" content="width=device-width" /> -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css?v=1.1" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <!--  Fonts and icons     -->
	<link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">

</head>
<body onload="stopLoading();">
<div class='loading-screen' id='loading-screen'><div class='loader-center'><div class='loading-screen-loader'></div></div></div>
<div class="wrapper">
	<div class="sidebar" data-background-color="white" data-active-color="info">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

    	<div class="sidebar-wrapper">
            <div class="logo">
			<?php if (!$_SESSION['loggedIn']) { ?>
                <a href="index" class="simple-text">
			<?php } else { ?>
                <a href="dashboard" class="simple-text">
			<?php } ?>
                    <i class="fab fa-gitter"></i> Truthfill
                </a>
            </div>
            <ul class="nav">
                <?php if (!$_SESSION['loggedIn']) { ?>
                <li <?php if ($thisPage=="index") echo " class=\"active\""; ?>>
                    <a href="index">
                        <i class="ti-home menu-icon"></i>
                        <p>Home</p>
                    </a>
                </li>
                <?php } ?>
                <li <?php if ($thisPage=="dashboard") echo " class=\"active\""; ?>>
                    <a href="dashboard">
                        <?php if (!$_SESSION['loggedIn']) { ?><i class="ti-lock lock"></i><?php } ?>
                        <i class="ti-panel"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li <?php if ($thisPage=="messages") echo " class=\"active\""; ?>>
                    <a href="messages">
                        <?php if (!$_SESSION['loggedIn']) { ?><i class="ti-lock lock"></i><?php } ?>
                        <i class="ti-comment-alt"></i>
                        <p>Messages</p>
                    </a>
                </li>
                <li <?php if ($thisPage=="chats") echo " class=\"active\""; ?>>
                    <a href="chats">
                        <?php if (!$_SESSION['loggedIn']) { ?><i class="ti-lock lock"></i><?php } ?>
                        <i class="far fa-comments"></i>
                        <p>Chats</p>
                    </a>
                </li>
                <li <?php if ($thisPage=="faq") echo " class=\"active\""; ?>>
                    <a href="faq">
                        <i class="far fa-question-circle"></i>
                        <p>FAQ</p>
                    </a>
                </li>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
		<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <p class="navbar-brand mobile-logo1"><?php echo $title; ?></p>
					<?php if (!$_SESSION['loggedIn']) { ?>
						<a href="index" class="navbar-brand mobile-logo2">
					<?php } else { ?>
						<a href="dashboard" class="navbar-brand mobile-logo2">
					<?php } ?>
                    <i class="fab fa-gitter"></i> Truthfill</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (!$_SESSION['loggedIn']) { ?>
                        <li>
                            <a href="login<?php if($thisPage == "feedback") { echo "?l=user/" . $_GET['u']; } ?>">
                                <i class="fa fa-sign-in-alt"></i>
								<p>Login</p>
                            </a>
                        </li>
                        <li>
                            <a href="register">
                                <i class="fa fa-user-plus"></i>
								<p>Register</p>
                            </a>
                        </li>
                        <li>
                            <a href="faq?contact=form#contact">
                                <i class="fa fa-question"></i>
								<p>Support</p>
                            </a>
                        </li>
						<?php } else { ?>
						<li class="dropdown">
                            <a class="dropdown-toggle pointer-mouse" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user"></i>
								<p><?php echo $_SESSION['fullname']; ?></p>
								<b class="caret"></b>
                            </a>
                              <ul class="dropdown-menu">
                                <li><a href="change"><i class="fas fa-key"></i> Change Password</a></li>
                                <li><a href="edit"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                                <li><a href="settings"><i class="fas fa-cogs"></i> Account Settings</a></li>
                                <li><a href="delete"><i class="fas fa-trash"></i> Delete Account</a></li>
                                <li><a href="faq?contact=form#contact"><i class="fas fa-question"></i> Support</a></li>
                              </ul>
                        </li>
                        <li>
                            <a href="login">
                                <i class="fa fa-sign-out-alt"></i>
								<p>Logout</p>
                            </a>
                        </li>
						<?php } ?>
                    </ul>

                </div>
            </div>
        </nav>

