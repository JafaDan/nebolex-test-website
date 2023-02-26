<?php

$page_title = "Nebolex - Регистрация";

session_start();

include('./system/core.php');

ob_start();

$is_index_pages = 1;
$is_user_page = 0;
$is_admin_page = 0;
$is_test_page = 0;

include('./template/header.php');

$pagesel = trim($_GET['page']);

switch($pagesel) {
	
case 'redirect':
	
		echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg-end">
						Вы успешно <br>
						Зарегестрировались
					</h1>
					<div class="buttons__reg-end clearfix">
					<form method="post" action="">
						<button name = "lk" class="order__btn button btn_reg-end float-left">
							Личный кабинет
						</button>
						<button name = "chose" class="order__btn button btn_reg-end float-left">
							Выбрать препарат
						</button>
					</form>
					</div>
					
				</div>
			</div>
		</section>';
	
	$lk = $_POST['lk'];
	$chose = $_POST['chose'];
	
	if (isset($lk)) {
		header("Location: /index.php?page=user");
	}
	if (isset($chose)) {
		header("Location: /question.php");
	}
	
break;
	
case 'code_entry':

	if (empty($_SESSION['entry_tries'])) {
		$_SESSION['entry_tries'] = 0;
	}
		
	echo '<div class="screen_reg">';
	
	$login = $_COOKIE["user_login"];
	$reg_date = $_COOKIE["reg_date"];
		
	$data_query = mysql_query("SELECT * FROM USERREGTEMP WHERE LOGIN = '$login' AND DATE_REG = '$reg_date'");
	while ($query_data = mysql_fetch_array($data_query)) {
		$fails = $query_data['FAILS'];
		$code = $query_data['CODE'];
		$user_ip = $query_data['IP'];
	}
		
	$fails_left = 20 - $fails;
		
	if ($fails == 20)  {
		echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix"><p class="registration_intro">Вы превысили количество попыток ввода кода. Повторите регистрацию через час.</p></div>
			</div>
		</section>';
	}
	else {
		
	echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg">
						Регистрация
					</h1>
					<p class="registration_intro">
						Регистрация почти завершена <br>
						На ваш номер был отправлен код для подтверждения аккаунта 
					</p>';
					
					if ($fails > 0) echo '</br><p class="registration_intro">Неверный код подтверждения. Введите новый код, присланный вам в СМС.</p>';
					
					echo '<form method = "post" class="order" action="">
						<input type="code" name="sms_code" class="order__input code_confirm" required id="code_confirm">';
						
					if ($_SESSION['entry_tries'] >= 1) {
						
					echo '<img src="captcha.php" id="captcha" /><br/>
							<a href="#" onclick=';
					echo "document.getElementById('captcha').src='captcha.php?'+Math.random();document.getElementById('captcha-form').focus();";
					echo 'id="change-image">Обновить проверочный код</a><br/><br/>
					<div class="order__group">
						<input type="text" name="captcha"  class="order__input" id="captcha-form" autocomplete="off" />
					</div>';
					
					}
						
					echo '<button type="submit" name="confirm_user" class="order__btn button code_confirm_brn">
							Подтвердить
						</button>
					</form>
				</div>
			</div>
		</section>';
		
	}
		
	$conf_add = $_POST['confirm_user'];
	
	if (isset($conf_add)) {
			
		if (($_SESSION['entry_tries'] >= 1) && empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
		}
		
		if ($_POST['sms_code'] != $code)  {
				
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
			$fails = $fails + 1;
			$code = rand(1000,9999);
			mysql_query("UPDATE USERREGTEMP SET FAILS = '$fails', CODE = '$code' WHERE LOGIN = '$login' AND DATE_REG = '$reg_date'");
			
			include_once "smsc_api.php";
			list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$login", "Code: $code", 1);
				
			header("Location: /reg.php?page=code_entry");
			
		}
		
		else {
				
			$user_login = $_COOKIE["user_login"];
			$user_password = $_COOKIE["user_password"];
			$reg_date = $_COOKIE["reg_date"];
				
			$_SESSION['entry_tries'] = 0;
				
			function generateSalt() {
				$salt = '';
				$saltLength = 8;
				for($i=0; $i<$saltLength; $i++) {
					$salt .= chr(mt_rand(33,126));
				}
				return $salt;
			}
				
			$key = generateSalt();
			$comp_password = md5($user_password.$key);
			
			if ($_COOKIE['user_restore'] == 1) {
				mysql_query("UPDATE USERS SET PASSWORD = '$comp_password', SKEY = '$key' WHERE LOGIN = '$login'");
				mysql_query("DELETE FROM `USERREGTEMP` WHERE `LOGIN` = '$login'");
					
				setcookie("user_login", "", time());
				setcookie("user_password", "", time());
				setcookie("reg_date", "", time());
					
				header("Location: /reg.php?page=redirect");
			}
			
			if ($_COOKIE['user_restore'] == 2) {
				mysql_query("INSERT INTO `USERS` (`ID`, `LOGIN`, `PASSWORD`, `SKEY`, `NAME`, `SUBNAME`, `OTCH`, `DATE_REG`, `EMAIL`, `IP`) VALUES ('','$user_login','$comp_password', '$key', '','', '','$reg_date', '', '$user_ip')");
				mysql_query("DELETE FROM `USERREGTEMP` WHERE `LOGIN` = '$login'");
					
				setcookie("user_login", "", time());
				setcookie("user_password", "", time());
				setcookie("reg_date", "", time());
					
				header("Location: /reg.php?page=redirect");
			}
		}
	}
	
break;
	
default:

	$us_login = $_COOKIE["user_login"];
	$us_date = $_COOKIE["reg_date"];
	$user_ip = $_SERVER['REMOTE_ADDR'];
		
	if (($us_login != "") && ($us_date != "")) {
	$check_user = mysql_query("SELECT * FROM `USERREGTEMP` WHERE `LOGIN` = '$us_login' AND `DATE_REG` = '$us_date'");
		while ($result = mysql_fetch_array($check_user)) {
			$time_reg = $result['DATE_REG'];
			$user_fails = $result['FAILS'];
			$reg_ip = $result['IP'];
		}
	}
	
	$time_now = time("U");
	$time_dif = $time_now - $time_reg;
	
	if (($user_fails == 20) && ($time_dif < 1800) && ($reg_ip == $user_ip)) {
		echo '<div class = "input_field"><p>Вы превысили количество попыток регистрации! Повторите позже.</p></div>';
	}
	else {
	
	echo '
	
	<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg">
						Регистрация
					</h1>
					<ul class="reg_data">
						<li>
							Номер телефона
							<div class="icon_answer">
								
							</div>
						</li>

						<li>
							Придумайте пароль
						</li>
						<li>
							Подтвердите пароль
						</li>
					</ul>
					<div class="form_reg">
						<form class="order" method="post" action="">
							<div class="order__group phone_order">
								<input type="tel" name="user_login" class="order__input order__input_phone password" required id="tel" placeholder="+7 (___) ___-__-__">
							</div>
							<div class="order__group pas_order">
								<input type="password" name="user_password" class="order__input order_password password" required id="password" placeholder="*******">
								<span class="pwd2">Сложность <br> пароля</span>
								
								<span id="pwdMeter" class="pwd">Ни чего не введено</span>
								<div class="showPassword"></div>

							</div>

							<div class="order__group pas_order">
								<input type="password" name="user_password_conf" class="order__input cor_password" required  id="confirm_password" name="confirm_password" placeholder="*******">
								<div class="showPassword2"></div>
							</div>
							<div class="error"></div>
							<button type="submit" name = "add_user" id="submit" class="order__btn button">
								Отправить
							</button>
						</form>
					</div>
				</div>
			</div>
		</section>';
	}
	

	$user_add = $_POST['add_user'];
	
	if (isset($user_add)) {
	
		if ($_POST['user_password'] != $_POST['user_password_conf']) {
			echo 'Пароль подтверждения не совпадает! Введите пароль заново.</br>';
		}
		else {
			
			$login_adp = htmlspecialchars($_POST['user_login']);
			$user_login = str_replace(array('+', ' ', '(' , ')', '-'), '', $login_adp);
			$user_password = htmlspecialchars($_POST['user_password']);
			$reg_date = time("U");
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$fails = 0;
				
			$check_user = mysql_query("SELECT `LOGIN` FROM `USERS` WHERE `LOGIN` = '$user_login'");
			$result = mysql_fetch_array($check_user);
			
			if (!empty($result)) {
				
				setcookie("user_login", $user_login, time() + 3600);
				setcookie("user_password", $user_password, time() + 3600);
				setcookie("reg_date", $reg_date, time() + 3600);
				setcookie("user_restore", 1, time() + 3600);

				$code = rand(1000,9999);
				
				mysql_query("INSERT INTO USERREGTEMP (`ID`, `LOGIN`, `PASSWORD`, `DATE_REG`, `IP`, `CODE`, `FAILS`) VALUES ('','$user_login','$user_password','$reg_date', '$user_ip', '$code', '$fails')");
				
				include_once "smsc_api.php";
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$user_login", "Code: $code", 1);
				
				header("Location: /reg.php?page=code_entry");
			}
			else {
				
				setcookie("user_login", $user_login, time() + 3600);
				setcookie("user_password", $user_password, time() + 3600);
				setcookie("reg_date", $reg_date, time() + 3600);
				setcookie("user_restore", 2, time() + 3600);
				$code = rand(1000,9999);
				
				mysql_query("INSERT INTO USERREGTEMP (`ID`, `LOGIN`, `PASSWORD`, `DATE_REG`, `IP`, `CODE`, `FAILS`) VALUES ('','$user_login','$user_password','$reg_date', '$user_ip', '$code', '$fails')");
				
				include_once "smsc_api.php";
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$user_login", "Code: $code", 1);
				
				header("Location: /reg.php?page=code_entry");
					
			}
		}
	}
		
	echo '</section>';
}

include('./template/footer.php');

?>