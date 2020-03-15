<?

namespace application\models;

use application\core\Model;


class Admin extends Model
{

	public function checkAdmin() {
		// Предполагается, что здесь в будущем будет запрос к БД, поэтому метод реализован в модели

		$login = isset($_POST['login']) ? $_POST['login'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		if (empty($login)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задан логин'
			];
		}

		if (empty($password)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задан пароль'
			];
		}

		if (trim($login) == 'admin' && trim($password) == '123') {
			$_SESSION['login'] = "admin";
			return [
				'url' => '/'
			];
		}

		return [
			'status' => self::ERROR,
			'message' => 'Неправильная пара логин-пароль'
		];
	}

	public function updatetask() {
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$status = (isset($_POST['status']) && $_POST['status'] == 1) ? $_POST['status'] : 0;
		$description = isset($_POST['description']) ? $_POST['description'] : '';

		if (empty($id)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задан id задачи'
			];
		}

		if (empty($description)) {
			return [
				'status' => self::ERROR,
				'message' => 'Не задано описание задачи'
			];
		}

		$query = $this->db->query('
				UPDATE 	tasks
				SET 	description = :description
					,	status = :status
				WHERE 	id = :id
			', 
			compact("description", "status", "id")
		);

		if ($query['result']) {
			$_SESSION['notice'] = "Задача успешно обновлена";
			return [
				'url' => '/'
			];			
		}

		return [
			'status' => self::ERROR,
			'message' => 'Произошла ошибка при обновлении задачи'
		];
	}
}