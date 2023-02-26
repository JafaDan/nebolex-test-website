<?php

$page_title = "Nebolex - Личный кабинет";

include('./template/header.php');
/*include('./template/sidebar.php');*/

$pagesel = trim($_GET['page']);
$ses = $_SESSION['login'];
$user_name = $_SESSION['name'];

/*function cmp_formula($a, $b) { 			
	if ($a['priority'] != $b['priority']) 			
	return $a['priority'] > $b['priority']; 			
	return $a['name'] > $b['name']; 			
} 			
			
function cmp_component($a, $b) { 			
	if ($a['priority'] != $b['priority']) 			
	return $a['priority'] > $b['priority']; 			
	return $a['name'] > $b['name']; 			
}

*/

if ((!(isset($_SESSION['login']))) && (!(isset($_SESSION['key']))) ) {
	header("Location: /index.php?page=user");
}

switch($pagesel) {

/*	
//Вывод результатов анализов	
case 'results':

    # получаем данные по заданому результату анализов (data[COMP_ID] = value)
	$data = array();
	$analyze_id = $_GET['analyze_id'];
	$mysql_result = mysql_query("SELECT * FROM `ANALYZES_DATA` WHERE `ANALYZE_ID` = '$analyze_id'");
		
	while ($mysql_row = mysql_fetch_array($mysql_result))
		$data[$mysql_row['COMPONENT_ID']] = $mysql_row['DATA'];

	# получаем значения всех компонентов. 
	$components = array();
	$mysql_result = mysql_query("SELECT * FROM `COMPONENTS`");
		
	while ($mysql_row = mysql_fetch_array($mysql_result)) {
		$components[$mysql_row['ID']] = array();
		foreach ($mysql_row as $key => $value) {
			if ($key == 'ID')
				continue;
			$components[$mysql_row['ID']][$key] = $value;
		}
	}
	
	#получаем список всех формул в нужном порядке
	$formulas = array();
	$mysql_result = mysql_query("SELECT * FROM `FORMULS` ORDER BY `FORM_PRIORITY` ASC");
	while ($mysql_row = mysql_fetch_array($mysql_result)) {
		$tmp = array();
		foreach ($mysql_row as $key => $value) 
			$tmp[$key] = $value;
		$formulas[] = $tmp;
	}
	
	#выводим всписок формул:
	#заголовок:
	echo '<div class = "main_links"><a href = "/user.php?page=list">Назад</a></br>
	<a href = "/user.php?page=count&analyze_id='.$analyze_id.'">Подсчитать</a></br>
	</div>
	<div class = "input_field"><p>Результаты анализов</p></div>
		<div class = "input_field">
			<table width="100" class = "result_table"><tr>
				<td>Формула</td>
				<td>Приоритетность</td>
				<td>Значение числителя</td>
				<td>Значение знаменателя</td>
				<td>Результат</td>
				<td>Должен быть в пределах</td>
				<td>Вывод</td>
			</tr>';
	
	#данные:
	
	$result = array();  
	
	foreach ($formulas as $formula) {
		$comp_id_1 = $formula['FORM_CODE_1'];
		$comp_id_2 = $formula['FORM_CODE_2'];
		
		if (!array_key_exists($comp_id_1, $data) || !array_key_exists($comp_id_2, $data))
			continue;
		
		$name = $components[$comp_id_1]['COMP_NAME'] . '/' . $components[$comp_id_2]['COMP_NAME'];
		$priority = $formula['FORM_PRIORITY'];
		$value_1 = $data[$comp_id_1];
		$value_2 = $data[$comp_id_2];
		$total_value = $value_1 / $value_2;
		$range = $formula['FORM_MIN'] . '-' . $formula['FORM_MAX'];
		if ($total_value > $formula['FORM_CRIT_MAX'])
			$is_crit = "<td style = 'background: #CE3533; color: #FFB3B1;'>Критическое";
		else if ($total_value < $formula['FORM_CRIT_MIN'])
			$is_crit = "<td style = 'background: #003989; color: #C3E4FF;'>Критическое";
		else if ($total_value > $formula['FORM_MAX'])
			$is_crit = "<td style = 'background: #FFA500; color: #FFFFCA;'>Некритическое";
		else if ($total_value < $formula['FORM_MIN'])
			$is_crit = "<td style = 'background: #5E9CDF; color: #C3E4FF;'>Некритическое";
		else 
			$is_crit = "<td style = 'background: green; color: #A2F596;'>Некритическое";
		if (($components[$comp_id_1]['COMP_TOXIC'] == 'Да') || ($components[$comp_id_2]['COMP_TOXIC'] == 'Да'))
			$is_toxic = 'токсичное';
		else
			$is_toxic = 'нетоксичное';
		$comment = $is_crit.' '.$is_toxic.' соотношение</td>';
		
				 $tmp = array(); 	
					$tmp['name'] = $name; 			
					$tmp['priority'] = $priority; 			
					$tmp['value_1'] = $value_1; 			
					$tmp['value_2'] = $value_2; 			
					if ($total_value - floor($total_value) < 0.00001) 			
					   $tmp['total_value'] = $total_value; 			
					else 			
					$tmp['total_value'] = number_format ($total_value, 4); 			
					 $tmp['range'] = $range; 			
					$tmp['comment'] = $comment; 			
					$result[] = $tmp; 			
					} 			
					usort($result, "cmp_formula"); 			
					foreach ($result as $row) 			
					{ 
		
		echo 
			'<tr>
				<td>'.$row['name'].'</td>
				<td>'.$row['priority'].'</td>
				<td>'.$row['value_1'].'</td>
				<td>'.$row['value_2'].'</td>
				<td>'.$row['total_value'].'</td>
				<td>'.$row['range'].'</td>'
				.$row['comment'].'
			<tr>';
	}
	#конец таблицы
	echo '</table></div></br>';
		
		
	#таблица компонентов
	#заголовок
	echo '<div class = "input_field"><p>Результаты компонентов</p></div>
		<div class = "input_field">
		<table width="100" class = "result_table">
			<tr>
				<td>Элемент</td>
				<td>Приоритетность</td>
				<td>Значение</td>
				<td>Должен быть в пределах</td>
				<td>Вывод</td>
			</tr>';
	
	#получаем список компонент в нужном порядке
	$sorted_componets = array();
	$mysql_result = mysql_query("SELECT * FROM `COMPONENTS` ORDER BY `COMP_PRIORITY` ASC");
	while ($mysql_row = mysql_fetch_array($mysql_result)) {
		$sorted_componets[] = $mysql_row['ID'];
	}

	
	$result = array();     
	foreach ($sorted_componets as $comp_id)
	{
		if (!array_key_exists($comp_id, $data))
			continue;
		
		$name = $components[$comp_id]['COMP_NAME'];
		$priority = $components[$comp_id]['COMP_PRIORITY'];
		$value = $data[$comp_id];
		$range = $components[$comp_id]['COMP_MIN'] . '-' . $components[$comp_id]['COMP_MAX'];
		
		if ($value < $components[$comp_id]['COMP_CRIT_MIN'])
			$is_crit = "<td style = 'background: #003989; color: #C3E4FF;'>Критический";
		else if ($value > $components[$comp_id]['COMP_CRIT_MAX'])
			$is_crit = "<td style = 'background: #CE3533; color: #FFB3B1;'>Критический";
		else if ($value < $components[$comp_id]['COMP_MIN'])
			$is_crit = "<td style = 'background: #5E9CDF; color: #C3E4FF;'>Некритический";
		else if ($value > $components[$comp_id]['COMP_MAX'])
			$is_crit = "<td style = 'background: #FFA500; color: #FFFFCA;'>Некритический";
		else
			$is_crit = "<td style = 'background: green; color: #A2F596;'>Некритический";
		
		if ($components[$comp_id]['COMP_TOXIC'] == 'Да')
			$is_toxic = 'токсичный';
		else
			$is_toxic = 'нетоксичный';
		
		$comment = $is_crit . ' ' . $is_toxic;
		
		$tmp = array(); 
		
		$tmp['name'] = $name;
		$tmp['priority'] = $priority;
		$tmp['value'] = $value;
		$tmp['range'] = $range;
		$tmp['comment'] = $comment;
		
		$result[] = $tmp;
		

	}

	 usort($result, "cmp_component"); 	
		
		foreach ($result as $row) { 			
			echo
				'<tr> 			
				<td>'.$row['name'].'</td> 			
				<td>'.$row['priority'].'</td> 			
				<td>'.$row['value'].'</td> 			
				<td>'.$row['range'].'</td>' 			
				.$row['comment'].' элемент</td> 			
				<tr>'; 			
		}
	
	echo '</table></div><div class = "main_links">
				<a href = "/user.php?page=list">Назад</a></br>
		  </div>';
		
	
    break;
*/


//Тестовая страница подсчетов Добавить/Удалить
/*case 'count':

    # получаем данные по заданому результату анализов (data[COMP_ID] = value)
	$data = array();
	$analyze_id = $_GET['analyze_id'];
	$mysql_result = mysql_query("SELECT * FROM `ANALYZES_DATA` WHERE `ANALYZE_ID` = '$analyze_id'");
		
	while ($mysql_row = mysql_fetch_array($mysql_result))
		$data[$mysql_row['COMPONENT_ID']] = $mysql_row['DATA'];
	
	# получаем значения всех компонентов. 
	$components = array();
	$mysql_result = mysql_query("SELECT * FROM `COMPONENTS`");
		
	while ($mysql_row = mysql_fetch_array($mysql_result)) {
		$components[$mysql_row['ID']] = array();
		foreach ($mysql_row as $key => $value) {
			if ($key == 'ID')
				continue;
			$components[$mysql_row['ID']][$key] = $value;
		}
	}

	#таблица компонентов
	#заголовок
	echo '<div class = "main_links">
				<a href = "/user.php?page=results&analyze_id='.$analyze_id.'">Назад</a></br>
		  </div>
		  <div class = "input_field"><p>Рассчет по компонентам</p></div>
		<div class = "input_field" style = "overflow: hidden;">';
	
	#получаем список компонент в нужном порядке
	$sorted_componets = array();
	$mysql_result = mysql_query("SELECT * FROM `COMPONENTS` ORDER BY `COMP_PRIORITY` ASC");
	while ($mysql_row = mysql_fetch_array($mysql_result)) {
		$sorted_componets[] = $mysql_row['ID'];
	}
	
	$result = array();     
	foreach ($sorted_componets as $comp_id)
	{
		if (!array_key_exists($comp_id, $data))
			continue;
		
		$name = $components[$comp_id]['COMP_NAME'];
		$priority = $components[$comp_id]['COMP_PRIORITY'];
		$value = $data[$comp_id];
		$mark = '';
		
		if (($value < $components[$comp_id]['COMP_MIN']) && ($value > $components[$comp_id]['COMP_CRIT_MIN']))
			$is_crit = 1;
		else if (($value > $components[$comp_id]['COMP_MAX']) && ($value < $components[$comp_id]['COMP_CRIT_MAX']))
			$is_crit = 2;
		
		else if ($value < $components[$comp_id]['COMP_CRIT_MIN']) {
			$is_crit = 1;
			$mark = 'style = "background: #DAF4D4;"';
		}
		
		else if ($value > $components[$comp_id]['COMP_CRIT_MAX']) {
			$is_crit = 2;
			$mark = 'style = "background: #EEF4C9;"';
		}
		
		else $is_crit = 0;
		
		$check = $is_crit;
		
		$tmp = array(); 
		
		$tmp['name'] = $name;
		$tmp['priority'] = $priority;
		$tmp['value'] = $value;
		$tmp['check'] = $check;
		$tmp['mark'] = $mark;
		
		$result[] = $tmp;
	}

	usort($result, "cmp_component"); 
			
		echo '<table class = "count_table">
			<tr><td style = "background: #5E9CDF; color: #C3E4FF;">Добавить</td></tr>';			
			foreach ($result as $row) {
				if ($row['check'] == 1)
				echo '<tr><td '.$row['mark'].'>'.$row['name'].'</td></tr>';
			}			
			echo '</table>';
			
		echo '<table class = "count_table">
			<tr><td style = "background: #FFA500; color: #FFFFCA;">Удалить</td></tr>';			
			foreach ($result as $row) {
				if ($row['check'] == 2)
				echo '<tr><td '.$row['mark'].'>'.$row['name'].'</td></tr>';
			}			
			echo '</table>';	
		
	echo '</div>';
	
	
	#получаем список всех формул в нужном порядке
	$formulas = array();
	$mysql_result = mysql_query("SELECT * FROM `FORMULS` ORDER BY `FORM_PRIORITY` ASC");
	while ($mysql_row = mysql_fetch_array($mysql_result)) {
		$tmp = array();
		foreach ($mysql_row as $key => $value) 
			$tmp[$key] = $value;
		$formulas[] = $tmp;
	}
	
	#выводим всписок формул:
	#заголовок:
	echo '
	<div class = "input_field"><p>Рассчет по формулам</p></div>
		<div class = "input_field" style = "overflow: hidden;">';
			
	#данные:
	
	$result = array();  
	
	foreach ($formulas as $formula) {
		$comp_id_1 = $formula['FORM_CODE_1'];
		$comp_id_2 = $formula['FORM_CODE_2'];
		
		if (!array_key_exists($comp_id_1, $data) || !array_key_exists($comp_id_2, $data))
			continue;
		
			$name = $components[$comp_id_1]['COMP_NAME'] . '/' . $components[$comp_id_2]['COMP_NAME'];
			$priority = $formula['FORM_PRIORITY'];
			
			$value_1_name = $components[$comp_id_1]['COMP_NAME'];
			$value_1 = $data[$comp_id_1];
			$value_1_mid = ($components[$comp_id_1]['COMP_MIN'] + $components[$comp_id_1]['COMP_MAX']) / 2;
			$value_1_toxic = $components[$comp_id_1]['COMP_TOXIC'];
			
			$value_2_name = $components[$comp_id_2]['COMP_NAME'];
			$value_2 = $data[$comp_id_2];
			$value_2_mid = ($components[$comp_id_2]['COMP_MIN'] + $components[$comp_id_2]['COMP_MAX']) / 2;
			$value_2_toxic = $components[$comp_id_2]['COMP_TOXIC'];
			
			$formula_min = $formula['FORM_MIN'];
			$formula_max = $formula['FORM_MAX'];
			$formula_result = $value_1 / $value_2;

		
		#Условия:
		#0 - токсичный, в кр. зоне хотя бы один элемент, добавляем нетоксичный в добавить
		
		#1 - если числитель меньше среднего значения компонента, добавляем
		#2 - если числитель больше ср.знач. компонента, знаменатель больше ср.знач., то уменьшаем знаменатель (убрать знач. #знаменатель)
		#3 - если числитель больше ср.зн., знаменатель меньше ср.зн., то добавляем числитель и красим в желтый
		
		#4 - если знаменатель меньше ср.зн., то добавляем
		#5 - если знаменатель больше ср.зн. и числитель больше ср.зн., то убираем числитель
		#6 - если знаменатель больше ср.зн., а числитель меньше ср. зн., то убираем знаменатель и красим в синий
		
		
		$check_1 = 3;
		$check_2 = 3;
		$is_toxic_1 = 0;
		$is_toxic_2 = 0;
		$mark = '';
		
		
		
		#0 - Удалить
		#1 - Добавить
		#2 - Не добавляем/не удаляем
		#3 - игнор
		
		
			if ($components[$comp_id_1]['COMP_TOXIC'] == 'Да') $is_toxic_1 = 1;
			if ($components[$comp_id_2]['COMP_TOXIC'] == 'Да') $is_toxic_2 = 1;

			if (($formula_result > $formula_max) && ($is_toxic_1 == 0) && ($is_toxic_2 == 0)) {
				if (($value_1 < $value_1_mid) && ($value_2 > $value_2_mid)) {
					$check_1 = 0;
					$check_2 = 2;
				}
				elseif (($value_1 > $value_1_mid) && ($value_2 > $value_2_mid)) {
					$check_1 = 2;
					$check_2 = 1;
				}
				elseif (($value_1 > $value_1_mid) && ($value_2 < $value_2_mid)) {
					$check_1 = 2;
					$check_2 = 1;
					$mark = 'style = "background: #E8C808;"';
				}
			}
				
			if (($formula_result < $formula_min) && ($is_toxic_1 == 0) && ($is_toxic_2 == 0)) {
				if (($value_2 < $value_2_mid) && ($value_1 > $value_1_mid)) {
					$check_1 = 0;
					$check_2 = 2;
				}
				elseif (($value_2 > $value_2_mid) && ($value_1 > $value_1_mid)) {
					$check_1 = 2;
					$check_2 = 0;
				}
				elseif (($value_2 > $value_2_mid) && ($value_1 < $value_1_mid)) {
					$check_1 = 2;
					$check_2 = 0;
					$mark = 'style = "background: #ACC0F4;"';
				}
			}
		
		$tmp = array();
			$tmp['name'] = $name;
			$tmp['priority'] = $priority; 			
			$tmp['value_1_name'] = $value_1_name;
			$tmp['value_2_name'] = $value_2_name;
			$tmp['check_1'] = $check_1;
			$tmp['check_2'] = $check_2;
			$tmp['toxic_1'] = $is_toxic_1;
			$tmp['toxic_2'] = $is_toxic_2;
			$tmp['mark'] = $mark;
			
			$result[] = $tmp;
	}
		
		usort($result, "cmp_formula"); 
		
		echo '<table class = "count_table" style = "width: 100%;">
			<tr>
			<td style = "width: 30%; background: #8DF598;">Формула</td>
			<td style = "background: #5E9CDF; color: #C3E4FF;">Добавить</td>
			<td style = "background: #FFA500; color: #FFFFCA;">Удалить</td>
			</tr>';
			
			foreach ($result as $row) {
				
				#Вывод по нетоксичным формулам
				
				if (($row['check_1'] != 3) && ($row['check_2'] != 3)) {
					echo '<tr><td style = "background: #DAF4D4;">'.$row['name'].'</td>';
						echo '<td>';
							if ($row['check_1'] == 1) echo '<span '.$row['mark'].'>'.$row['value_1_name'].'</span></br>';
							if ($row['check_2'] == 1) echo '<span '.$row['mark'].'>'.$row['value_2_name'].'</span></br>';
						echo '</td>';
						echo '<td>';
							if ($row['check_1'] == 0) echo '<span '.$row['mark'].'>'.$row['value_1_name'].'</span></br>';
							if ($row['check_2'] == 0) echo '<span '.$row['mark'].'>'.$row['value_2_name'].'</span></br>';
						echo '</td>';
					echo '</tr>';
				}
				
				#Вывод по токсичным формулам
				
				if (($row['toxic_1'] == 1) || ($row['toxic_2'] == 1)) {
					echo '<tr><td style = "background: #EEF4C9;">'.$row['name'].'</td>';
						echo '<td>';
							if ($row['toxic_1'] == 0) echo $row['value_1_name'].'</br>';
							if ($row['toxic_2'] == 0) echo $row['value_2_name'].'</br>';
						echo '</td><td>';
							if ($row['toxic_1'] == 1) echo $row['value_1_name'].'</br>';
							if ($row['toxic_2'] == 1) echo $row['value_2_name'].'</br>';
						echo '</td>';
					echo '</tr>';
				}
			}
			
			echo '</table>';

	#конец таблицы
	echo '</div>';
	
	
	echo '<div class = "main_links">
		<a href = "/user.php?page=results&analyze_id='.$analyze_id.'">Назад</a></br>
		</div>';
		  
break;


//Страница добавления данных по новому анализу
case 'user_add_analys':

	$comp_query_list = "SELECT ID, COMP_CODE, COMP_NAME FROM COMPONENTS ORDER BY COMP_NAME ASC";
	$form_entry = mysql_query($comp_query_list);
			
		echo '
			<div class = "input_field"><p>Добавить анализ</p></div>
			<div class = "input_field">
				<form method="post" action="">';
				while ($mass5 = mysql_fetch_array($form_entry)) {
					$check_co = $mass5['ID'];
							echo '<p>'.$mass5['COMP_NAME'].'</p><input type="text" class = "input_nums" name="'.$mass5['ID'].'" value="" maxlength="7">';
					}
				
		echo '</br></br><input type="submit" name="add_analys" value="Подтвердить"></input></form></div><div class = "input_field"></div><div class = "main_links">
				<a href = "/user.php">Назад</a></br>
			</div>';
			
	$add_analys = $_POST['add_analys'];
	
	if(isset($add_analys)) {
	
	$current_add_time = time(U);
	$change_time = time(U);
	
			mysql_query("INSERT INTO `ANALYZES` (`ID`,`USER_ID`,`CREATION_TIME`,`CHANGE_TIME`) VALUES ('','$ses','$current_add_time','$change_time');") or die (mysql_error());
		
			foreach($_POST as $key => $value) {							
				if (($key != "add_analys") && ($value != "Подтвердить") && ($value != 0)) {
					$value = str_replace(",",".",$value);
					$id_query = mysql_query("SELECT ID FROM ANALYZES WHERE USER_ID = '$ses' AND CREATION_TIME = '$current_add_time'");
					while ($query_id = mysql_fetch_array($id_query)) {
						$analyz_id = $query_id['ID'];
					}
					
					mysql_query("INSERT INTO `ANALYZES_DATA` (`ID`,`ANALYZE_ID`,`COMPONENT_ID`,`DATA`) VALUES ('','$analyz_id','$key','$value');") or die (mysql_error());
				}
			}
			
		header("Location: /user.php?page=list");
			
	}
	
break;


//Список всех анализов
case 'list':

	$comp_query_list = "SELECT * FROM ANALYZES WHERE USER_ID = '$ses'";
	$form_entry = mysql_query($comp_query_list);
			
		echo '<div class = "input_field"><p>Список анализов</p></div>';
			while ($mass5 = mysql_fetch_array($form_entry)) {
				$creation_time = $mass5['CREATION_TIME'];
				$change_time = $mass5['CHANGE_TIME'];
				echo '<div class = "results"><ul>
				<li>ID Анализа: ' .$mass5['ID'] . '</li>
				<li>Дата внесения:  ' . date('d.m.Y в G:i', $creation_time) . '</li>
				<li>Дата изменения:  ' . date('d.m.Y в G:i', $change_time) . '</li>
				<li><a href = "/user.php?page=results&analyze_id=' .$mass5['ID'] . '">Посмотреть результаты</a></li>
				</ul></div>
				';
			}
			
			echo '<div class = "main_links">
				<a href = "/user.php">Назад</a></br>
			</div>';

break;


//Вывод тестов, доступных для прохождения
case 'tests':

	echo '<div class = "input_field"><p>Список доступных для прохождения тестов</p></div>';
		
	$test_query_list = "SELECT * FROM TESTS";
	$test_entry = mysql_query($test_query_list);
			
	while ($mass5 = mysql_fetch_array($test_entry)) {
		$creation_time = $mass5['CREATION_TIME'];
		echo '<div class = "results"><ul>
			<li>ID Теста: '.$mass5['ID'].'</li>
			<li>Название:  '.$mass5['NAME'].'</li>
			<li><a href = "/user.php?page=test&id='.$mass5['ID'] .'">Пройти тест</a></li>
			</ul></div>';
	}
	
	echo '<div class = "main_links">
				<a href = "/user.php">Назад</a></br>
	</div>';

break;


//Список пройденых тестов и результаты
case 'tests_res':

	echo '<div class = "input_field"><p>Результаты тестов</p></div>';
	
	$tests_name = array();
	$test_entry = mysql_query("SELECT ID,NAME FROM TESTS");
	while ($mass6 = mysql_fetch_assoc($test_entry)) {
	foreach ($mass6 as $id => $name)
		$tmp[$id] = $name;
		$tests_name[] = $tmp;
	}
	$kss = count($tests_name);

	$result_texts = array();
	$test_entry = mysql_query("SELECT * FROM TESTS_RESULTS");
	while ($mass7 = mysql_fetch_assoc($test_entry)) {
	foreach ($mass7 as $key => $value)
		$tmp0[$key] = $value;
		$result_texts[] = $tmp0;
	}
	$pss = count($result_texts);

	
	$test_query_list = "SELECT * FROM TESTS_USERS WHERE USER_ID = '$ses'";
	$test_entry = mysql_query($test_query_list);	
	while ($mass5 = mysql_fetch_array($test_entry)) {
	
		$test_id = $mass5['TEST_ID'];
		$total_rank = $mass5['TOTAL_VALUE'];
		$comp_time = $mass5['COMPLETE_DATA'];
		
		for ($i = 0; $i <= $kss; $i++) {
			if ($tests_name[$i]['ID'] == $test_id) $test_name = $tests_name[$i]['NAME'];
		}
		
		for ($i = 0; $i <= $pss; $i++) {
			if (($result_texts[$i]['TEST_ID'] == $test_id) && ($result_texts[$i]['RESULT_MIN'] <= $total_rank) && ($result_texts[$i]['RESULT_MAX'] >= $total_rank))
				$res_text = $result_texts[$i]['TEXT'];
		}
		
		echo '<div class = "results"><ul>
		<li>ID Теста: '.$mass5['ID'].'</li>
		<li>Название:  '.$test_name.'</li>
		<li>Дата прохождения:  ' . date('d.m.Y в G:i', $comp_time) . '</li>
		<li>Вывод:  ' .$res_text. '</li>
		<li>Количество баллов:  ' .$total_rank. '</li>
		<li><a href = "/user.php?page=test&id='.$test_id.'">Пройти тест еще раз</a></li>
		</ul></div>';
	
	}
	
	echo '<div class = "main_links">
				<a href = "/user.php">Назад</a></br>
	</div>';

break;


//Страница прохождения теста
case 'test':

	$data = array();
	$test_id = $_GET['id'];
	$mysql_test = mysql_query("SELECT * FROM `TESTS` WHERE `ID` = '$test_id'");
	
	while ($test_info = mysql_fetch_array($mysql_test)) {
		echo '<div class = "results"><ul>
		<li>Название:  ' .$test_info['NAME']. '</li>
		<li>Заголовок:  ' .$test_info['TEXT']. '</li>
		</ul></div>';
	}
	
	echo '<div class = "input_field"><table width="80%" class = "result_table">
		<tr>
			<td style = "background: #5E9CDF; color: #C3E4FF;" width = "30%">Вопрос</td>
			<td style = "background: #3BC200; color: #fff;">Выбор ответа</td>
		</tr>
	</table><form method="post" action="">';
	
	$mysql_test_data_q = mysql_query("SELECT * FROM `TESTS_QUESTIONS` WHERE `TEST_ID` = '$test_id'");
	while ($test_info1 = mysql_fetch_array($mysql_test_data_q)) {
		echo '
		<table width="80%" class = "result_table">
			<tr>
				<td width = "30%">'.$test_info1['QTEXT'].'</td>';
		
		$quest_id = $test_info1['ID'];
		
		$mysql_test_data_a = mysql_query("SELECT * FROM `TESTS_ANSWERS` WHERE `QUEST_ID` = '$quest_id'");
		while ($test_info2 = mysql_fetch_array($mysql_test_data_a)) {
			echo '<td><input name="'.$test_info1['ID'].'" type="radio" value="'.$test_info2['AVALUE'].'">' .$test_info2['ATEXT'] . '</td>';
		}
		
		echo '</tr></table>';
		
	}
	
	echo '</br><input type="submit" name="end_test" value="Завершить тест"></input></form></div>';
	
	$end_test = $_POST['end_test'];
	if(isset($end_test)) {
		
		$total_rank = 0;
		$current_add_time = time(U);
		mysql_query("INSERT INTO `TESTS_USERS` (`ID`,`USER_ID`,`TEST_ID`,`COMPLETE_DATA`,`TOTAL_VALUE`) VALUES ('','$ses','$test_id','$current_add_time','');") or die (mysql_error());
		
		$id_query = mysql_query("SELECT ID FROM TESTS_USERS WHERE COMPLETE_DATA = '$current_add_time'");
		while ($query_id = mysql_fetch_array($id_query)) {
			$tests_id = $query_id['ID'];
		}
		
		foreach ($_POST as $key => $value) {
			if (!preg_match("/end_test/",$key)) {
				mysql_query("INSERT INTO `TESTS_DATA` (`ID`,`TEST_ID`,`QUEST_ID`,`VALUE`) VALUES ('','$tests_id','$key','$value');");
				$total_rank = $total_rank + $value;
			}
		}
		
		mysql_query("UPDATE `TESTS_USERS` SET `TOTAL_VALUE`='$total_rank' WHERE COMPLETE_DATA = '$current_add_time'");
		
		$query = mysql_query("SELECT * FROM TESTS_RESULTS WHERE RESULT_MIN <= '$total_rank' AND RESULT_MAX >= '$total_rank' AND TEST_ID = '$test_id'");
		while ($result_text = mysql_fetch_array($query)) {
			echo '<div class = "input_field">
			Вывод:</br>
			От '.$result_text['RESULT_MIN'].' до '.$result_text['RESULT_MAX'].' баллов (Вы набрали '.$total_rank.'): '.$result_text['TEXT'].'
			</div>';
		}
	}
		
	echo '<div class = "main_links"><a href = "/user.php?page=tests">Назад</a></br></div>';

break;
*/

//Главная страница пользователя
default:
		
	/*	$user_auth = "SELECT ID, LOGIN, NAME, SUBNAME, OTCH, DATE_REG FROM `USERS` WHERE LOGIN = '$ses'";
		
		$auth_query = mysql_query($user_auth);
		
		while ($mass1 = mysql_fetch_array($auth_query)) {
			
			$time_reg = $mass1['DATE_REG'];
			
				echo '<div class = "headers">Данные пользователя</div>
				<div class = "results">
				<ul>
				<li>ID: ' .$mass1['ID'] . '</li>
				<li>Номер телефона: ' .$mass1['LOGIN'] . '</li>
				<li>Имя: ' .$mass1['NAME'] . '</li>
				<li>Фамилия: ' .$mass1['SUBNAME'] . '</li>
				<li>Отчество: ' .$mass1['OTCH'] . '</li>
				<li>Дата регистрации: ' . date('d.m.Y', $time_reg) . '</li>
				</ul></div>';
		}*/
		
		echo "
		<section id='history'>
			<div class='container'>
				<div class='history'>
					<div class='history_menu clearfix'>
						<h3 class='history__prev float-left bebas'>
							Личный кабинет
						</h3>
						<h2 class='history__prev2 float-left'>
							/
						</h2>
						<h1 class='history__heading float-left'>
							История заказов
						</h1>
					</div>
				</div><div class='history__table'>";
		
		$orders_query = mysql_query("
		SELECT ORDERS.ID, ORDERS.USER_ID, ORDERS.DATE, ORDERS_DATA.TOVAR_ID, ORDERS_DATA.COUNT, ORDERS_DATA.COST, STORE.NAME, STORE.PHOTO2
		FROM ORDERS_DATA
		JOIN ORDERS ON ORDERS.ID = ORDER_ID
		JOIN STORE ON ORDERS_DATA.TOVAR_ID = STORE.ID
		WHERE ORDERS.USER_ID = '$ses'
		GROUP BY ORDERS.DATE
		ORDER BY ORDERS.DATE DESC 
		");
		
		while ($order = mysql_fetch_assoc($orders_query)) {
		
		echo '<div class="history__table_day clearfix">
						<img src="img/spring-active.png" alt="" class="history__table_icon float-left">
						<div class="history__table_data float-left">
							' . date('d.m.Y', $order['DATE']) . '
						</div>
						<div class="history__table_products float-left">
							<div class="history__table_product clearfix">
								<img src="content/images/'.$order['PHOTO2'].'" alt="" class="history__product_img float-left">
								<div class="history__table_products_name float-left bebas">
									'.$order['NAME'].'
								</div>
								<div class="history__table_amount float-left">
									'.$order['COUNT'].' уп.
								</div>
							</div>							
						</div>
						<div class="history__table_cost bebas">
							'.$order['COST'].' Р.
						</div>
					</div>';
		}
		
		echo "</div>
			</div></section>";
}

include('./template/footer.php');

?>