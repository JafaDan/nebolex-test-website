<?php

include('./template/head_adm.php');
include('./template/sidebar.php');

$pagesel = trim($_GET['page']);

switch($pagesel) {


case 'smstat':
	
	
	
break;

//Раздел магазина
case 'store_list':

	$store_query = "SELECT * FROM STORE";
	$store_entry = mysql_query($store_query);
			
		echo '<div class = "input_field"><p>Список товаров</p></div>';
			while ($store_list = mysql_fetch_array($store_entry)) {
				
				if ($store_list['STATUS'] == 0) $stat = "Ожидает размещения";
				if ($store_list['STATUS'] == 1) $stat = "Размещено в магазине";
				
				echo '<div class = "results"><ul>
				<li>ID товара: ' .$store_list['ID'] . '</li>
				<li>Название: ' .$store_list['NAME'] . '</li>
				<li>Описание: ' .$store_list['DESCRIPTION'] . '</li>
				<li>Цена: ' .$store_list['PRICE'] . '</li>
				<li>Количество: ' .$store_list['COUNT'] . '</li>
				<li>Статус: ' .$stat. '</li>
				<li>Фото #1:</br><img height = "150px" src="content/images/' . $store_list['PHOTO1'] . '" alt=""></li>
				<li>Фото #2:</br><img height = "150px" src="content/images/' . $store_list['PHOTO2'] . '" alt=""></li>
				<li>Опции: <a href = "admin.php?page=tovar_add&id='.$store_list['ID'].'">Разместить в магазине</a> | <a href = "admin.php?page=tovar_hide&id='.$store_list['ID'].'">Скрыть</a> | <a href = "admin.php?page=tovar_del&id='.$store_list['ID'].'">Удалить</a>
				</li>
				</ul></div>
				';
			}

break;

case 'tovar_add':
	
	$tovar_id = $_GET['id'];
	mysql_query("UPDATE `STORE` SET `STATUS` = '1' WHERE ID = '$tovar_id'");
	header("Location: /admin.php?page=store_list");
	
break;

case 'tovar_hide':

	$tovar_id = $_GET['id'];
	mysql_query("UPDATE `STORE` SET `STATUS` = '0' WHERE ID = '$tovar_id'");
	header("Location: /admin.php?page=store_list");
	
break;

case 'tovar_del':

	$tovar_id = $_GET['id'];
	
	$check_tovar = mysql_query("SELECT * FROM `STORE` WHERE `ID` = '$tovar_id'");
		while ($tovar_info = mysql_fetch_array($check_tovar)) {
		$photo1 = $tovar_info['PHOTO1'];
		$photo2 = $tovar_info['PHOTO2'];
		
		$path1 = 'content/images/' . $photo1;
		$path2 = 'content/images/' . $photo2;
		}
		
		unlink($path1);
		unlink($path2);
	mysql_query("DELETE FROM STORE WHERE ID = '$tovar_id'");
	header("Location: /admin.php?page=store_list");
	
break;

case 'add_in_store':

	echo '
			<div class = "input_field"><p>Добавить Товар</p></div>
			<div class = "input_field">
				<form method="post" action="admin.php?page=add_in_store" enctype="multipart/form-data">
					<p>Название</p>
						<input type="text" name="tovar_name" value="" maxlength="40"></input>
					<p>Описание</p>
						<textarea rows="10" cols="45" name="tovar_desc"></textarea>
					<p>Цена</p>
						<input type="text" name="tovar_cost" value="" maxlength="5"></input>
					<p>Количество товара</p>
						<input type="text" name="tovar_count" value="" maxlength="5"></input>
					<form method="post" action="basic.php" enctype="multipart/form-data">
					
					<p>Фото №1 (Макс. 2МБ)</p>
					  <label><input id="tov_photo_1" name="tov_photo_1" type="file" /></label>
					<p>Фото №2 (Макс. 2МБ)</p>
					  <label><input id="tov_photo_2" name="tov_photo_2" type="file" /></label>
					  
					</br></br>
						<input type="submit" name="add_tovar" value="Подтвердить"></input>
				</form>
			</div>';
			
		echo '
		<div class = "input_field">';
	
		$tovar_add = $_POST['add_tovar'];
	
	if (isset($tovar_add)) {
	
		$limit_size = 2*1024*1024; // 2 Mb
		$valid_format = array("jpeg", "jpg", "gif", "png");
		$error_array = array();
		$path_file = "content/images/";
		$rand_name1 = time() . '_' . rand(0, 9999);
		$rand_name2 = time() . 'c_' . rand(0, 9999);
		
		if (empty($_POST['tovar_name'])) {
				echo '<p>Поле Название не заполненно.</p>';
			}
		if (empty($_POST['tovar_desc'])) {
				echo '<p>Поле Описание не заполненно.</p>';
			}
		if (empty($_POST['tovar_cost'])) {
				echo '<p>Поле Цена не заполненно.</p>';
			}
		if (empty($_POST['tovar_count'])) {
				echo '<p>Поле Количество товара не заполненно.</p>';
			}
		else {
		
			$tovar_name = htmlspecialchars($_POST['tovar_name']);
			$tovar_desc = htmlspecialchars($_POST['tovar_desc']);
			$tovar_cost = htmlspecialchars($_POST['tovar_cost']);
			$tovar_count = htmlspecialchars($_POST['tovar_count']);
			$add_date = time();
			
			$check_tovar = mysql_query("SELECT `NAME` FROM `STORE` WHERE `NAME` = '$tovar_name'");
			$result = mysql_fetch_array($check_tovar);
				if (!empty($result)) {
					echo '<p>Товар с этим именем уже существует!</p>';
				}
				else {
					
				if ($_FILES) {
					if($_FILES["tov_photo_1"]["size"] > $limit_size){
						$error_array[] = "Размер первого фото превышает допустимый!";
					}
					if($_FILES["tov_photo_2"]["size"] > $limit_size){
						$error_array[] = "Размер второго фото превышает допустимый!";
					}
					$format_pr1 = explode('.', $_FILES["tov_photo_1"]["name"]);
					$format_pr2 = explode('.', $_FILES["tov_photo_2"]["name"]);
					$format1 = end($format_pr1);
					$format2 = end($format_pr2);
					if(!in_array($format1, $valid_format)){
						$error_array[] = "Формат первого файла не допустимый!";
					}
					if(!in_array($format2, $valid_format)){
						$error_array[] = "Формат второго файла не допустимый!";
					}
					if(empty($error_array)){
						if(is_uploaded_file($_FILES["tov_photo_1"]["tmp_name"])){
							move_uploaded_file($_FILES["tov_photo_1"]["tmp_name"], $path_file . $rand_name1 . ".$format1");
						}
						if(is_uploaded_file($_FILES["tov_photo_2"]["tmp_name"])){
							move_uploaded_file($_FILES["tov_photo_2"]["tmp_name"], $path_file . $rand_name2 . ".$format2");
						}
						else{
							$error_array[] = "Ошибка загрузки!";
						}
					}			
				}
				
				$tov_photo_1 = $rand_name1.'.'.$format1;
				$tov_photo_2 = $rand_name2.'.'.$format2;
				$status = 0;
				//Статусы:
				//0 - добавлен, но не опубликован
				//1 - добавлен, опубликован
				//2 - добавлен, опубликован, расположен на главной
				
				mysql_query("INSERT INTO `STORE` (`ID`,`NAME`,`DESCRIPTION`,`PRICE`,`COUNT`,`PHOTO1`,`PHOTO2`,`ADD_DATE`,`STATUS`) VALUES ('','$tovar_name','$tovar_desc','$tovar_cost','$tovar_count','$tov_photo_1','$tov_photo_2','$add_date','$status')");
				
				echo '<p>Товар успешно добавлен!</p>';
			}
		}
		
	}
	
	if(!empty($error_array)) {
		echo '<span style="color: red;">Файлы не загружены!</span><br/>';
		foreach($error_array as $one_error) {
			echo '<span style="color: red;">' . $one_error . '</span><br/>';
		}
	}
	
	if(empty($error_array) AND $_FILES) {
		echo '<span style="color: green;">Файлы успешно загружены!</span><br/>';
	}
	
	echo '</div><div class = "main_links"><a href = "/admin.php">Назад</a></br></div>';

break;



//Редактор элементов
case 'comp_editor':
		
		echo '<div class = "main_links">
			<a href = "/admin.php">Назад</a></br>
			</div>
		
		<div class = "input_field"><p>Добавить компонент</p></div>
			<div class = "input_field">';
			
			echo '<form method="post" action="">
				
				<table border="0">
				  <tr>
					<td><p>Название</p></td>
					<td><p>Ключ</p></td>
					<td><p>Приоритет</p></td>
					<td><p>Минимум</p></td>
					<td><p>Максимум</p></td>
					<td><p>Критич. минимум</p></td>
					<td><p>Крит. максимум</p></td>
					<td><p>Токсичен?</p></td>
					<td><p>Действие</p></td>
				  </tr>
				  <tr>
					<td>
					<input type="text" class = "input_text" name="comp_name" value="" maxlength="20"></input>
					</td><td>
					<input type="text" class = "input_nums" name="comp_code" value="" maxlength="3"></input>
					</td><td>
					<input type="text" class = "input_nums" name="comp_prior" value="" maxlength="10"></input>
					</td><td>
					<input type="text" class = "input_nums" name="comp_min" value="" maxlength="7"></input>
					</td><td>
					<input type="text" class = "input_nums" name="comp_max" value="" maxlength="7"></input>
					</td><td>
					<input type="text" class = "input_nums" name="comp_crit_min" value="" maxlength="7"></input>
					</td><td>
					<input type="text" class = "input_nums" name="comp_crit_max" value="" maxlength="7"></input>
					</td><td>
					<input type="checkbox" name = "comp_toxic" value="Да"/>
					</td><td>
					<input type="submit" name="comp_add" value="Добавить"></input>
					</td>
				  </tr>
				</table>
				</form>
			</div>';
			
			echo '
		<div class = "input_field">';
	
		$comp_add = $_POST['comp_add'];
	
		if (isset($comp_add)) {
	
			if ((empty($_POST['comp_name'])) || (empty($_POST['comp_code']))) {
				echo '<p>Заполните все поля!</p></br>';
			}
			else {
		
			$comp_name = htmlspecialchars($_POST['comp_name']);
			$comp_code = htmlspecialchars($_POST['comp_code']);
			$comp_prior = str_replace(",",".",htmlspecialchars($_POST['comp_prior']));
			$comp_min = str_replace(",",".",htmlspecialchars($_POST['comp_min']));
			$comp_max = str_replace(",",".",htmlspecialchars($_POST['comp_max']));
			$comp_crit_min = str_replace(",",".",htmlspecialchars($_POST['comp_crit_min']));
			$comp_crit_max = str_replace(",",".",htmlspecialchars($_POST['comp_crit_max']));
			$comp_toxic = htmlspecialchars($_POST['comp_toxic']);
			
			if ($comp_toxic != 'Да') {
				$comp_toxic = 'Нет';
			}
			
			$check_comp = mysql_query("SELECT `COMP_NAME`,`COMP_CODE` FROM `COMPONENTS` WHERE `COMP_NAME` = '$comp_name' AND `COMP_CODE` = '$comp_code'");
			$result = mysql_fetch_array($check_comp);
				
				if (!empty($result)) {
					echo '<p>Данный компонент уже существует!</p></br>';
				}
				else {
					
					mysql_query("INSERT INTO `COMPONENTS` (`ID` ,`COMP_NAME` ,`COMP_CODE`,`COMP_PRIORITY` ,`COMP_MIN` ,`COMP_MAX` ,`COMP_CRIT_MIN` ,`COMP_CRIT_MAX` ,`COMP_TOXIC`)
					VALUES ('', '$comp_name', '$comp_code','$comp_prior', '$comp_min', '$comp_max', '$comp_crit_min', '$comp_crit_max', '$comp_toxic');
					");
				
				header("Location: /admin.php?page=comp_editor");
				
				}
				
				
			}

		}

	echo "<script type='text/javascript'>
		function openbox(id){
		display = document.getElementById(id).style.display;
			if(display=='none'){
				document.getElementById(id).style.display='block';
				}
				else {
					document.getElementById(id).style.display='none';
				}
		}
		</script>";


		
			$comp_query = 'SELECT ID, COMP_NAME, COMP_CODE, COMP_PRIORITY, COMP_MIN, COMP_MAX, COMP_CRIT_MIN, COMP_CRIT_MAX, COMP_TOXIC FROM COMPONENTS ORDER BY COMP_PRIORITY, COMP_NAME ASC';
			$comp_entry = mysql_query($comp_query);
				
				while ($mass1 = mysql_fetch_array($comp_entry)) {
					echo '<table  class = "result_table"><tr>
					<td class = "input_text">' .$mass1['COMP_NAME'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_CODE'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_PRIORITY'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_MIN'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_MAX'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_CRIT_MIN'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_CRIT_MAX'] . '</td>
					<td class = "input_nums">' .$mass1['COMP_TOXIC'] . '</td>
					<td class = "input_text"><form method="post" action=""><input type="submit" name="comp_del_'.$mass1['ID'].'" value="Удалить"></form></td>
					<td class = "input_text">
					<a href="#" onclick="openbox('.$mass1['ID'].'); return false">Редактировать</a>
					</td></tr></table>';
							
							echo '<div class = "input_field_hide" id = "'.$mass1['ID'].'" style="display: none;"><table class = "input_field"><tr><form method="post" action="">
										<td>
										<input hidden name = "'.$mass1['ID'].'" value="'.$mass1['ID'].'"></input>
										</td>
										<td>
										<input type="text" class = "input_text" name="ecomp_name" value="' .$mass1['COMP_NAME'] . '" maxlength="20"></input>
										</td><td>
										<input type="text" class = "input_nums" name="ecomp_code" value="' .$mass1['COMP_CODE'] . '" maxlength="3"></input>
										</td><td>
										<input type="text" class = "input_nums" name="ecomp_prior" value="' .$mass1['COMP_PRIORITY'] . '" maxlength="10"></input>
										</td><td>
										<input type="text" class = "input_nums" name="ecomp_min" value="' .$mass1['COMP_MIN'] . '" maxlength="7"></input>
										</td><td>
										<input type="text" class = "input_nums" name="ecomp_max" value="' .$mass1['COMP_MAX'] . '" maxlength="7"></input>
										</td><td>
										<input type="text" class = "input_nums" name="ecomp_crit_min" value="' .$mass1['COMP_CRIT_MIN'] . '" maxlength="7"></input>
										</td><td>
										<input type="text" class = "input_nums" name="ecomp_crit_max" value="' .$mass1['COMP_CRIT_MAX'] . '" maxlength="7"></input>
										</td><td>';
										if ($mass1[COMP_TOXIC] == 'Да') {
										echo '<input type="checkbox" name = "ecomp_toxic" value="Да" checked/>';
										}
										else echo '<input type="checkbox" name = "ecomp_toxic" value="Да"/>';
										echo '</td><td>
										<input type="submit" name="comp_ed_'.$mass1['ID'].'" value="Изменить"></input>
										</td>
									  </form></tr>
									</table></div>';
									
									$edit_button = $_POST['comp_ed_'.$mass1['ID']];
									
								if (isset($edit_button)) {
									
									$ecomp_id = $mass1['ID'];
									$ecomp_name = htmlspecialchars($_POST['ecomp_name']);
									$ecomp_code = htmlspecialchars($_POST['ecomp_code']);
									$ecomp_prior = str_replace(",",".",htmlspecialchars($_POST['ecomp_prior']));
									$ecomp_min = str_replace(",",".",htmlspecialchars($_POST['ecomp_min']));
									$ecomp_max = str_replace(",",".",htmlspecialchars($_POST['ecomp_max']));
									$ecomp_crit_min = str_replace(",",".",htmlspecialchars($_POST['ecomp_crit_min']));
									$ecomp_crit_max = str_replace(",",".",htmlspecialchars($_POST['ecomp_crit_max']));
									$ecomp_toxic = htmlspecialchars($_POST['ecomp_toxic']);
									
									if ($ecomp_toxic != 'Да') {
										$ecomp_toxic = 'Нет';
									}
										
									mysql_query("UPDATE COMPONENTS SET COMP_NAME = '$ecomp_name', COMP_CODE = '$ecomp_code', COMP_PRIORITY = '$ecomp_prior', COMP_MIN = '$ecomp_min', COMP_MAX = '$ecomp_max', COMP_CRIT_MIN = '$ecomp_crit_min', COMP_CRIT_MAX = '$ecomp_crit_max', COMP_TOXIC = '$ecomp_toxic' WHERE ID = '$ecomp_id'");									
									header("Location: /admin.php?page=comp_editor");

								}
								
								if (isset($_POST['comp_del_'.$mass1['ID']])) {
							mysql_query("DELETE FROM COMPONENTS WHERE ID = ".$mass1['ID']);
							mysql_query("DELETE FROM `ANALYZES_DATA` WHERE `COMPONENT_ID`= ".$mass1['ID']);
							header("Location: /admin.php?page=comp_editor");
						}
						
				echo '</br>';
				}
				

				echo '</div><div class = "main_links">
			<a href = "/admin.php">Назад</a></br>
			</div>';
				
	break;
	
	//Редактор формул
	
case 'form_editor':
	
	echo '<div class = "main_links">
			<a href = "/admin.php">Назад</a></br>
			</div>';
	
	$comp_query_list = 'SELECT ID, COMP_CODE FROM COMPONENTS ORDER BY COMP_NAME';
	
	echo '
		<div class = "input_field"><p>Добавить формулу</p></div>
			<div class = "input_field">
				<table border="0">
				  <tr>
					<td><p>Ключ №1</p></td>
					<td><p>Ключ №2</p></td>
					<td><p>Приоритет</p></td>
					<td><p>Минимум</p></td>
					<td><p>Максимум</p></td>
					<td><p>Крит. минимум</p></td>
					<td><p>Крит. максимум</p></td>
					<td><p>Действие</p></td>
				  </tr>
				  <tr>
					<td><form method="post" action=""><select name="form_code_1"><option disabled>Код №1: </option>';
						$comp_entry_list = mysql_query($comp_query_list);
						while ($comp_list_1 = mysql_fetch_array($comp_entry_list)) {
							echo '<option value = "'.$comp_list_1[ID].'">'.$comp_list_1[COMP_CODE].'</option>';
						}
					echo '</select>
					</td><td><select name="form_code_2"><option disabled>Код №2: </option>';
					
						$comp_entry_list = mysql_query($comp_query_list);
						while ($comp_list_2 = mysql_fetch_array($comp_entry_list)) {
							echo '<option value = "'.$comp_list_2[ID].'">'.$comp_list_2[COMP_CODE].'</option>';
						}
					echo '</select></td><td>
					<input type="text" class = "input_nums" name="form_prior" value="" maxlength="10"></input>
					</td><td>
					<input type="text" class = "input_nums" name="form_min" value="" maxlength="7"></input>
					</td><td>
					<input type="text" class = "input_nums" name="form_max" value="" maxlength="7"></input>
					</td><td>
					<input type="text" class = "input_nums" name="form_crit_min" value="" maxlength="7"></input>
					</td><td>
					<input type="text" class = "input_nums" name="form_crit_max" value="" maxlength="7"></input>
					</td><td>
					<input type="submit" name="form_add" value="Добавить"></input>
					</td>
					</form>
				  </tr>
				</table>
			</div>';
			
	
		$form_add = $_POST['form_add'];
	
		if (isset($form_add)) {
	
			if ((empty($_POST['form_min'])) && (empty($_POST['form_max']))) {
				echo '<p>Заполните все поля!</p></br>';
			}
			else {
			
			$form_code_1 = htmlspecialchars($_POST['form_code_1']);
			$form_code_2 = htmlspecialchars($_POST['form_code_2']);
			$form_prior = str_replace(",",".",htmlspecialchars($_POST['form_prior']));
			$form_min = str_replace(",",".",htmlspecialchars($_POST['form_min']));
			$form_max = str_replace(",",".",htmlspecialchars($_POST['form_max']));
			$form_crit_min = str_replace(",",".",htmlspecialchars($_POST['form_crit_min']));
			$form_crit_max = str_replace(",",".",htmlspecialchars($_POST['form_crit_max']));
			
			$check_form = mysql_query("SELECT `FORM_CODE_1`,`FORM_CODE_2` FROM `FORMULS` WHERE `FORM_CODE_1` = '$form_code_1' AND `FORM_CODE_2` = '$form_code_2'");
			$result = mysql_fetch_array($check_form);
				
				if (!empty($result)) {
					echo '<p>Формула уже существует!</p></br>';
				}
				else {
					
				mysql_query("
				
				INSERT INTO `FORMULS` (`ID` ,`FORM_CODE_1` ,`FORM_CODE_2` ,`FORM_PRIORITY` ,`FORM_MIN` ,`FORM_MAX` ,`FORM_CRIT_MIN` ,`FORM_CRIT_MAX`)
				VALUES ('', '$form_code_1', '$form_code_2', '$form_prior', '$form_min', '$form_max', '$form_crit_min', '$form_crit_max');
				
				");
				
				header("Location: /admin.php?page=form_editor");
				
				}
				
				
			}

		}
		
			echo "<script type='text/javascript'>
		function openbox(id){
		display = document.getElementById(id).style.display;
			if(display=='none'){
				document.getElementById(id).style.display='block';
				}
				else {
					document.getElementById(id).style.display='none';
				}
		}
		</script>";
		
			echo '<div class = "input_field">';
		
			$form_query = 'SELECT * FROM FORMULS ORDER BY FORM_PRIORITY, FORM_CODE_1 ASC';
			$form_entry = mysql_query($form_query);
				while ($mass2 = mysql_fetch_array($form_entry)) {
					
					$id_code = $mass2['FORM_CODE_1'];
					$id_code2 = $mass2['FORM_CODE_2'];
					
					$id_query = "SELECT COMP_CODE FROM COMPONENTS WHERE ID = '$id_code'";
					$id_entry = mysql_query($id_query);
					while ($mass3 = mysql_fetch_array($id_entry)) {
						
						$id_query2 = "SELECT COMP_CODE FROM COMPONENTS WHERE ID = '$id_code2'";
						$id_entry2 = mysql_query($id_query2);
						while ($mass4 = mysql_fetch_array($id_entry2)) {
						
						echo '<table  class = "result_table"><tr>
						<td class = "input_text">' .$mass3['COMP_CODE'] . '</td>
						<td class = "input_nums">' .$mass4['COMP_CODE'] . '</td>
						<td class = "input_nums">' .$mass2['FORM_PRIORITY'] . '</td>
						<td class = "input_nums">' .$mass2['FORM_MIN'] . '</td>
						<td class = "input_nums">' .$mass2['FORM_MAX'] . '</td>
						<td class = "input_nums">' .$mass2['FORM_CRIT_MIN'] . '</td>
						<td class = "input_nums">' .$mass2['FORM_CRIT_MAX'] . '</td>
						<td class = "input_text"><form method="post" action=""><input type="submit" name="form_del_'.$mass2['ID'].'" value="Удалить"></form></td>
						<td class = "input_text">
						<a href="#" onclick="openbox('.$mass2['ID'].'); return false">Редактировать</a>
						</td>
						</tr></table></br>';
							
							echo '<div id = "'.$mass2['ID'].'" style="display: none;">
							
							<table class = "input_field"><tr>
							<form method="post" action="">
							<td><select name="eform_code_1"><option disabled>Код №1: </option>';
							$comp_entry_list = mysql_query($comp_query_list);
							while ($comp_list_1 = mysql_fetch_array($comp_entry_list)) {
								if ($mass3['COMP_CODE'] == $comp_list_1[COMP_CODE]) {
									echo '<option value = "'.$comp_list_1[ID].'" selected>'.$comp_list_1[COMP_CODE].'</option>';
								}
								echo '<option value = "'.$comp_list_1[ID].'">'.$comp_list_1[COMP_CODE].'</option>';
							}
							echo '</select>
							</td><td><select name="eform_code_2"><option disabled>Код №2: </option>';
					
							$comp_entry_list = mysql_query($comp_query_list);
							while ($comp_list_2 = mysql_fetch_array($comp_entry_list)) {
								if ($mass4['COMP_CODE'] == $comp_list_2[COMP_CODE]) {
									echo '<option value = "'.$comp_list_2[ID].'" selected>'.$comp_list_2[COMP_CODE].'</option>';
								}
								echo '<option value = "'.$comp_list_2[ID].'">'.$comp_list_2[COMP_CODE].'</option>';
							}
							echo '</select></td><td>
							<input type="text" class = "input_nums" name="eform_prior" value="' .$mass2['FORM_PRIORITY'] . '" maxlength="10"></input>
							</td><td>
							<input type="text" class = "input_nums" name="eform_min" value="' .$mass2['FORM_MIN'] . '" maxlength="7"></input>
							</td><td>
							<input type="text" class = "input_nums" name="eform_max" value="' .$mass2['FORM_MAX'] . '" maxlength="7"></input>
							</td><td>
							<input type="text" class = "input_nums" name="eform_crit_min" value="' .$mass2['FORM_CRIT_MIN'] . '" maxlength="7"></input>
							</td><td>
							<input type="text" class = "input_nums" name="eform_crit_max" value="' .$mass2['FORM_CRIT_MAX'] . '" maxlength="7"></input>
							</td><td>
							<input type="submit" name="form_ed_'.$mass2['ID'].'" value="Изменить"></input>
							</td>
							</form></tr>
							</table></div>';
									
									$edit_button = $_POST['form_ed_'.$mass2['ID']];
									
								if (isset($edit_button)) {
									
									$eform_id = $mass2['ID'];
									$eform_code_1 = htmlspecialchars($_POST['eform_code_1']);
									$eform_code_2 = htmlspecialchars($_POST['eform_code_2']);
									$eform_prior = str_replace(",",".",htmlspecialchars($_POST['eform_prior']));
									$eform_min = str_replace(",",".",htmlspecialchars($_POST['eform_min']));
									$eform_max = str_replace(",",".",htmlspecialchars($_POST['eform_max']));
									$eform_crit_min = str_replace(",",".",htmlspecialchars($_POST['eform_crit_min']));
									$eform_crit_max = str_replace(",",".",htmlspecialchars($_POST['eform_crit_max']));
									
									mysql_query("UPDATE FORMULS SET FORM_CODE_1 = '$eform_code_1', FORM_CODE_2 = '$eform_code_2', FORM_PRIORITY = '$eform_prior',FORM_MIN = '$eform_min', FORM_MAX = '$eform_max', FORM_CRIT_MIN = '$eform_crit_min', FORM_CRIT_MAX = '$eform_crit_max' WHERE ID = '$eform_id'");
									
									header("Location: /admin.php?page=form_editor");
									
								}
							
								if (isset($_POST['form_del_'.$mass2['ID']])) {
									mysql_query("DELETE FROM FORMULS WHERE ID = ".$mass2['ID']);
									header("Location: /admin.php?page=form_editor");
								}
						}
					}
				}
	
			echo '</div><div class = "main_links">
			<a href = "/admin.php">Назад</a></br>
			</div>';
	
	break;

	//Добавление пользователя
	case 'user_add':
		
		echo '
			<div class = "input_field"><p>Добавить пользователя</p></div>
			<div class = "input_field">
				<form method="post" action="">
					<p>Имя пациента</p>
						<input type="text" name="user_name" value="" maxlength="20"></input>
					<p>Фамилия</p>
						<input type="text" name="user_subname" value="" maxlength="20"></input>
					<p>Отчество</p>
						<input type="text" name="user_otch" value="" maxlength="20"></input>
					<p>Телефон</p>
						<input type="text" name="user_login" value="" maxlength="11"></input>
					<p>Пароль</p>
						<input type="password" name="user_password" value="" maxlength="20"></input>
					</br></br>
						<input type="submit" name="add_user" value="Подтвердить"></input>
				</form>
			</div>';
			
		echo '
		<div class = "input_field">';
	
		$user_add = $_POST['add_user'];
	
		if (isset($user_add)) {
	
			if (empty($_POST['user_name'])) {
				echo '<p>Поле ИМЯ не заполненно.</p>';
			}
			if (empty($_POST['user_subname'])) {
				echo '<p>Поле ФАМИЛИЯ не заполненно.</p>';
			}
			if (empty($_POST['user_otch'])) {
				echo '<p>Поле ОТЧЕСТВО не заполненно.</p>';
			}
			if (empty($_POST['user_login'])) {
				echo '<p>Поле ТЕЛЕФОН не заполненно.</p>';
			}
			if (empty($_POST['user_password'])) {
				echo '<p>Поле ПАРОЛЬ не заполненно.</p>';
			}
			else {
		
			$user_name = htmlspecialchars($_POST['user_name']);
			$user_subname = htmlspecialchars($_POST['user_subname']);
			$user_otch = htmlspecialchars($_POST['user_otch']);
			$user_login = htmlspecialchars($_POST['user_login']);
			$user_password = htmlspecialchars($_POST['user_password']);
			$analyz_login = htmlspecialchars($_POST['user_login']);
			$reg_date = time("U");
			
			$check_user = mysql_query("SELECT `LOGIN`,`PASSWORD` FROM `USERS` WHERE `LOGIN` = '$user_login' AND `PASSWORD` = '$user_password'");
			$result = mysql_fetch_array($check_user);
				if (!empty($result)) {
					echo '<p>Данный пользователь уже существует!</p>';
				}
				else {
				
				mysql_query("INSERT INTO `USERS` (`ID`,`LOGIN`,`PASSWORD`,`NAME`,`SUBNAME`,`OTCH`,`DATE_REG`) VALUES ('','$user_login','$user_password','$user_name','$user_subname','$user_otch','$reg_date')");
				mysql_query("INSERT INTO `ANALYZES` (`ID`,`USER_ID`,`CREATION_TIME`,`CHANGE_TIME`) VALUES ('','$user_login','','')");
				
				echo '<p>Запись успешно добавлена!</p>';
			}
		}
		
	}
	
	echo '</div><div class = "main_links">
			<a href = "/admin.php">Назад</a></br>
			</div>
			';
		
	break;
	
	//Вывод списка пациентов + основная страница
	default:
		
		echo '<div class = "headers">Список пользователей</div><div class = "results">';
		
		$user_list = "SELECT ID, LOGIN, PASSWORD, NAME, SUBNAME, OTCH FROM `USERS` ORDER BY `ID`";
		$user_query = mysql_query($user_list);
		
		while ($mass = mysql_fetch_array($user_query)) {
		echo '<p>' . $mass['NAME'] . ' ' .$mass['SUBNAME'] . ' ' .$mass['OTCH'] . '</p>
		<p style = "font-size: 0.9em">Телефон: ' . $mass['LOGIN'] . '</br></p>
		<a href = "#">Редактировать информацию</a> | <a href = "#">Добавить анализ</a> | <a href = "#">Удалить</a>
		</br></br>';
		}
	
		echo '</div><div class = "main_links">
			<a href = "/admin.php">Назад</a></br>
			</div>
		';
		
}


?>