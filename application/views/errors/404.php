<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>404</title>
</head>
<body>
	<h1>Страница не найдена</h1><?

	if (isset($message) && !empty($message)) { ?>
		<p><?= $message ?></p><?
	} ?>
</body>
</html>