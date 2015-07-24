<?php
class ActiveBase {
	protected $pdo;

	public function __construct($data) {
		if (!isset($data) || $data === false) return false;
		$class_route = explode('\\', get_class($this));
		$class_name = end($class_route);
		if ($class_name == 'ActiveBase') return;
		$model_name = str_replace('_model', '', $class_name);
		$this->table = strtolower($model_name).'s';
		$this->pdo = new PDO(
			'mysql:host='.$data['host'].';
			dbname='.$data['dbname'].';
			charset=utf8',
			$data['user'], $data['pass'],
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);
	}

	public function truncate() {
		$table = $this->table;
		$sql = "TRUNCATE TABLE $table";
		$pdo = $this->pdo->prepare($sql);
		@$pdo->execute() or $error;
		if(isset($error)) return 'error';
		return 'done';
	}

	public function next_id() {
		$table = $this->table;
		$sql = "SHOW TABLE STATUS LIKE ?";
		$pdo = $this->pdo->prepare($sql);
		$pdo->execute(array($table));
		return $pdo->fetch(PDO::FETCH_OBJ)->Auto_increment;
	}

	public function get($id) {
		if (isset($id)) {
			$table = $this->table;
			$sql = "SELECT * FROM $table WHERE id = ?";
			$pdo = $this->pdo->prepare($sql);
			$pdo->execute(array($id));
			return $pdo->fetch(PDO::FETCH_OBJ);
		}
	}

	public function add($arr = array()) {
		$table = $this->table;
		$keys = array_keys($arr);
		$columns = '`'.implode('`, `', $keys).'`';
		$values = ':'.implode(', :', $keys);
		$sql = "INSERT INTO $table ($columns) VALUES ($values)";
		$pdoArr = array();
		foreach($arr as $key => $val) {
			$pdoArr[':'.$key] = $val;
		}
		$pdo = $this->pdo->prepare($sql);
		$pdo->execute($pdoArr);
		return $this->pdo->lastInsertId();
	}

	public function update($id, $arr = array()) {
		if (!isset($id)) return false;
		$table = $this->table;
		$keys = array_keys($arr);
		$sqlArr = array();
		foreach($keys as $key) {
			$sqlArr[] = '`'.$key.'`=:'.$key;
		}
		$set = implode(',',$sqlArr);
		$sql = "UPDATE $table SET $set WHERE id = :id";
		$pdoArr = array();
		foreach($arr as $key => $val) {
			$pdoArr[':'.$key] = $val;
		}
		$pdoArr[':id'] = $id;
		$pdo = $this->pdo->prepare($sql);
		$pdo->execute($pdoArr);
		return $id;
	}

	public function delete($id) {
		$table = $this->table;
		$sql = "DELETE FROM $table WHERE id = ?";
		$pdo = $this->pdo->prepare($sql);
		$pdo->execute(array($id));
	}

	public function index($str = false, $arr = array()) {
		$table = $this->table;
		$sql = "SELECT * FROM $table";
		if ($str) $sql.= ' '.$str;
		$pdo = $this->pdo->prepare($sql);
		$pdo->execute($arr);
		return $pdo->fetchAll(PDO::FETCH_OBJ);
	}
}
?>