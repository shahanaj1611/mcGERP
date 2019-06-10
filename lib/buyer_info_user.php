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
			$buyer_name = $data['buyer_name'];
			$company_address    = $data['company_address'];
			$purchase_quantity = $data['purchase_quantity'];

			$chk_company_address = $this->company_addresscheck($company_address);

			if ($product_id == "" or $buyer_name == "" or $company_address =="" or $purchase_quantity == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			/*if (strlen($buyer_name) <3) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>User product_id is too short</div>";
				return $msg;
			}elseif (preg_match('/[^a-z0-9_-]+/i',$buyer_name)) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>buyer_name must only contain Alphanumerical,dashes and underscores!</div>";
				return $msg;
			}
			if (filter_var($company_address, FILTER_VALbuyer_idATE_company_address) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The company_address is not valbuyer_id!</div>";
				return $msg;
			}
			if ($chk_company_address==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The company_address is already exist!</div>";
				return $msg;
			}
			$purchase_quantity = md5($data['purchase_quantity']);*/
			$sql = "INSERT INTO buyer_info (product_id, buyer_name, company_address, purchase_quantity) VALUES (:product_id, :buyer_name, :company_address, :purchase_quantity)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id',$product_id);
			$query-> bindValue(':buyer_name',$buyer_name);
			$query-> bindValue(':company_address',$company_address);
			$query-> bindValue(':purchase_quantity',$purchase_quantity);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function company_addresscheck($company_address){
			$sql = "SELECT company_address FROM buyer_info WHERE company_address = :company_address";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':company_address',$company_address);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($company_address, $purchase_quantity){
			$sql = "SELECT * FROM buyer_info WHERE company_address = :company_address AND purchase_quantity = :purchase_quantity LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':company_address', $company_address);
			$query-> bindValue(':purchase_quantity', $purchase_quantity);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$company_address    = $data['company_address'];
			$purchase_quantity = md5($data['purchase_quantity']);
			$chk_company_address = $this->company_addresscheck($company_address);

			if ($company_address =="" or $purchase_quantity == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($company_address, FILTER_VALbuyer_idATE_company_address) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The company_address is not valbuyer_id!</div>";
				return $msg;
			}
			if ($chk_company_address == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The company_address is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($company_address, $purchase_quantity);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("buyer_id", $result->buyer_id);
				Session::set("product_id", $result->product_id);
				Session::set("buyer_name", $result->buyer_name);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: buyer_info.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM buyer_info ORDER BY buyer_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserBybuyer_id($buyer_id){
			$sql = "SELECT * FROM buyer_info WHERE buyer_id = :buyer_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':buyer_id', $buyer_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($buyer_id, $data){
			$product_id     = $data['product_id'];
			$buyer_name = $data['buyer_name'];
			$company_address    = $data['company_address'];
						$purchase_quantity    = $data['purchase_quantity'];


			if ($product_id == "" or $buyer_name == "" or $company_address =="" or $purchase_quantity ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE buyer_info set
					product_id      = :product_id,
					buyer_name  = :buyer_name,
					company_address     = :company_address
					purchase_quantity     = :purchase_quantity
					WHERE buyer_id  = :buyer_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id',$product_id);
			$query-> bindValue(':buyer_name',$buyer_name);
			$query-> bindValue(':company_address',$company_address);
						$query-> bindValue(':purchase_quantity',$purchase_quantity);

			$query-> bindValue(':buyer_id',$buyer_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong> data updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong> data not updated</div>";
				return $msg;
			}
		}

		private function checkpurchase_quantity($buyer_id, $old_item){
			$purchase_quantity = md5($old_item);
			$sql = "SELECT purchase_quantity FROM buyer_info WHERE buyer_id = :buyer_id AND purchase_quantity = :purchase_quantity";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':buyer_id', $buyer_id);
			$query-> bindValue(':purchase_quantity', $purchase_quantity);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updatepurchase_quantity($buyer_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['purchase_quantity'];
			$chk_item = $this->checkpurchase_quantity($buyer_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old purchase_quantity not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>purchase_quantity is too short</div>";
				return $msg;
			}

			//$purchase_quantity = md5($new_item);
			$sql = "UPDATE buyer_info set
					purchase_quantity  = :purchase_quantity
					WHERE buyer_id  = :buyer_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':purchase_quantity', $purchase_quantity);
			$query-> bindValue(':buyer_id', $buyer_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>purchase_quantity updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>purchase_quantity not updated</div>";
				return $msg;
			}
		}
	}

?>