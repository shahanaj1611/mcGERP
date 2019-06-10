<?php
	include 'inc/header.php';
	include 'lib/User.php';
	Session::checkLogin();
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['login'])) {
			$useLogin = $user->userLogin($_POST); 
		}

	?>


			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>User login </h2>
				</div>
				<div class="panel-body">
					<div style="max-width:600px; margin:0 auto">
					<?php
						if (isset($useLogin)) {
							echo $useLogin;
						}
					?>
						<form action="" method="POST">
							<div class="form-group">
								<label for="Email" >Email Address</label>
								<input type="text" id="email" name="email" class="form-control"/>
							</div>
							<div class="form-group">
								<label for="Password" >password</label>
								<input type="password" id="password" name="password" class="form-control"/>
							</div>
							<button type="submit" name="login" class="btn btn-success">Login</button>
						</form>
					</div>
				</div>
			</div>

<div>
<div>
	<img src="pic/sales.png"><br>
<br>
Sales are activities related to selling or the number of goods or services sold in a given time period.

The seller, or the provider of the goods or <a href="sales_intro.php">see more</a></div>
<br>
<br>
<div>

<img src="pic/inve.png">
<br>
<br>
Inventory management is a discipline primarily about specifying the shape and placement of stocked goods. It is required at different locations within a facility or within many locations of a supply network to precede the regular and planned course of production and stock of materials.<a href="inve_intro.php">see more</a>
</div>
<div>
	<img src="pic/hr1.png">
<br>
<br>
Human resource management involves developing and administering programs that are designed to increase the effectiveness of an organization or business. It includes the entire spectrum of creating, managing, and cultivating the employer-employee relationship.

</div>
</div><br>
<br>
<br>


<?php include'inc/footer.php'; ?>