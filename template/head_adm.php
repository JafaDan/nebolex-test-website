<?

session_start();

if (!(isset($_SESSION['adlogin']))) {
header("Location: /index.php");
exit;
};

include('./system/core.php');

ob_start();

echo '
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	
	if ($is_test_page === 1) echo '<title>МедСайт - редактор тестов</title>';
	else echo '<title>МедСайт - админ-панель</title>';
	
echo '<link href="oldstyle/style.css" rel="stylesheet">
</head>
<body>

<div id = "header">
	<div class = "logo">Таблетка</div>
	<div class = "auth">Вы вошли как Администратор. <a href = "index.php">Выйти?</a></div>
</div>
<div id = "content">
';



?>