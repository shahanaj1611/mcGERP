<?php
	//include_once 'Session.php';
	include 'Database.php';
	
	class User{
		private $db;
		
		public function __construct(){
			$this->db = new Database();
		}
		public function userRegistation($data){
			$product_id     = $data['product_id'];
			$ordering_item = $data['ordering_item'];
			$ordering_date    = $data['ordering_date'];
			$arrival_date = $data['arrival_date'];

			//$chk_ordering_date = $this->ordering_datecheck($ordering_date);

			if ($product_id == "" or $ordering_item == "" or $ordering_date =="" or $arrival_date == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			/*if (strlen($ordering_item) <3) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>User product_id is too short</div>";
				return $msg;
			}elseif (preg_match('/[^a-z0-9_-]+/i',$ordering_item)) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>ordering_item must only contain Alphanumerical,dashes and underscores!</div>";
				return $msg;
			}
			if (filter_var($ordering_date, FILTER_VALordering_idATE_ordering_date) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The ordering_date is not valordering_id!</div>";
				return $msg;
			}
			if ($chk_ordering_date==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The ordering_date is already exist!</div>";
				return $msg;
			}
			$arrival_date = md5($data['arrival_date']);*/
			$sql = "INSERT INTO ordering (product_id, ordering_item, ordering_date, arrival_date) VALUES (:product_id, :ordering_item, :ordering_date, :arrival_date)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id',$product_id);
			$query-> bindValue(':ordering_item',$ordering_item);
			$query-> bindValue(':ordering_date',$ordering_date);
			$query-> bindValue(':arrival_date',$arrival_date);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function ordering_datecheck($ordering_date){
			$sql = "SELECT ordering_date FROM ordering WHERE ordering_date = :ordering_date";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':ordering_date',$ordering_date);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($ordering_date, $arrival_date){
			$sql = "SELECT * FROM ordering WHERE ordering_date = :ordering_date AND arrival_date = :arrival_date LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':ordering_date', $ordering_date);
			$query-> bindValue(':arrival_date', $arrival_date);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$ordering_date    = $data['ordering_date'];
			$arrival_date = md5($data['arrival_date']);
			$chk_ordering_date = $this->ordering_datecheck($ordering_date);

			if ($ordering_date =="" or $arrival_date == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($ordering_date, FILTER_VALordering_idATE_ordering_date) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The ordering_date is not valordering_id!</div>";
				return $msg;
			}
			if ($chk_ordering_date == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The ordering_date is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($ordering_date, $arrival_date);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("ordering_id", $result->ordering_id);
				Session::set("product_id", $result->product_id);
				Session::set("ordering_item", $result->ordering_item);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: ordering.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM ordering ORDER BY ordering_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserByordering_id($ordering_id){
			$sql = "SELECT * FROM ordering WHERE ordering_id = :ordering_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':ordering_id', $ordering_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($ordering_id, $data){
			$product_id     = $data['product_id'];
			$ordering_item = $data['ordering_item'];
			$ordering_date    = $data['ordering_date'];
						$arrival_date    = $data['arrival_date'];


			if ($product_id == "" or $ordering_item == "" or $ordering_date =="" or $arrival_date ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE ordering set
					product_id      = :product_id,
					ordering_item  = :ordering_item,
					ordering_date     = :ordering_date
					arrival_date     = :arrival_date
					WHERE ordering_id  = :ordering_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id',$product_id);
			$query-> bindValue(':ordering_item',$ordering_item);
			$query-> bindValue(':ordering_date',$ordering_date);
						$query-> bindValue(':arrival_date',$arrival_date);

			$query-> bindValue(':ordering_id',$ordering_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong> data updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong> data not updated</div>";
				return $msg;
			}
		}

		private function checkarrival_date($ordering_id, $old_item){
			$arrival_date = md5($old_item);
			$sql = "SELECT arrival_date FROM ordering WHERE ordering_id = :ordering_id AND arrival_date = :arrival_date";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':ordering_id', $ordering_id);
			$query-> bindValue(':arrival_date', $arrival_date);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updatearrival_date($ordering_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['arrival_date'];
			$chk_item = $this->checkarrival_date($ordering_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old arrival_date not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>arrival_date is too short</div>";
				return $msg;
			}

			//$arrival_date = md5($new_item);
			$sql = "UPDATE ordering set
					arrival_date  = :arrival_date
					WHERE ordering_id  = :ordering_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':arrival_date', $arrival_date);
			$query-> bindValue(':ordering_id', $ordering_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>arrival_date updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>arrival_date not updated</div>";
				return $msg;
			}
		}
	}

?>