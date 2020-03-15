<?

namespace application\core;

define('DEBUG_MODE', 1); // 1 = показ debug-сообщений

class View
{

	public $path;
	public $route;
	public $layout = 'default';

	public function __construct($route) {
		$this->route = $route;
		$this->path = $route['controller'] . '/' . $route['action'];
	}

	public function render($title, $vars = []) {
		extract($vars);
		$path = 'application/views/' . $this->path . '.php';
		if (file_exists($path)) {
			ob_start();
			require $path;
			$content = ob_get_clean();

			require 'application/views/layouts/' . $this->layout . '.php';
		} else {
			echo 'Вид не найден: ' . $this->path;
		}
	}

	public static function errorCode($code, $message = '') {
		http_response_code($code);
		$path = 'application/views/errors/' . $code . '.php';
		if (file_exists($path)) {
			if (DEBUG_MODE !== 1) { // Если не дебаг-режим, то чистим сообщения
				$message = '';
			}
			require $path;
		} else {
			echo 'Страница не найдена ' . $path;
		}
		exit();
	}

	public function redirect($url) {
		header('location:' . $url);
		exit();
	}

	public function message($status, $message) {
		exit(json_encode(['status' => $status, 'message' => $message]));
	}

	public function location($url) {
		exit(json_encode(['url' => $url]));
	}

}