<?php 
if(isset($_POST['submit']) AND isset($_POST['name']) AND isset($_POST['email']) AND isset($_POST['message'])) {
	$to = "contact@truthfill.com"; 
    $from = "noreply@truthfill.com";
//    $from = $_POST['email'];
    $full_name = $_POST['name'];
    $subject = $full_name . " - Question/Issue/Suggestion - Truthfill";
    $message = $full_name . " wrote the following:" . "\n\n" . $_POST['message'] . "\n\nThis email was provided: " . $_POST['email'];

    $headers = "From:" . $from;
    mail($to,$subject,$message,$headers);
    header('Location: faq?contact=sent#contact');
}

$title="FAQ"; $thisPage="faq"; require('header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
						<div class="card">
							<div class="header">
								<h1 class="title">FAQ</h1>
							</div>
                            <div class="content">
                            </div>
                        </div>
						<div class="card">
							<div class="header pointer-mouse" data-toggle="collapse" data-target="#question1">
                                <div class="row">
                                    <div class="col-xs-10">
										<h3 class="title">Can they see my name when I responded to the chat?</h3>
                                    </div>
                                    <div class="col-xs-2 text-right">
										<h3 class="title">+</h3>
                                    </div>
                                </div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
										<p class="collapse" id="question1">No. Your name is not showed to people that you contact first. If someone leaves you a message and you start a chat on it, they see your name because they originally sent you a message. But if you send a message then they can’t see your name not on the message or the chat that they can start one it.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="card">
							<div class="header pointer-mouse" data-toggle="collapse" data-target="#question2">
                                <div class="row">
                                    <div class="col-xs-10">
										<h3 class="title">Do I have to create an account to send a message?</h3>
                                    </div>
                                    <div class="col-xs-2 text-right">
										<h3 class="title">+</h3>
                                    </div>
                                </div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
										<p class="collapse" id="question2">No. You can send a anonymous message to a friend without creating an account. If you create an account though you will be able to access more features like chats, rating users, likes ranks, adding to favorites, and much more.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="card">
							<div class="header pointer-mouse" data-toggle="collapse" data-target="#question3">
                                <div class="row">
                                    <div class="col-xs-10">
										<h3 class="title">How does the ranking system work?</h3>
                                    </div>
                                    <div class="col-xs-2 text-right">
										<h3 class="title">+</h3>
                                    </div>
                                </div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
										<p class="collapse" id="question3">When you send someone a message will logged in, it gets sent anonymously under your account. If any user likes the message, you get a like on your account. Certain amounts of likes and you rank up. Please see the ranks list on the bottom of your dashboard.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="card" id="contact">
							<div class="header pointer-mouse" data-toggle="collapse" data-target="#contact_form">
                                <div class="row">
                                    <div class="col-xs-10">
										<h3 class="title">Additional Questions? Issues? Suggestions?</h3>
                                    </div>
                                    <div class="col-xs-2 text-right">
										<h3 class="title">+</h3>
                                    </div>
                                </div>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
										<div class="collapse<?php if(isset($_GET['contact'])) { if($_GET['contact'] == ("form" || "sent")) { echo " show"; }} ?>" id="contact_form">
										<?php if($_GET['contact'] == "sent") { ?>
											<h4 class="title">Your message has been sent. You should get a response within 48 business hours.</h4>
										<?php } else { ?>
											<p>Please use the form below to submit your question.</p>
											<form action="faq" method="POST">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label>Name<span class="red-text">*</span></label>
															<input type="text" name="name" class="form-control border-input" placeholder="John Doe" required>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Email<span class="red-text">*</span></label>
															<input type="email" class="form-control border-input" name="email" placeholder="email@example.com" required>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>Question/Issue/Suggestion:<span class="red-text">*</span></label>
															<textarea rows="5" class="form-control border-input" placeholder="Please describe your question, and issue you are expericneing, or a suggestion you have." name="message" required></textarea>
														</div>
													</div>
												</div>
												<p class="red-text">* Required Fields</p>
												<button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
											</form>
										<?php } ?>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); ?>