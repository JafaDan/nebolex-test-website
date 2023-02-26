<?php

$is_user_page = 0;
$is_admin_page = 1;
$is_test_page = 1;

include('./template/header.php');
include('./template/sidebar.php');

$pagesel = trim($_GET['page']);

switch($pagesel) {


//Создание теста
case 'create':
		
		echo '<div class = "input_field"><p>Создать тест</p></div>
			<div class = "input_field"><form method="post" action="">
				<p>Название теста</p>
					<input type="text" name="test_name" value="" maxlength="40"></input>
				<p>Текст</p>
					<textarea rows = "5" cols = "21" name="test_text" value="" maxlength="300"></textarea></br></br>
				<input type="submit" name="add_test" value="Создать тест"></input></form>
			</div>';
					
			$add_test = $_POST['add_test'];
				
			if (isset($add_test)) {
						
				$test_name = $_POST['test_name'];
				$test_text = $_POST['test_text'];
				$current_add_time = time(U);
				
				mysql_query("INSERT INTO `TESTS` (`ID`,`NAME`,`TEXT`,`CREATION_TIME`) VALUES ('','$test_name','$test_text','$current_add_time');") or die (mysql_error());
				
				$id_query = mysql_query("SELECT ID FROM TESTS WHERE CREATION_TIME = '$current_add_time'");
					while ($query_id = mysql_fetch_array($id_query)) {
						$test_id = $query_id['ID'];
					}
				
				header("Location: /tests.php?page=edit&test_id=$test_id");
						
			}

	echo '<div class = "main_links">
			<a href = "/tests.php">Назад</a></br>
			</div>';
			
break;

//Редактор теста
case 'edit':

	$test_id = $_GET['test_id'];
	$mysql_result = mysql_query("SELECT * FROM `TESTS` WHERE `ID` = '$test_id'");
	
	while ($mass5 = mysql_fetch_array($mysql_result)) {
		$creation_time = $mass5['CREATION_TIME'];
		echo '<div class = "results"><ul>
		<li>ID Теста: ' .$mass5['ID'] . '</li>
		<li>Название:  ' .$mass5['NAME'] . '</li>
		<li>Текст:  ' .$mass5['TEXT'] . '</li>
		<li>Дата создания:  ' . date('d.m.Y в G:i', $creation_time) . '</li>
		<li><a href = "/tests.php?page=del_test&test_id='.$mass5['ID'].'">Удалить тест навсегда</a></li>
		<li><a href = "/tests.php?page=del_test&test_id='.$mass5['ID'].'">Активировать тест</a></li>
		</ul></div>
		';
	}
	
	$mysql_result1 = mysql_query("SELECT * FROM `TESTS_QUESTIONS` WHERE `TEST_ID` = '$test_id'");
	while ($mass6 = mysql_fetch_array($mysql_result1)) {
		echo '<div class = "results"><ul>
		<li>Вопрос:  ' .$mass6['QTEXT'] . '</li>';
			
			$quest_id = $mass6['ID'];
			$mysql_result2 = mysql_query("SELECT * FROM `TESTS_ANSWERS` WHERE `QUEST_ID` = '$quest_id'");
			while ($mass7 = mysql_fetch_array($mysql_result2)) {
				echo '<li>Ответ: ' .$mass7['ATEXT'] . '</br> Значение:  ' .$mass7['AVALUE'] . '</li>';
			}
			
		echo '<li><a href = "/tests.php?page=del_quest&test_id='.$test_id.'&id='.$quest_id.'">Удалить вопрос</a></li>
		</ul></div>
		';
	}
	
	$mysql_test_result = mysql_query("SELECT * FROM `TESTS_RESULTS` WHERE `TEST_ID` = '$test_id'");
		while ($test_info3 = mysql_fetch_array($mysql_test_result)) {
			echo '<div class = "results"><li>Диапазон</li>
			<li>Текст:  ' .$test_info3['TEXT'] . '</li>
			<li>Минимум:  ' .$test_info3['RESULT_MIN'] . '</li>
			<li>Максимум:  ' .$test_info3['RESULT_MAX'] . '</li>
			<li><a href = "/tests.php?page=del_result&test_id='.$test_id.'&result_id='.$mass6['ID'].'">Удалить</a></li>
			</div>';
	}

	echo '<div class = "main_links">
			<a href = "/tests.php?page=add_question&test_id='.$test_id.'">Добавить вопрос</a></br>
			<a href = "/tests.php?page=edit_results&test_id='.$test_id.'">Диапазон результатов</a></br>
			<a href = "/tests.php?page=list">Вернуться к списку тестов</a></br>
			<a href = "/tests.php">Назад</a></br>
			</div>';

break;


//Редактор диапазона
case 'edit_results':

	$test_id = $_GET['test_id'];
	
	$mysql_result1 = mysql_query("SELECT * FROM `TESTS_RESULTS` WHERE `TEST_ID` = '$test_id'");
	while ($mass6 = mysql_fetch_array($mysql_result1)) {
		echo '<div class = "results"><ul>
		<li>Текст:  ' .$mass6['TEXT'] . '</li>
		<li>Минимальный диапазон:  ' .$mass6['RESULT_MIN'] . '</li>
		<li>Максимальный диапазон:  ' .$mass6['RESULT_MAX'] . '</li>
		<li><a href = "/tests.php?page=del_result&test_id='.$test_id.'&result_id='.$mass6['ID'].'">Удалить</a></li>
		</ul></div>
		';
	}
	
	echo '<div class = "input_field"><p>Добавить диапазон</p></div>
	<div class = "input_field"><form method="post" action="">
		<p>Текст</p>
		<textarea rows = "3" cols = "21" name="res_text" value="" maxlength="300"></textarea>
		<p>Минимум</p>
		<input class = "input_text" type="text" name="min" value="" maxlength="3"></input>
		<p>Максимум</p>
		<input class = "input_text" type="text" name="max" value="" maxlength="3"></input>
		</br></br>
		<input type="submit" name="add_res" value="Добавить диапазон"></input>
		</form></div>
		';
		
		$add_res = $_POST['add_res'];
		if (isset($add_res)) {
		
			$res_text = $_POST['res_text'];
			$res_min = $_POST['min'];
			$res_max = $_POST['max'];
				
			mysql_query("INSERT INTO `TESTS_RESULTS` (`ID`,`TEST_ID`,`TEXT`,`RESULT_MIN`,`RESULT_MAX`) VALUES ('','$test_id','$res_text','$res_min','$res_max');") or die (mysql_error());
			
			header("Location: /tests.php?page=edit_results&test_id=$test_id");
		
		}

	
	echo '<div class = "main_links">
			<a href = "/tests.php?page=edit&test_id='.$test_id.'">Вернуться к редактированию теста</a></br>
			<a href = "/tests.php?page=list">Вернуться к списку тестов</a></br>
		</div>';

break;

//Удаление вопроса теста
case 'del_result':

	$result_id = $_GET['result_id'];
	$test_id = $_GET['test_id'];

	echo '<div class = "input_field"><form method="post" action=""><input type="submit" name="del_result" value="Подтвердить удаление"></input></form></div><div class = "main_links"><a href = "/tests.php?page=edit_results&test_id='.$test_id.'">Отменить удаление</a></br></div>';

	$del_result = $_POST['del_result'];
	if (isset($del_result)) {
		
		mysql_query("DELETE FROM TESTS_RESULTS WHERE ID = '$result_id'");
		
		header("Location: /tests.php?page=edit_results&test_id=$test_id");
	}
	
break;

case 'add_question':

	$test_id = $_GET['test_id'];
	$mysql_result = mysql_query("SELECT * FROM `TESTS` WHERE `ID` = '$test_id'");
	
	echo '
		
		<script>
		
		var countOfAFields = 2;
		var curFieldANameId = 2;
		var maxFieldALimit = 20;
		
		function deleteAField(a) {
		 var contDiv = a.parentNode;
		 contDiv.parentNode.removeChild(contDiv);
		 countOfAFields--;
		 curFieldANameId--;
		 return false;
		}
		function addAField() {
		 if (countOfAFields >= maxFieldALimit) {
		 alert("Число полей достигло своего максимума = " + maxFieldALimit);
		 return false;
		 }
		 countOfAFields++;
		 curFieldANameId++;
		 var div = document.createElement("div");
		 div.innerHTML = "<table width=\"100\" border=\"0\"><tr><td><input class = \"input_text\" type=\"text\" name=\"test_answ_" + curFieldANameId + "\" value=\"\" maxlength=\"40\"></input></td><td><input class = \"input_text\" type=\"text\" name=\"val_" + curFieldANameId + "\" value=\"\" maxlength=\"40\"></input></td></tr></table><a onclick=\"return deleteAField(this)\" href=\"#\">[X]</a>";
		 document.getElementById("answers").appendChild(div);
		 return false;
		}
		
		</script>';
		
		echo '
		
		<div class = "input_field"><form method="post" action="">
		<p>Вопрос</p>
		<textarea rows = "3" cols = "21" name="test_quest" value="" maxlength="300"></textarea>
		</br></br>
		<div id = "answers">
		<table width="100" border="0">
			<tr>
				<td><p>Вариант ответа</p></td>
				<td><p>Значение</p></td>
			</tr>
			<tr>
				<td><input class = "input_text" type="text" name="test_answ_1" value="" maxlength="40"></input></td>
				<td><input class = "input_text" type="text" name="val_1" value="" maxlength="40"></input></td>
			</tr>
		</table>
		<table width="100" border="0">
			<tr>
				<td><input class = "input_text" type="text" name="test_answ_2" value="" maxlength="40"></input></td>
				<td><input class = "input_text" type="text" name="val_2" value="" maxlength="40"></input></td>
			</tr>
		</table>
		
		</div>
		<a onclick="return addAField()" href="#">Добавить вариант</a>
		</br></br>
		<input type="submit" name="add_quest" value="Добавить вопрос"></input>
		
		</div></form>
		';
		
	$add_quest = $_POST['add_quest'];
	if (isset($add_quest)) {
						
		$current_add_time = time(U);
		$quest_text = $_POST['test_quest'];
				
		mysql_query("INSERT INTO `TESTS_QUESTIONS` (`ID`,`TEST_ID`,`QTEXT`,`CREATION_TIME`) VALUES ('','$test_id','$quest_text','$current_add_time');") or die (mysql_error());
				
		$id_query = mysql_query("SELECT ID FROM TESTS_QUESTIONS WHERE CREATION_TIME = '$current_add_time'");
		while ($query_id = mysql_fetch_array($id_query)) {
			$quest_id = $query_id['ID'];
		}
		
		foreach ($_POST as $key => $value) {
				
			if (preg_match("/answ/", $key)) mysql_query("INSERT INTO `TESTS_ANSWERS` (`ID`,`QUEST_ID`,`ATEXT`,`AVALUE`) VALUES ('','$quest_id','$value','');") or die (mysql_error());
				
			$last_answ_id = mysql_insert_id();
				
			if (preg_match("/val/", $key)) mysql_query("UPDATE `TESTS_ANSWERS` SET `AVALUE` = '$value' WHERE id = '$last_answ_id'") or die (mysql_error());

			}
		
		header("Location: /tests.php?page=edit&test_id=$test_id");
						
	}

break;


//Удаление вопроса теста
case 'del_quest':

	$quest_id = $_GET['id'];
	$test_id = $_GET['test_id'];

	echo '<div class = "input_field"><form method="post" action=""><input type="submit" name="del_quest" value="Подтвердить удаление"></input></form></div><div class = "main_links"><a href = "/tests.php?page=edit&test_id='.$test_id.'">Отменить удаление</a></br></div>';

	$del_quest = $_POST['del_quest'];
	if (isset($del_quest)) {
		
		mysql_query("DELETE FROM TESTS_ANSWERS WHERE QUEST_ID = '$quest_id'");
		mysql_query("DELETE FROM TESTS_QUESTIONS WHERE ID = '$quest_id'");
		
		header("Location: /tests.php?page=edit&test_id=$test_id");
	}
	
break;

//Удаление теста
case 'del_test':

	$test_id = $_GET['test_id'];

	echo '<div class = "input_field"><form method="post" action=""><input type="submit" name="del_test" value="Подтвердить удаление"></input></form></div><div class = "main_links"><a href = "/tests.php?page=list">Отменить удаление</a></br></div>';

	$del_test = $_POST['del_test'];
	if (isset($del_test)) {
		
		$select_quest_to_del = mysql_query("SELECT * FROM TESTS_QUESTIONS WHERE TEST_ID = '$test_id'");
		while ($query_data = mysql_fetch_array($select_quest_to_del)) {
			$quest_id = $query_data['ID'];
			mysql_query("DELETE FROM TESTS_ANSWERS WHERE QUEST_ID = '$quest_id'");
			mysql_query("DELETE FROM TESTS_QUESTIONS WHERE ID = '$quest_id'");
		}
		
		mysql_query("DELETE FROM TESTS_RESULTS WHERE TEST_ID = '$test_id'");
		mysql_query("DELETE FROM TESTS WHERE ID = '$test_id'");
		
		header("Location: /tests.php?page=list");
	}
	
break;

//Список тестов + главная
default:
	
	echo '<div class = "input_field"><p>Список тестов</p></div>';
	
	$test_query_list = "SELECT * FROM TESTS";
	$test_entry = mysql_query($test_query_list);
	
		while ($mass5 = mysql_fetch_array($test_entry)) {
			$creation_time = $mass5['CREATION_TIME'];
			echo '<div class = "results"><ul>
			<li>ID Теста: ' .$mass5['ID'] . '</li>
			<li>Название:  ' .$mass5['NAME'] . '</li>
			<li>Текст:  ' .$mass5['TEXT'] . '</li>
			<li>Дата создания:  ' . date('d.m.Y в G:i', $creation_time) . '</li>
			<li><a href = "/tests.php?page=edit&test_id='.$mass5['ID'].'">Изменить</a></li>
			<li><a href = "/tests.php?page=del_test&test_id='.$mass5['ID'].'">Удалить</a></li>
			</ul></div>
			';
		}

}

include('./template/footer.php');

?>
