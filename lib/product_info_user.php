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
			$imported    = $data['imported'];
			$raw_material = $data['raw_material'];

			$chk_imported = $this->importedcheck($imported);

			if ($product_name == "" or $product_quantity == "" or $imported =="" or $raw_material == ""){
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
			if (filter_var($imported, FILTER_VALproduct_idATE_imported) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The imported is not valproduct_id!</div>";
				return $msg;
			}
			if ($chk_imported==true) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The imported is already exist!</div>";
				return $msg;
			}
			$raw_material = md5($data['raw_material']);*/
			$sql = "INSERT INTO product_info (product_name, product_quantity, imported, raw_material) VALUES (:product_name, :product_quantity, :imported, :raw_material)";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_name',$product_name);
			$query-> bindValue(':product_quantity',$product_quantity);
			$query-> bindValue(':imported',$imported);
			$query-> bindValue(':raw_material',$raw_material);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>data has been inserted!</div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>There has been a problem inserting detail</div>";
				return $msg;
			}
		}
		public function importedcheck($imported){
			$sql = "SELECT imported FROM product_info WHERE imported = :imported";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':imported',$imported);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}
		
		public function getLoginUser($imported, $raw_material){
			$sql = "SELECT * FROM product_info WHERE imported = :imported AND raw_material = :raw_material LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':imported', $imported);
			$query-> bindValue(':raw_material', $raw_material);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}
		public function userLogin($data){
			$imported    = $data['imported'];
			$raw_material = md5($data['raw_material']);
			$chk_imported = $this->importedcheck($imported);

			if ($imported =="" or $raw_material == ""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}
			if (filter_var($imported, FILTER_VALproduct_idATE_imported) === false) {
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The imported is not valproduct_id!</div>";
				return $msg;
			}
			if ($chk_imported == false){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>The imported is not exist!</div>";
				return $msg;
			}

			$result = $this->getLoginUser($imported, $raw_material);
			if ($result){
				Session::init();
				Session::set("login", true);
				Session::set("product_id", $result->product_id);
				Session::set("product_name", $result->product_name);
				Session::set("product_quantity", $result->product_quantity);
				Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are loggedIn!</div>");
				header("Location: product_info.php");
			}else{
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found!</div>";
				return $msg;
			}
		}
		public function getUserData (){
			$sql = "SELECT * FROM product_info ORDER BY product_id DESC";
			$query = $this->db->pdo->prepare($sql);
			$query->execute();
			$result = $query->fetchall();
			return $result;
		}
		public function getUserByproduct_id($product_id){
			$sql = "SELECT * FROM product_info WHERE product_id = :product_id LIMIT 1";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id', $product_id);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_OBJ);
			return $result;
		}

		public function updateUserData($product_id, $data){
			$product_name     = $data['product_name'];
			$product_quantity = $data['product_quantity'];
			$imported    = $data['imported'];
						$raw_material    = $data['raw_material'];


			if ($product_name == "" or $product_quantity == "" or $imported =="" or $raw_material ==""){
				$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field not must be empty</div>";
				return $msg;
			}

			$sql = "UPDATE product_info set
					product_name      = :product_name,
					product_quantity  = :product_quantity,
					imported     = :imported
					raw_material     = :raw_material
					WHERE product_id  = :product_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_name',$product_name);
			$query-> bindValue(':product_quantity',$product_quantity);
			$query-> bindValue(':imported',$imported);
						$query-> bindValue(':raw_material',$raw_material);

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

		private function checkraw_material($product_id, $old_item){
			$raw_material = md5($old_item);
			$sql = "SELECT raw_material FROM product_info WHERE product_id = :product_id AND raw_material = :raw_material";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':product_id', $product_id);
			$query-> bindValue(':raw_material', $raw_material);
			$query->execute();
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

		public function updateraw_material($product_id, $data){
			$old_item = $data['old_item'];
			$new_item = $data['raw_material'];
			$chk_item = $this->checkraw_material($product_id, $old_item);

			if ($old_item == "" or $new_item == "") {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Field Must Not be Empty</div>";
			    return $msg;
			}

			if ($chk_item == false) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>Old raw_material not exits</div>";
				return $msg;
			}

			if (strlen($new_item) < 6) {
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>raw_material is too short</div>";
				return $msg;
			}

			//$raw_material = md5($new_item);
			$sql = "UPDATE product_info set
					raw_material  = :raw_material
					WHERE product_id  = :product_id";
			$query = $this->db->pdo->prepare($sql);
			$query-> bindValue(':raw_material', $raw_material);
			$query-> bindValue(':product_id', $product_id);
			$result = $query->execute();
			if ($result) {
				$msg = "<div class='alert alert-success'><strong>Success !</strong>raw_material updated successfully </div>";
				return $msg;
			}else{
				$msg = "<div class='alert alert-danger'><strong>Sorry !</strong>raw_material not updated</div>";
				return $msg;
			}
		}
	}

?>