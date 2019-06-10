<?php
	//include_once 'Session.php';
	include 'Database.php';
	
	class User{
		private $db;
		
		public function __construct(){
			$this->db = new Database();
		}
		public function userRegistation($data){
			$product_name     = $data['product_name'];
			$product_quantity = $data['product_quantity'];
			$limited_item    = $data['limited_item'];
			$extra_item = $data['extra_item'];

			$chk_limited_item = $this->limited_itemcheck($limited_item);

			if ($product_name == "" or $product_quantity == "" or $limited_item =="" or $extra_item == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			/*if (strlen($product_quantity) <3) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>User product_name is too short</div>";
				return $msg;
			}elseif (preg_match('/[^a-z0-9_-]+/i',$product_quantity)) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>product_quantity must only contain Alphanumerical,dashes and underscores!</div>";
				return $msg;
			}
			if (filter_var($limited_item, FILTER_VALproduct_idATE_limited_item) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The limited_item is not valproduct_id!</div>";
				return $msg;
			}
			if ($chk_limited_item==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The limited_item is already exist!</div>";
				return $msg;
			}
			$extra_item = md5($data['extra_item']);*/
			$sql = "INSERT INTO asset_tracking (product_name, product_quantity, limited_item, extra_item) VALUES (:product_name, :product_quantity, :limited_item, :extra_item)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_name',$product_name);
			$query-> bindValue(':product_quantity',$product_quantity);
			$query-> bindValue(':limited_item',$limited_item);
			$query-> bindValue(':extra_item',$extra_item);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function limited_itemcheck($limited_item){
			$sql = "SELECT limited_item FROM asset_tracking WHERE limited_item = :limited_item";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':limited_item',$limited_item);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($limited_item, $extra_item){
			$sql = "SELECT * FROM asset_tracking WHERE limited_item = :limited_item AND extra_item = :extra_item LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':limited_item', $limited_item);
			$query-> bindValue(':extra_item', $extra_item);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$limited_item    = $data['limited_item'];
			$extra_item = md5($data['extra_item']);
			$chk_limited_item = $this->limited_itemcheck($limited_item);

			if ($limited_item =="" or $extra_item == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($limited_item, FILTER_VALproduct_idATE_limited_item) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The limited_item is not valproduct_id!</div>";
				return $msg;
			}
			if ($chk_limited_item == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The limited_item is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($limited_item, $extra_item);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("product_id", $result->product_id);
				Session::set("product_name", $result->product_name);
				Session::set("product_quantity", $result->product_quantity);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: asset_tracking.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM asset_tracking ORDER BY product_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserByproduct_id($product_id){
			$sql = "SELECT * FROM asset_tracking WHERE product_id = :product_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id', $product_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($product_id, $data){
			$product_name     = $data['product_name'];
			$product_quantity = $data['product_quantity'];
			$limited_item    = $data['limited_item'];
						$extra_item    = $data['extra_item'];


			if ($product_name == "" or $product_quantity == "" or $limited_item =="" or $extra_item ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE asset_tracking set
					product_name      = :product_name,
					product_quantity  = :product_quantity,
					limited_item     = :limited_item
					extra_item     = :extra_item
					WHERE product_id  = :product_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_name',$product_name);
			$query-> bindValue(':product_quantity',$product_quantity);
			$query-> bindValue(':limited_item',$limited_item);
						$query-> bindValue(':extra_item',$extra_item);

			$query-> bindValue(':product_id',$product_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong> data updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong> data not updated</div>";
				return $msg;
			}
		}

		private function checkextra_item($product_id, $old_item){
			$extra_item = md5($old_item);
			$sql = "SELECT extra_item FROM asset_tracking WHERE product_id = :product_id AND extra_item = :extra_item";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id', $product_id);
			$query-> bindValue(':extra_item', $extra_item);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updateextra_item($product_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['extra_item'];
			$chk_item = $this->checkextra_item($product_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old extra_item not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>extra_item is too short</div>";
				return $msg;
			}

			//$extra_item = md5($new_item);
			$sql = "UPDATE asset_tracking set
					extra_item  = :extra_item
					WHERE product_id  = :product_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':extra_item', $extra_item);
			$query-> bindValue(':product_id', $product_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>extra_item updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>extra_item not updated</div>";
				return $msg;
			}
		}
	}

?>