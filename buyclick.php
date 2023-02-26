<?

$page_title = "Nebolex - Купить в один клик";

include('./template/header.php');

$pagesel = trim($_GET['page']);

switch($pagesel) {
	
case 'confirm':

	echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg">
						Подтверждение
					</h1>
					<p class="registration_intro">
						Чтобы перейти к оформлению покупки, пройдите верификацию через СМС<br>
						На ваш номер был отправлен код для подтверждения заказа 
					</p>';
					if ($_SESSION['entry_tries'] > 0) {
						echo '<br><p class="registration_intro">Неверный код подтверждения. Введите новый код, присланный вам в СМС.</p>';
					}
					
					echo '<form class="order" method = "post">
						<input type="code" name="sms_code" class="order__input code_confirm" required id="code_confirm">';
					
					if ($_SESSION['entry_tries'] > 3) {
			
					echo '<p class="registration_intro"><img src="captcha.php" id="captcha" /><br/>
							<a href="#" onclick=';
					echo "document.getElementById('captcha').src='captcha.php?'+Math.random();document.getElementById('captcha-form').focus();";
					echo 'id="change-image">Обновить проверочный код</a><br/><br/>
						<input type="text" name="captcha" class="order__input code_confirm" id="captcha-form" autocomplete="off" /></p>';
					
					}
					
					echo '<button type="submit" name = "conf_order" class="order__btn button code_confirm_brn">
							Подтвердить
						</button>
					</form>

					
				</div>
			</div>
		</section>
		<section class="footer_reg-conf">
			<div class="container clearfix">
				<p class="footer_reg">
						© 2018 Nebolex <br>
						Если Вы хотите сообщить о побочном явлении или жалобе на качество <br>
						продукции,пожалуйста, направьте обращение по следующему адресу: test@test.com
				</p>
			</div>
		</section>
	</div>		
</body>';


	$conf_order = $_POST['conf_order'];
	$ord_login = $_SESSION['ord_login'];
	
	if (isset($conf_order)) {
			
		if (($_SESSION['entry_tries'] > 3) && empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
		}
		
		if ($_POST['sms_code'] != $_SESSION['code'])  {
				
			$_SESSION['entry_tries'] = $_SESSION['entry_tries'] + 1;
			$code = rand(1000,9999);
			$_SESSION['code'] = $code;
			
			include_once "smsc_api.php";
			list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$ord_login", "Code: $code", 1);
				
			header("Location: /buyclick.php?page=confirm");
			
		}
		else {
			
			$ord_name = $_COOKIE['ord_name'];
			$ord_subname = $_COOKIE['ord_subname'];
			$ord_otch = $_COOKIE['ord_otch'];
			$ord_mail = $_COOKIE['ord_mail'];
			$ord_city = $_COOKIE['ord_city'];
			$ord_street = $_COOKIE['ord_street'];
			$ord_house = $_COOKIE['ord_house'];
			$ord_housing = $_COOKIE['ord_housing'];
			$ord_apartment = $_COOKIE['ord_apartment'];
			$ord_entrance = $_COOKIE['ord_entrance'];
			$ord_flat = $_COOKIE['ord_flat'];
			$ord_date = $_SESSION['ord_date'];
			$user_ip = $_SERVER['REMOTE_ADDR'];
			
			mysql_query("INSERT INTO ONECLICK (`ID`,`LOGIN`,`NAME`,`SUBNAME`,`OTCH`,`EMAIL`,`DATE`,`IP`,`CITY`,`STREET`,`HOUSE`,`HOUSING`,`APARTMENT`,`ENTRANCE`,`FLAT`,`CODE`) VALUES ('','$ord_login','$ord_name','$ord_subname','$ord_otch','$ord_mail','$ord_date','$user_ip','$ord_city','$ord_street','$ord_house','$ord_housing','$ord_apartment','$ord_entrance','$ord_flat','$code')");
		
			mysql_query("INSERT INTO `ORDERS` (`ID`,`USER_ID`,`DATE`) VALUES ('','$ord_login','$ord_date');") or die (mysql_error());
			
			$id_query = mysql_query("SELECT ID FROM ORDERS WHERE DATE = '$ord_date'");
			while ($query_id = mysql_fetch_array($id_query)) {
				$ord_id = $query_id['ID'];
			}
			
			foreach ($productsInCart as $product) {

			$prod_id = $product['TOVAR_ID'];
			$prod_count = $product['COUNT(SHOPCART.TOVAR_ID)'];
			$part_cost = $product['PRICE'] * $product['COUNT(SHOPCART.TOVAR_ID)'];
			mysql_query("INSERT INTO `ORDERS_DATA` (`ID`,`ORDER_ID`,`TOVAR_ID`,`COUNT`,`COST`) VALUES ('','$ord_id','$prod_id','$prod_count','$part_cost');") or die (mysql_error());
			
			}
			$cart = $_COOKIE['cart'];
			mysql_query("DELETE * FROM SHOPCART WHERE HASH = '$cart'");
			unset($_SESSION['login']);
			unset($_SESSION['entry_tries']);
			unset($_SESSION['code']);
			unset($_SESSION['ord_date']);
			setcookie("cart", "", time());
		
			header("Location: /buyclick.php?page=success");
			
		}
	}

break;

case 'success':

	echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg-end">
						Спасибо за покупку!
					</h1>
					<div class="buttons__reg-end clearfix">
						<a href = "/reg.php"><button class="order__btn button btn_reg-end float-left">
							Зарегистрироваться
						</button></a>
						<a href = "/index.php"><button class="order__btn button btn_reg-end float-left">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;На главную&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</button></a>
					</div>
				</div>
			</div>
		</section>';

break;

case 'cart':
	
	$tovar_id = $_GET['id'];
	
	if ((isset($_SESSION['login'])) && (isset($_SESSION['key'])) ) {
	header("Location: /store.php?page=product&id=$tovar_id");
	}
	
	function DbQuery($query) {
		$result = mysql_query($query);
		$arr = array();
			while ($row = mysql_fetch_assoc($result)) {
			$arr[] = $row;
			}
		return $arr;
    }
	
    function DbSet($query) {
		mysql_query($query) or die("Нее, не прокатит: " . mysql_error());
    }
    
	function resetStatus($id) {
		header("Location: /buyclick.php?page=cart&id=$id");
    }

    function addToCart($hash, $id) {
		DbSet("INSERT INTO SHOPCART (ID, HASH, TOVAR_ID) VALUES ('','$hash','$id');");
    }

    function minusInnerCart($hash, $id) {
		$check_query = mysql_query("SELECT COUNT(ID) FROM SHOPCART WHERE TOVAR_ID = '$id'");
		$checkdb = mysql_fetch_row($check_query);
		$total = $checkdb[0];
		if ($total > 1) {
		DbSet("DELETE FROM SHOPCART WHERE HASH = '$hash' AND TOVAR_ID = '$id' LIMIT 1 ;");
		}
		else {
		header("Location: /buyclick.php?page=cart&id=$id");
		}
    }

    function deleteFromCart($hash, $id) {
		DbSet("DELETE FROM SHOPCART WHERE HASH = '$hash' AND TOVAR_ID = '$id';");
    }

	function controller($hash) {
		if (isset($_GET['add']) and (int) $_GET['add'] > 0) {
        addToCart($hash, (int) $_GET['add']);
        resetStatus((int) $_GET['add']);
		}
		if (isset($_GET['minus']) and (int) $_GET['minus'] > 0) {
        minusInnerCart($hash, (int) $_GET['minus']);
        resetStatus((int) $_GET['minus']);
		}
		if (isset($_GET['del']) and (int) $_GET['del'] > 0) {
        deleteFromCart($hash, (int) $_GET['del']);
        resetStatus((int) $_GET['del']);
		}
    }

    if (isset($_COOKIE['cart']) and ((string) $_COOKIE['cart'])) {
		$cart_hash = (string) $_COOKIE['cart'];
    }
	else {
		function generateSalt() {
					$salt = '';
					$saltLength = 4;
					for($i=0; $i<$saltLength; $i++) {
						$salt .= chr(mt_rand(33,126));
					}
					return $salt;
		}
		$key = generateSalt();
		$cart_hash = md5($key . microtime());
    }

	setcookie("cart", $cart_hash, time() + 86400);
	controller($cart_hash);
	
    $productsInCart = DbQuery("	
	SELECT TOVAR_ID, COUNT(SHOPCART.TOVAR_ID), NAME, PRICE, PHOTO1, DESCRIPTION FROM STORE
	JOIN SHOPCART ON STORE.ID = TOVAR_ID
	WHERE SHOPCART.HASH = '$cart_hash' AND TOVAR_ID = '$tovar_id'
    ");
	
	foreach ($productsInCart as $product) {
		
		$total_cost = $product['PRICE'] * $product['COUNT(SHOPCART.TOVAR_ID)'];
		$total_count = $product['COUNT(SHOPCART.TOVAR_ID)'];
	
	echo "<section id='buyoneclick'>
				<div class='buyoneclick'>
					<div class='container clearfix'>
						<h1 class='header__page'>
							Купить в 1 клик
						</h1>
						<div class='buyoneclick__product float-left clearfix'>
							<img src='img/product_img.png' alt='product' class='buyoneclick__product_img float-left'>
							<div class='buyoneclick__product_description float-left'>
								<h2 class='buyoneclick__product_name'>
									Nebolex Arthro Initial ''30
								</h2>
								<div class='buyoneclick__product_buttons float-left clearfix'>";
								
								echo '<a href="/buyclick.php?page=cart&minus='.$tovar_id.'"><button class="cart__button cart__less_product">
										-
									</button></a>
									<button class="cart__button cart__abount_prodcut">
										' . $total_count . '
									</button>
									<a href="/buyclick.php?page=cart&add='.$tovar_id.'"><button class="cart__button cart__add_product">
										+
									</button></a>';
								
								echo '</div><div class="buyoneclick__product_cost bebas float-left">
									' . $total_cost . ' Р.
								</div></div></div>';
							
	}
										
					echo '<div class="buyoneclick__buy float-right">
							<div class="form_reg">
								<form class="order" method = "post">
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Фамилия*
										</p>
										<input type="text" name="subname" class="order__input order__input_txt float-right" required id="second_name">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Имя*
										</p>
										<input type="text" name="name" class="order__input order__input_txt float-right" required id="name">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Отчество
										</p>
										<input type="text" name="otch" class="order__input order__input_txt float-right" required id="patronymic">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Телефон*
										</p>
										<input style = "width: 240px" type="tel" name="login" class="order__input order__input_phone phone_passwordReset float-right" required id="tel" placeholder="+7 (___) ___-__-__">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Email*
										</p>
										<input type="mail" id="buyoneclick_email" name="mail" class="order__input order__input_txt float-right" required id="mail">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Город*
										</p>
										<input type="text" name="city" class="order__input order__input_txt float-right" required id="city">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Улица*
										</p>
										<input type="text" name="street" class="order__input order__input_txt float-right" required id="street">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Дом
										</p>
										<input type="text" name="house" class="order__input order__input_txtmini order__input_house float-left" required id="house">
										<p class="float-left">
											Корпус
										</p>
										<input type="text" name="housing" class="float-right order__input order__input_txtmini float-left" required id="housing">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Квартира
										</p>
										<input type="text" name="apartment" class="order__input order__input_txtmini order__input_apartment float-left" required id="apartment">
										<p class="float-left">
											Подъезд
										</p>
										<input type="text" name="entrance" class="order__input order__input_txtmini float-right" required id="Entrance">
									</div>
									<div class="buyoneclick__order_group clearfix">
										<p class="float-left">
											Этаж
										</p>
										<input type="text" name="flat" class="order__input order__input_txtmini order__input_flat float-left" required id="flat">
									</div>
									
									<button type="submit" name = "click_order" class="order__btn button float-right">
										Оформить заказ
									</button>
								</form>
							</div>
						</div>	
					</div>
				</div>		
			</section>
			<footer>
				<div class="container">
						<p class="footer_buyoneclick">
								© 2018 Nebolex <br>
								Если Вы хотите сообщить о побочном явлении или жалобе на качество <br>
								продукции, пожалуйста, направьте обращение по следующему адресу: test@test.com
						</p>
					</div>
			</footer>
		</div>	
	</body>';

	if (isset($_POST['click_order'])) {
				
		$login_adp = htmlspecialchars($_POST['login']);
		$ord_login = str_replace(array('+', ' ', '(' , ')', '-'), '', $login_adp);
		$ord_name = htmlspecialchars($_POST['name']);
		$ord_subname = htmlspecialchars($_POST['subname']);
		$ord_otch = htmlspecialchars($_POST['otch']);
		$ord_mail = htmlspecialchars($_POST['mail']);
		$ord_city = htmlspecialchars($_POST['city']);
		$ord_street = htmlspecialchars($_POST['street']);
		$ord_house = htmlspecialchars($_POST['house']);
		$ord_housing = htmlspecialchars($_POST['housing']);
		$ord_apartment = htmlspecialchars($_POST['apartment']);
		$ord_entrance = htmlspecialchars($_POST['entrance']);
		$ord_flat = htmlspecialchars($_POST['flat']);
		$ord_date = time("U");
		$code = rand(1000,9999);
	
		setcookie("ord_name", "$ord_name", time() + 3600);
		setcookie("ord_subname", "$ord_subname", time() + 3600);
		setcookie("ord_otch", "$ord_otch", time() + 3600);
		setcookie("ord_mail", "$ord_mail", time() + 3600);
		setcookie("ord_city", "$ord_city", time() + 3600);
		setcookie("ord_street", "$ord_street", time() + 3600);
		setcookie("ord_house", "$ord_house", time() + 3600);
		setcookie("ord_housing", "$ord_housing", time() + 3600);
		setcookie("ord_apartment", "$ord_apartment", time() + 3600);
		setcookie("ord_entrance", "$ord_entrance", time() + 3600);
		setcookie("ord_flat", "$ord_flat", time() + 3600);
		$_SESSION['ord_login'] = $ord_login;
		$_SESSION['ord_date'] = $ord_date;
		$_SESSION['code'] = $code;
		
		include_once "smsc_api.php";
		list($sms_id, $sms_cnt, $cost, $balance) = send_sms("$ord_login", "Code: $code", 1);
		
		header("Location: /buyclick.php?page=confirm");
	}
		
}

?>