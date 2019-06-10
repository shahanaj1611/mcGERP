<?php
	//include 'inc/header.php';
	include 'lib/buyer_info_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['buyer_info'])) {
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
								<label for="product_id" >Your Product id</label>
								<input type="text" id="product_id" name="product_id" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="buyer_name" >buyer_name</label>
								<input type="text" id="buyer_name" name="buyer_name" class="form-control" />
							</div>

							<div class="form-group">
								<label for="company_address" >company_address</label>
								<input type="text" id="company_address" name="company_address" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="purchase_quantity" >purchase_quantity</label>
								<input type="text" id="purchase_quantity" name="purchase_quantity" class="form-control"/>
							</div>

							<button type="submit" name="buyer_info" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>
 
