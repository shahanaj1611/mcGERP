<?php
	//include 'inc/header.php';
	include 'lib/meeting_user.php';
	?>

	<?php

		$user = new User();
		if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['meeting'])) {
			$useRegi = $user->userRegistation($_POST); 
		}

	?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>Meeting </h2>
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
								<label for="buyer_id" >Buyer Id</label>
								<input type="text" id="buyer_id" name="buyer_id" class="form-control"/>
							</div>

							<div class="form-group">
								<label for="meeting_place" >meeting_place</label>
								<input type="text" id="meeting_place" name="meeting_place" class="form-control" />
							</div>

							<div class="form-group">
								<label for="date" >date </label>
								<input type="text" id="date" name="date" class="form-control" />
							</div>

							<div class="form-group">
								<label for="time" >time</label>
								<input type="text" id="time" name="time" class="form-control"/>
							</div>

							<button type="submit" name="meeting" class="btn btn-success">submit</button>
						</form>
					</div>
				</div>
			</div>
 