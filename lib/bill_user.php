<?php
	//include_once 'Session.php';
	include 'Database.php';
	
	class User{
		private $db;
		
		public function __construct(){
			$this->db = new Database();
		}
		public function userRegistation($data){
			$ordering_id     = $data['ordering_id'];
			$cost = $data['cost'];
			$paid    = $data['paid'];
			$due = $data['due'];

			//$chk_paid = $this->paidcheck($paid);

			if ($ordering_id == "" or $cost == "" or $paid =="" or $due == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			/*if (strlen($cost) <3) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>User ordering_id is too short</div>";
				return $msg;
			}elseif (preg_match('/[^a-z0-9_-]+/i',$cost)) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>cost must only contain Alphanumerical,dashes and underscores!</div>";
				return $msg;
			}
			if (filter_var($paid, FILTER_VALbill_idATE_paid) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The paid is not valbill_id!</div>";
				return $msg;
			}
			if ($chk_paid==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The paid is already exist!</div>";
				return $msg;
			}
			$due = md5($data['due']);*/
			$sql = "INSERT INTO bill (ordering_id, cost, paid, due) VALUES (:ordering_id, :cost, :paid, :due)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':ordering_id',$ordering_id);
			$query-> bindValue(':cost',$cost);
			$query-> bindValue(':paid',$paid);
			$query-> bindValue(':due',$due);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function paidcheck($paid){
			$sql = "SELECT paid FROM bill WHERE paid = :paid";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':paid',$paid);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($paid, $due){
			$sql = "SELECT * FROM bill WHERE paid = :paid AND due = :due LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':paid', $paid);
			$query-> bindValue(':due', $due);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$paid    = $data['paid'];
			$due = md5($data['due']);
			$chk_paid = $this->paidcheck($paid);

			if ($paid =="" or $due == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($paid, FILTER_VALbill_idATE_paid) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The paid is not valbill_id!</div>";
				return $msg;
			}
			if ($chk_paid == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The paid is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($paid, $due);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("bill_id", $result->bill_id);
				Session::set("ordering_id", $result->ordering_id);
				Session::set("cost", $result->cost);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: bill.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM bill ORDER BY bill_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserBybill_id($bill_id){
			$sql = "SELECT * FROM bill WHERE bill_id = :bill_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':bill_id', $bill_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($bill_id, $data){
			$ordering_id     = $data['ordering_id'];
			$cost = $data['cost'];
			$paid    = $data['paid'];
						$due    = $data['due'];


			if ($ordering_id == "" or $cost == "" or $paid =="" or $due ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE bill set
					ordering_id      = :ordering_id,
					cost  = :cost,
					paid     = :paid
					due     = :due
					WHERE bill_id  = :bill_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':ordering_id',$ordering_id);
			$query-> bindValue(':cost',$cost);
			$query-> bindValue(':paid',$paid);
						$query-> bindValue(':due',$due);

			$query-> bindValue(':bill_id',$bill_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong> data updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong> data not updated</div>";
				return $msg;
			}
		}

		private function checkdue($bill_id, $old_item){
			$due = md5($old_item);
			$sql = "SELECT due FROM bill WHERE bill_id = :bill_id AND due = :due";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':bill_id', $bill_id);
			$query-> bindValue(':due', $due);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updatedue($bill_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['due'];
			$chk_item = $this->checkdue($bill_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old due not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>due is too short</div>";
				return $msg;
			}

			//$due = md5($new_item);
			$sql = "UPDATE bill set
					due  = :due
					WHERE bill_id  = :bill_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':due', $due);
			$query-> bindValue(':bill_id', $bill_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>due updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>due not updated</div>";
				return $msg;
			}
		}
	}

?>