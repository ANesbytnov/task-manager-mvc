<?

namespace application\models;

use application\core\Model;


class Admin extends Model
{

	public function checkAdmin() {
		// Предполагается, что здесь в будущем будет запрос к БД, поэтому метод реализован в модели

		$login = isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '';
		$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

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
		$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
		$task_status = (isset($_POST['status']) && in_array($_POST['status'], ["0", "1"])) ? $_POST['status'] : -1;
		$description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';

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

		// Возможно admin_status = 0 И изменился текст задачи, тогда нужно сделать admin_status = 1
		$old = $this->db->row('
				SELECT 	description
					,	admin_status
				FROM	tasks
				WHERE 	id = :id
			', 
			compact("id")
		);

		if (is_array($old) && count($old) > 0 && ($old[0]['admin_status'] == 0) && ($old[0]['description'] != $description)) {
			$admin_status = 1;
		} else {
			$admin_status = 0;
		}

		if ($task_status !== -1) {
			$query = $this->db->query('
					UPDATE 	tasks
					SET 	description = :description
						,	task_status = :task_status ' .
						($admin_status == 1 ? ',	admin_status = 1 ' : '') . '
					WHERE 	id = :id
				', 
				compact("description", "task_status", "id")
			);
		} else {
			$query = $this->db->query('
					UPDATE 	tasks
					SET 	description = :description ' .
						($admin_status == 1 ? ',	admin_status = 1 ' : '') . '
					WHERE 	id = :id
				', 
				compact("description", "id")
			);
		}

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