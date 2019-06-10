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
			$product_id = $data['product_id'];
			$issue_date    = $data['issue_date'];
			$deadline = $data['deadline'];

			$chk_issue_date = $this->issue_datecheck($issue_date);

			if ($buyer_id == "" or $product_id == "" or $issue_date =="" or $deadline == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			/*if (strlen($product_id) <3) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>User buyer_id is too short</div>";
				return $msg;
			}elseif (preg_match('/[^a-z0-9_-]+/i',$product_id)) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>product_id must only contain Alphanumerical,dashes and underscores!</div>";
				return $msg;
			}
			if (filter_var($issue_date, FILTER_VALcontract_idATE_issue_date) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The issue_date is not valcontract_id!</div>";
				return $msg;
			}
			if ($chk_issue_date==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The issue_date is already exist!</div>";
				return $msg;
			}
			$deadline = md5($data['deadline']);*/
			$sql = "INSERT INTO contract (buyer_id, product_id, issue_date, deadline) VALUES (:buyer_id, :product_id, :issue_date, :deadline)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':buyer_id',$buyer_id);
			$query-> bindValue(':product_id',$product_id);
			$query-> bindValue(':issue_date',$issue_date);
			$query-> bindValue(':deadline',$deadline);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function issue_datecheck($issue_date){
			$sql = "SELECT issue_date FROM contract WHERE issue_date = :issue_date";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':issue_date',$issue_date);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($issue_date, $deadline){
			$sql = "SELECT * FROM contract WHERE issue_date = :issue_date AND deadline = :deadline LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':issue_date', $issue_date);
			$query-> bindValue(':deadline', $deadline);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$issue_date    = $data['issue_date'];
			$deadline = md5($data['deadline']);
			$chk_issue_date = $this->issue_datecheck($issue_date);

			if ($issue_date =="" or $deadline == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($issue_date, FILTER_VALcontract_idATE_issue_date) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The issue_date is not valcontract_id!</div>";
				return $msg;
			}
			if ($chk_issue_date == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The issue_date is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($issue_date, $deadline);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("contract_id", $result->contract_id);
				Session::set("buyer_id", $result->buyer_id);
				Session::set("product_id", $result->product_id);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: contract.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM contract ORDER BY contract_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserBycontract_id($contract_id){
			$sql = "SELECT * FROM contract WHERE contract_id = :contract_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':contract_id', $contract_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($contract_id, $data){
			$buyer_id     = $data['buyer_id'];
			$product_id = $data['product_id'];
			$issue_date    = $data['issue_date'];
						$deadline    = $data['deadline'];


			if ($buyer_id == "" or $product_id == "" or $issue_date =="" or $deadline ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE contract set
					buyer_id      = :buyer_id,
					product_id  = :product_id,
					issue_date     = :issue_date
					deadline     = :deadline
					WHERE contract_id  = :contract_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':buyer_id',$buyer_id);
			$query-> bindValue(':product_id',$product_id);
			$query-> bindValue(':issue_date',$issue_date);
						$query-> bindValue(':deadline',$deadline);

			$query-> bindValue(':contract_id',$contract_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong> data updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong> data not updated</div>";
				return $msg;
			}
		}

		private function checkdeadline($contract_id, $old_item){
			$deadline = md5($old_item);
			$sql = "SELECT deadline FROM contract WHERE contract_id = :contract_id AND deadline = :deadline";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':contract_id', $contract_id);
			$query-> bindValue(':deadline', $deadline);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updatedeadline($contract_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['deadline'];
			$chk_item = $this->checkdeadline($contract_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old deadline not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>deadline is too short</div>";
				return $msg;
			}

			//$deadline = md5($new_item);
			$sql = "UPDATE contract set
					deadline  = :deadline
					WHERE contract_id  = :contract_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':deadline', $deadline);
			$query-> bindValue(':contract_id', $contract_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>deadline updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>deadline not updated</div>";
				return $msg;
			}
		}
	}

?>