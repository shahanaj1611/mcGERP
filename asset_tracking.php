<?php
	//include 'inc/header.php';
	include 'lib/asset_tracking_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['asset_tracking'])) {
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
								<label for="product_name" >Your Product Name</label>
								<input type="text" id="product_name" name="product_name" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="product_quantity" >product_quantity</label>
								<input type="text" id="product_quantity" name="product_quantity" class="form-control" />
							</div>

							<div class="form-group">
								<label for="limited_item" >limited_item </label>
								<input type="text" id="limited_item" name="limited_item" class="form-control" />
							</div>

							<div class="form-group">
								<label for="extra_item" >extra_item</label>
								<input type="text" id="extra_item" name="extra_item" class="form-control"/>
							</div>

							<button type="submit" name="asset_tracking" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>
 