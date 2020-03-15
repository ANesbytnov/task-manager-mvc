<?

namespace application\controllers;

use application\core\Controller;

class AdminController extends Controller
{
	public function loginAction() {
		if (isset($_SESSION['login'])) {
			$this->view->redirect('/');
		}
		 
		if (!empty($_POST)) {
			$result = $this->model->checkAdmin();
			if (isset($result['url'])) {
				$this->view->location($result['url']);
			} elseif (isset($result['status']) && isset($result['message'])) {
				$this->view->message($result['status'], $result['message']);
			} else {
				$this->view->message('error', 'Ошибка обработки запроса');	
			}		
		}
		$this->view->render('Login');
	}

	public function logoutAction() {
		if (isset($_SESSION['login'])) {
			unset($_SESSION['login']);
			$this->view->redirect('/');
		} else {
			View::errorCode(404);
		}
	}

	public function updatetaskAction() {
		if (!empty($_POST)) {
			$result = $this->model->updateTask();
			if (isset($result['url'])) {
				$this->view->location($result['url']);
			} elseif (isset($result['status']) && isset($result['message'])) {
				$this->view->message($result['status'], $result['message']);
			}
		} 

		$this->view->message('error', 'Ошибка обработки запроса ' . var_export($result));	
	}

}