<?php
	//include 'inc/header.php';
	include 'lib/ordering_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['ordering'])) {
			$useRegi = $user->userRegistation($_POST); 
		}

	?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>Bill </h2>
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
								<label for="product_id" >Product Id</label>
								<input type="text" id="product_id" name="product_id" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="ordering_item" >Ordering Item</label>
								<input type="text" id="ordering_item" name="ordering_item" class="form-control" />
							</div>

							<div class="form-group">
								<label for="ordering_date" >Ordering date </label>
								<input type="text" id="ordering_date" name="ordering_date" class="form-control" />
							</div>

							<div class="form-group">
								<label for="arrival_date" >Arrival date</label>
								<input type="text" id="arrival_date" name="arrival_date" class="form-control"/>
							</div>

							<button type="submit" name="ordering" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>
 