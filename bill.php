<?php
	//include 'inc/header.php';
	include 'lib/bill_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['bill'])) {
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
								<label for="ordering_id" >Ordering Id</label>
								<input type="text" id="ordering_id" name="ordering_id" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="cost" >cost</label>
								<input type="text" id="cost" name="cost" class="form-control" />
							</div>

							<div class="form-group">
								<label for="paid" >paid </label>
								<input type="text" id="paid" name="paid" class="form-control" />
							</div>

							<div class="form-group">
								<label for="due" >due</label>
								<input type="text" id="due" name="due" class="form-control"/>
							</div>

							<button type="submit" name="bill" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>
 