<?

namespace application\controllers;

use application\core\Controller;
use application\lib\Db;
use application\core\View;

class MainController extends Controller
{

	public function indexAction() {

		if (isset($_GET['page']) && (int)$_GET['page'] == $_GET['page']) {
			// Только если задано целое число
			$page = (int)$_GET['page'];

			if ($page == 1) {
				// Важно для SEO
				$this->view->redirect('/');
			}
		} else {
			$page = 1;
		}
		
		$tasks = $this->model->getTasks($page);
		$pages = $this->model->getCountPages();

		if ($page > $pages || $page < 1) {
			View::errorCode(404);
		}

		$vars = [
			'tasks' => $tasks,
			'page' => $page,
			'pages' => $pages
		];
		$this->view->render('Список задач', $vars);
	}

	public function addtaskAction() {
		if (!empty($_POST)) {
			$result = $this->model->newTask();
			if (isset($result['url'])) {
				$this->view->location($result['url']);
			} elseif (isset($result['status']) && isset($result['message'])) {
				$this->view->message($result['status'], $result['message']);
			} else {
				$this->view->message('error', 'Ошибка обработки запроса');	
			}
		}
	}

}