<?php
session_start();
if (isset($_SESSION['loggedIn'])) {
	$_SESSION = array();
	session_destroy();
	setcookie("loginhash", "", time() - 3600, "/");
}
$title="Account Deleted"; $thisPage="deleted"; require('header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Account Deleted</h4>
                            </div>
                            <div class="content">
								<p>We're sorry to see you go. Your account and all it's data has been deleted.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); ?>