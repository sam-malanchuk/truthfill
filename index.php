<?php 
session_start();
require('logincode.php');
loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'index');


$title="Home"; $thisPage="index"; require('header.php'); 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
						<div class="card">
							<div class="header">
                                <div class="row">
                                    <div class="col-lg-6">
										<h2 class="title">Welcome to Truthfill</h2>
										<h4>A place where you can receive honest feedback from your friends, family, and coworkers.</h4>
										<p>To get started there are just three simple steps.</p>
                                    </div>
                                    <div class="col-lg-6">
									<!--<img class="img-responsive" src="/assets/img/howtovideo.jpg">-->
                                    </div>
                                </div>
                            </div>
                            <div class="content">
							</div>
						</div>
                    </div>
                    <div class="col-lg-4 col-md-5">
						<div class="card">
							<div class="header">
								<h4 class="title">Step 1</h4>
								<h3>Create an account</h3>
								<div class="icon-info text-center">
									<i class="ti-user fa-10x"></i>
								</div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5">
						<div class="card">
							<div class="header">
								<h4 class="title">Step 2</h4>
								<h3>Make your profile</h3>
								<div class="icon-info text-center">
									<i class="ti-pencil-alt fa-10x"></i>
								</div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5">
						<div class="card">
							<div class="header">
								<h4 class="title">Step 3</h4>
								<h3>Share it with friends!</h3>
								<div class="icon-info text-center">
									<i class="ti-share fa-10x"></i>
								</div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
						<div class="card">
							<div class="header">
								<h3>But what's different about Truthfill?</h3>
								<h5>We at Truthfill are one of many anonymous feedback websites, but we offer the chance to reply back to your feedback giver. That's right! You can tap on any feedback to start a chat with the user anonymously. This is why we require users to login to leave anonymous feedback.  <b>All feedback is still sent anonymously and we will never give out your identity.</b></h5>
								<div class="text-center">
									<a href="register" class="btn btn-info btn-fill btn-lg">Sign Up Now!</a>
								</div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); ?>
