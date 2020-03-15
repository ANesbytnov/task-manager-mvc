<?

namespace application\core;

use application\lib\Db;

abstract class Model
{
	public $db;

	const ERROR = 'error';
	const SUCCESS = 'success';

	public function __construct() {
		$this->db = new DB;
	}

}