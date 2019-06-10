<?php
	//include 'inc/header.php';
	include 'lib/contract_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['contract'])) {
			$useRegi = $user->userRegistation($_POST); 
		}

	?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>Contract</h2>
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
								<label for="buyer_id" > Buyer Id</label>
								<input type="text" id="buyer_id" name="buyer_id" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="product_id" >product_id</label>
								<input type="text" id="product_id" name="product_id" class="form-control" />
							</div>

							<div class="form-group">
								<label for="issue_date" >issue_date </label>
								<input type="text" id="issue_date" name="issue_date" class="form-control" />
							</div>

							<div class="form-group">
								<label for="deadline" >deadline</label>
								<input type="text" id="deadline" name="deadline" class="form-control"/>
							</div>

							<button type="submit" name="contract" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>