<?

namespace application\lib;

use PDO;

class Db
{
	
	protected $db;

	public function __construct()
	{
		$config = require 'application/config/db.php';
		
		$this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
	}

	public function query($sql, $params = []) {
		$stmt = $this->db->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				$stmt->bindValue(':' . $key, $val);
			}
		} 
		$result = $stmt->execute();
		return compact("stmt", "result");
	}

	public function row($sql, $params = []) {
		return $this->query($sql, $params)['stmt']->fetchAll(PDO::FETCH_ASSOC);
	}

	public function column($sql, $params = []) {
		return $this->query($sql, $params)['stmt']->fetchColumn();
	}

}