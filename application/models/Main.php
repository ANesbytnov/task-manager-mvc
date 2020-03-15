<?

namespace application\models;

use application\core\Model;


class Main extends Model
{
	const LIMIT = 3;

	public function getCountPages() {
		$count = $this->db->column('
			SELECT 	COUNT(id) AS count
			FROM 	tasks'
		);
		return (int)ceil($count / self::LIMIT);
	}

	public function getTasks($page = 1) {
		$offset = ($page - 1) * self::LIMIT;

		// Сортировка возможно лежит в куках
		if (isset($_COOKIE['sortField']) && in_array($_COOKIE['sortField'], ['id', 'username', 'email', 'description'])) {
			$sortField = $_COOKIE['sortField'];
		} else {
			$sortField = 'id';
		}
		
		if (isset($_COOKIE['sortType']) && in_array($_COOKIE['sortType'], ['asc', 'desc'])) {
			$sortType = $_COOKIE['sortType'];
		} else {
			$sortType = 'asc';
		}

		return $this->db->row('
			SELECT 		id
					,	username
					, 	email
					,	description
					, 	status
			FROM 		tasks
			ORDER BY 	' . $sortField . ' ' . $sortType . ' 
			LIMIT 		' . $offset . ', ' . self::LIMIT
		);
	}

	public function newTask() {
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$description = isset($_POST['description']) ? $_POST['description'] : '';

		if (empty($username)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задано имя пользователя'
			];
		}

		if (empty($email)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задан E-mail'
			];
		}

		if (empty($description)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задано описание задачи'
			];
		}

		$query = $this->db->query('
				INSERT INTO tasks(username, email, description)
				VALUES (:username, :email, :description)
			', 
			compact("username", "email", "description")
		);

		if ($query['result']) {
			$_SESSION['notice'] = "Задача успешно добавлена";
			return [
				'url' => '/'
			];			
		}

		return [
			'status' => self::ERROR,
			'message' => 'Произошла ошибка при добавлении задачи'
		];
	}
}