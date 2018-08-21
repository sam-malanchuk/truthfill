        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul><!-- may include myself later as the creator
                        <li>
                            <a target="_blank" href="https://dezignsnow.com">
                                Dezigns Now
                            </a>
                        </li> -->
                        <li>
                            <a href="terms">
                               Terms and Conditions
                            </a>
                        </li>
                        <li>
                            <a href="privacy">
                                Privacy Policy
                            </a>
                        </li>
                    </ul>
                </nav>
				<div class="copyright pull-right">
                    &copy; Truthfill <?php echo date("Y"); ?></a>
                </div>
            </div>
        </footer>

    </div>
</div>


</body>

	
	<script>
	function loadingScreen(){
		document.body.innerHTML = "<div class='loading-screen' id='loading-screen'><div class='loader-center'><div class='loading-screen-loader'></div></div></div>" + document.body.innerHTML;
	}
	function stopLoading(){
		var item = document.getElementById('loading-screen');
		item.parentNode.removeChild(item)
	}
	</script>
	
    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins 
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>
-->
	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

	<!--  Copy for ios Plugin -->
	<script src="clipboard.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
	
</html>