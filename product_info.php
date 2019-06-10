<?php
	//include 'inc/header.php';
	include 'lib/product_info_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['product_info'])) {
			$useRegi = $user->userRegistation($_POST); 
		}

	?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>Asset tracking </h2>
				</div>
				<div class="panel-body">
					<div style="max-width:600px; margin:0 auto">
					<?php
						if (isset($useRegi)) {
							echo $useRegi;
						}
					?>
						<form action="" method="POST">

							<div class="form-group">
								<label for="product_name" >Product Name</label>
								<input type="text" id="product_name" name="product_name" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="product_quantity" >product_quantity</label>
								<input type="text" id="product_quantity" name="product_quantity" class="form-control" />
							</div>

							<div class="form-group">
								<label for="imported" >imported </label>
								<input type="text" id="imported" name="imported" class="form-control" />
							</div>

							<div class="form-group">
								<label for="raw_material" >raw_material</label>
								<input type="text" id="raw_material" name="raw_material" class="form-control"/>
							</div>

							<button type="submit" name="product_info" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>
 