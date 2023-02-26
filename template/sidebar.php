<?php
	
echo '<div class = "sidebar">';

	if ($is_test_page === 1) {
		echo '<div class = "main_links">
			<a href = "/tests.php?page=create">Создать тест</a></br>
			<a href = "/tests.php?page=list">Список тестов</a></br>
			<a href = "/admin.php">Вернуться в админку</a></br>
		</div>';
	}

echo '<div class = "main_links">
			<a href = "/admin.php?page=store_list">Список товаров</a></br>
			<a href = "/admin.php?page=add_in_store">Добавить товар</a></br>			
			</br>
			<a href = "/admin.php?page=user_add">Добавить пользователя</a></br>
			<a href = "/admin.php?page=user_list">Список пользователей</a></br>
			<a href = "/admin.php?page=comp_editor">Редактор компонентов</a></br>
			<a href = "/admin.php?page=form_editor">Редактор формул</a></br>
			<a href = "/tests.php">Редактор тестов</a></br>
			</br>
			<a href = "/admin.php?page=smstat">Статистика по смскам</a></br>
			</br>
			<a href = "/index.php">Выход</a>
		</div></div>
<div class = "mainbar">
';


?>