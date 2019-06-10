<?php
	//include_once 'Session.php';
	include 'Database.php';
	
	class User{
		private $db;
		
		public function __construct(){
			$this->db = new Database();
		}
		public function userRegistation($data){
			$buyer_id     = $data['buyer_id'];
			$meeting_place = $data['meeting_place'];
			$date    = $data['date'];
			$time = $data['time'];

			$chk_date = $this->datecheck($date);

			if ($buyer_id == "" or $meeting_place == "" or $date =="" or $time == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			/*if (strlen($meeting_place) <3) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>User buyer_id is too short</div>";
				return $msg;
			}elseif (preg_match('/[^a-z0-9_-]+/i',$meeting_place)) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>meeting_place must only contain Alphanumerical,dashes and underscores!</div>";
				return $msg;
			}
			if (filter_var($date, FILTER_VALmeeting_idATE_date) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The date is not valmeeting_id!</div>";
				return $msg;
			}
			if ($chk_date==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The date is already exist!</div>";
				return $msg;
			}
			$time = md5($data['time']);*/
			$sql = "INSERT INTO meeting (buyer_id, meeting_place, date, time) VALUES (:buyer_id, :meeting_place, :date, :time)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':buyer_id',$buyer_id);
			$query-> bindValue(':meeting_place',$meeting_place);
			$query-> bindValue(':date',$date);
			$query-> bindValue(':time',$time);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function datecheck($date){
			$sql = "SELECT date FROM meeting WHERE date = :date";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':date',$date);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($date, $time){
			$sql = "SELECT * FROM meeting WHERE date = :date AND time = :time LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':date', $date);
			$query-> bindValue(':time', $time);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$date    = $data['date'];
			$time = md5($data['time']);
			$chk_date = $this->datecheck($date);

			if ($date =="" or $time == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($date, FILTER_VALmeeting_idATE_date) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The date is not valmeeting_id!</div>";
				return $msg;
			}
			if ($chk_date == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The date is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($date, $time);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("meeting_id", $result->meeting_id);
				Session::set("buyer_id", $result->buyer_id);
				Session::set("meeting_place", $result->meeting_place);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: meeting.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM meeting ORDER BY meeting_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserBymeeting_id($meeting_id){
			$sql = "SELECT * FROM meeting WHERE meeting_id = :meeting_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':meeting_id', $meeting_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($meeting_id, $data){
			$buyer_id     = $data['buyer_id'];
			$meeting_place = $data['meeting_place'];
			$date    = $data['date'];
						$time    = $data['time'];


			if ($buyer_id == "" or $meeting_place == "" or $date =="" or $time ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE meeting set
					buyer_id      = :buyer_id,
					meeting_place  = :meeting_place,
					date     = :date
					time     = :time
					WHERE meeting_id  = :meeting_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':buyer_id',$buyer_id);
			$query-> bindValue(':meeting_place',$meeting_place);
			$query-> bindValue(':date',$date);
						$query-> bindValue(':time',$time);

			$query-> bindValue(':meeting_id',$meeting_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong> data updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong> data not updated</div>";
				return $msg;
			}
		}

		private function checktime($meeting_id, $old_item){
			$time = md5($old_item);
			$sql = "SELECT time FROM meeting WHERE meeting_id = :meeting_id AND time = :time";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':meeting_id', $meeting_id);
			$query-> bindValue(':time', $time);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updatetime($meeting_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['time'];
			$chk_item = $this->checktime($meeting_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old time not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>time is too short</div>";
				return $msg;
			}

			//$time = md5($new_item);
			$sql = "UPDATE meeting set
					time  = :time
					WHERE meeting_id  = :meeting_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':time', $time);
			$query-> bindValue(':meeting_id', $meeting_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>time updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>time not updated</div>";
				return $msg;
			}
		}
	}

?>