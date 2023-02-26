<?php

$page_title = "Nebolex - Главная";
$pagesel = trim($_GET['page']);
$is_index_page = true;

include('./template/header.php');

switch($pagesel) {
	

case 'admin':
	
	echo '<div class = "input_field">
		<form method="post" action="index.php?page=admin">
			<p>Логин администратора</p>
				<input type="text" name="adlogin" value="" maxlength="20"></input>
			<p>Пароль</p>
				<input type="password" name="adpassword" value="" maxlength="15"></input></br></br>
				<input type="submit" name = "adlogon" value="Подтвердить"></input>
		</form>
	</div>';
	
	echo '<div class = "input_field">';
	
		$log_ad = $_POST['adlogon'];
	
		if (isset($log_ad)) {
	
			if (empty($_POST['adlogin'])) {
				echo '<p>Поле логин не заполненно.</p>';
			}
			if (empty($_POST['adpassword'])) {
				echo '<p>Поле пароль не заполненно.</p>';
			}
			else {
		
			$adlogin = htmlspecialchars($_POST['adlogin']);
			$adpassword = htmlspecialchars($_POST['adpassword']);
			$query = mysql_query("SELECT `ADLOGIN`,`ADPASSWORD` FROM `ADMIN` WHERE `ADLOGIN` = '$adlogin' AND `ADPASSWORD` = '$adpassword'");
			$result = mysql_fetch_array($query);
				if (empty($result)) {
					echo '<p>Неверные Логин или Пароль</p>';
				}
				else {
				session_start();
				$_SESSION['adlogin']=$adlogin;
				header("Location: ./admin.php");
				}
			}
		}
		
	echo '</div><div class = "main_links"><a href = "/index.php">На главную</a></div>';
	
break;


//Авторизация
case 'user':
	
	if (empty($_SESSION['entry_tries'])) {
		$_SESSION['entry_tries'] = 0;
	}
	
	if ((!empty($_COOKIE['login'])) && (!empty($_COOKIE['key']))) {
		$cookie_login = $_COOKIE['login'];
		$cookie_key = $_COOKIE['key'];
		$login_query = mysql_query("SELECT LOGIN, SKEY FROM USERS WHERE LOGIN = '$cookie_login' AND SKEY = '$cookie_key'");
		while ($result = mysql_fetch_array($login_query)) {
			session_start();
			$_SESSION['login']=$_COOKIE['login'];
			$_SESSION['name']=$_COOKIE['name'];
			$_SESSION['key']=$_COOKIE['key'];
			header("Location: /user.php");
		}
	}
	
	else {
		
		echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg">
						Авторизация
					</h1>
					<ul class="reg_data">
						<li>
							Номер телефона
							<div class="icon_answer">
								
							</div>
						</li>

						<li>
							Пароль
						</li>
					</ul>
					<div class="form_reg">
						<form class="order" method="post" action="">
							<div class="order__group phone_order">
								<input type="tel" name="login" class="order__input order__input_phone password" required id="tel" placeholder="+7 (___) ___-__-__">
							</div>
							<div class="order__group pas_order">
								<input type="password" name="password" class="order__input order_password2 password" required id="password" placeholder="*******">
								<div class="showPassword showPassword_author"></div>
							</div>';
							
							if ($_SESSION['entry_tries'] >= 2) {
							
							echo '<img src="captcha.php" id="captcha" /><br/>
							<a href="#" onclick=';
							
							echo "document.getElementById('captcha').src='captcha.php?'+Math.random();document.getElementById('captcha-form').focus();";
							
							echo 'id="change-image">Обновить проверочный код</a><br/><br/>
							<div class="order__group">
								<input type="text" name="captcha"  class="order__input" id="captcha-form" autocomplete="off" />
							</div>';
							
							}
							
							echo '<button type="submit" name = "logon" id="submit" class="order__btn button">
								Вход
							</button>
							<a href="reg.php" class="button button__author_reg" >
								Регистрация	
							</a>
						</form>
						<a href = "/index.php?page=pass_restore" class="forget_password">Забыли пароль?</a>
					</div>
				</div>
			</div>
		</section>';
	
	$log_us = $_POST['logon'];
	
	if (isset($log_us)) {
		
		}
		if (($_SESSION['entry_tries'] >= 2) && empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
		}
		else {
			$login_adp = htmlspecialchars($_POST['login']);
			$login = str_replace(array('+', ' ', '(' , ')', '-'), '', $login_adp);
			$password = htmlspecialchars($_POST['password']);
			
			$query_user = mysql_query("SELECT `LOGIN`,`PASSWORD`,`SKEY`,`NAME`,`SUBNAME` FROM `USERS` WHERE `LOGIN` = '$login'");
			while ($result = mysql_fetch_array($query_user)) {
				
				$db_login = $result['LOGIN'];
				$db_key = $result['SKEY'];
				$db_password = $result['PASSWORD'];
				$form_password = md5($password.$db_key);
				
				if (($form_password == $db_password) && ($login == $db_login)) {
					$_SESSION['entry_tries'] = 0;
					unset($_SESSION['captcha']);
					unset($_SESSION['entry_tries']);
					session_start();
					$_SESSION['login']=$login;
					$_SESSION['key']=$db_key;
					$name_user = $result['NAME'] . ' ' . mb_substr($result['SUBNAME'], 0, 1, 'UTF-8') .'.';
					$_SESSION['name'] = $name_user;
					setcookie('login',$login, time() + 84600);
					setcookie('key', $db_key, time() + 84600);
					setcookie('name',$name_user, time() + 84600);
					header("Location: /user.php");
				}
				elseif (($form_password != $db_password) && ($login == $db_login)) {
					$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
				}
				else {
					$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
				}
			}
		}
	}
	
break;
	
	
//Восстановление пароля
case 'pass_restore':
		
		echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg">
						Восстановление пароля
					</h1>
					
					<div class="form_reset-pas">
						<form class="order" method="post" action="">
							<div class="reset-pas_intro">
								Введите номер телефона, указанный при регистрации,<br>на него будет выслан код для установки нового пароля.
							</div>
							<div class="order__group phone_order">
								<input type="tel" name="login" class="order__input order__input_phone phone_passwordReset" required id="tel" placeholder="+7 (___) ___-__-__">
							</div>
							<div class="icon_answer form_reset_ans"></div>
							<button type="submit" name = "validate" id="submit" class="order__btn button">
								Отправить
							</button>
						</form>
					</div>
				</div>
			</div>
		</section>';
	
	$log_us = $_POST['validate'];
	
	if (isset($log_us)) {
		
	$login_adp = htmlspecialchars($_POST['login']);
	
	$login = str_replace(array('+', ' ', '(' , ')', '-'), '', $login_adp);
	$reg_date = time("U");
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$fails = 0;
	$code = rand(1000,9999);
	
	setcookie("user_login", $login, time() + 3600);
	setcookie("reg_date", $reg_date, time() + 3600);
	
	mysql_query("INSERT INTO USERREGTEMP (`ID`, `LOGIN`, `PASSWORD`, `DATE_REG`, `IP`, `CODE`, `FAILS`) VALUES ('','$login','','$reg_date', '$user_ip', '$code', '$fails')");
	
	include_once "smsc_api.php";
	list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$login", "Code: $code", 1);
	
	header("Location: /index.php?page=pass_new");
	
	}
	
break;
	
	
//Генерация нового пароля
case 'pass_new':

	if (empty($_SESSION['entry_tries'])) {
		$_SESSION['entry_tries'] = 0;
	}

	$login = $_COOKIE["user_login"];
	$reg_date = $_COOKIE["reg_date"];
		
	$data_query = mysql_query("SELECT * FROM USERREGTEMP WHERE LOGIN = '$login' AND DATE_REG = '$reg_date'");
	while ($query_data = mysql_fetch_array($data_query)) {
		$code = $query_data['CODE'];
	}

	echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg heding_reset-pas">
						Новый пароль
					</h1>
					
					<div class="form_reset-pas">
						<form class="order" method="post" action="">
							<div class="reset-pas_intro">
								Введите код, который был отправлен,<br>
								на указанный вами номер телефона
							</div>
							<input type="text" name="sms_code" class="order__input reset_pas-code">
							<p>
								Новый пароль
							</p>
							<div class="order__group pas_order rpn">
								
								<input type="password" name="password" class="order__input order_password password" required id="password" placeholder="*******">
								<div class="showPassword shp-new"></div>
							</div>
							<p>
								Повторите ввод пароля
							</p>
							<div class="order__group pas_order">
								<input type="password" name="password_conf" class="order__input cor_password" required  id="confirm_password" name="confirm_password" placeholder="*******">
								<div class="showPassword2 shp-new"></div>
							</div>';
							
							if ($_SESSION['entry_tries'] >= 2) {
								
							echo '<img src="captcha.php" id="captcha" /><br/>
									<a href="#" onclick=';
							echo "document.getElementById('captcha').src='captcha.php?'+Math.random();document.getElementById('captcha-form').focus();";
							echo 'id="change-image">Обновить проверочный код</a><br/><br/>
							<div class="order__group">
								<input type="text" name="captcha"  class="order__input" id="captcha-form" autocomplete="off" />
							</div>';
							
							}
							
							echo '<div class="error"></div>
							<button type="submit" name = "confirm_pass" id="submit" class="order__btn button">
								Установить
							</button>
						</form>
					</div>
				</div>
			</div>
		</section>';
		
	$confirm = $_POST['confirm_pass'];
	
	if (isset($confirm)) {
			
		if (($_SESSION['entry_tries'] >= 2) && empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
		}
		
		if ($_POST['sms_code'] != $code)  {
				
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
			$fails = $fails + 1;
			$code = rand(1000,9999);
			mysql_query("UPDATE USERREGTEMP SET FAILS = '$fails', CODE = '$code' WHERE LOGIN = '$login' AND DATE_REG = '$reg_date'");
			
			include_once "smsc_api.php";
			list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$login", "Code: $code", 1);
				
			header("Location: /reg.php?page=pass_new");
		}
		
		elseif ($_POST['password'] != $_POST['password_conf']) {
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
			header("Location: /reg.php?page=pass_new");
		}
		
		else {
				
			$user_password = $_POST["password"];
				
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

			mysql_query("UPDATE USERS SET PASSWORD = '$comp_password', SKEY = '$key' WHERE LOGIN = '$login'");
			mysql_query("DELETE FROM `USERREGTEMP` WHERE `LOGIN` = '$login'");
					
			setcookie("user_login", "", time());
			setcookie("reg_date", "", time());
			$_SESSION['entry_tries'] = 0;
					
			header("Location: /index.php?page=user");
		}
	}

break;

//Выход из сессии	
case 'exit':
	
	if (!empty($_COOKIE['login'])) {
		session_start();
		session_destroy(); 
		setcookie('login', "", time());
		setcookie('name', "", time());
		setcookie('key', "", time());
		header("Location: /index.php");
	}
	
break;

//Главная страница	
default:
	
	echo '<section>
					<div class="container">
						<div class="first">
							<div class="product clearfix">
								<img src="img/arthro0.png" alt="product" class="product__img">
								<div class="product__discription">
									<a href="/store.php?page=product&id=7">
										<h1>
											Nebolex Arthro Initial "30/1
										</h1>
									</a>	
										
									<p class="product__intro">
										Это было подтверждено в результате нескольких <br>маркетинговых и клинических исследований.*
									</p>
									<div class = "buylinks">
										<a href="/store.php?page=product&id=7"><button class="button buy_button">Купить</button>
										</a>
										<a href="buyclick.php?page=cart&id=7"><button class="button buy_oneclick">Купить в один клик</button></a>
									</div>
									<div class = "buylinks1" style = "display: none;">
										<a href="#"><button class="button buy_oneclick">Скоро в продаже</button></a>
									</div>
								</div>
								<div class="product__group">
									<div class="line_one clearfix">
										<img src="img/product_example0.png" alt="prouct example" onclick="selectProduct(0)" class="product__example">
										<img src="img/product_example1.png" alt="prouct example" onclick="selectProduct(1)" class="product__example product_unselected">
									</div>
									<div class="line_one line_one_second clearfix">
										<img src="img/product_example2.png" alt="prouct example" onclick="selectProduct(2)" class="product__example product_unselected">
										<img src="img/product_example3.png" alt="prouct example" onclick="selectProduct(3)" class="product__example product_unselected">
									</div>
									<a href="questions.php"><button class="product__group_button">
										Подобрать <br> препарат
									</button></a>
								</div>
							</div>
						</div>
					</div>
				</section>';
}

include('./template/footer.php');

?>
