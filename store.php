<?php

$page_title = "Nebolex - магазин";
$pagesel = trim($_GET['page']);
$is_index_page = false;

include('./template/header.php');

$ses = $_SESSION['login'];

switch($pagesel) {


//Корзина
case 'shopcart':

if ((!(isset($_SESSION['login']))) && (!(isset($_SESSION['key']))) ) {
	header("Location: /index.php?page=user");
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
    
	function resetStatus() {
		header("Location: /store.php?page=shopcart");
    }

    function addToCart($hash, $id) {
		DbSet("INSERT INTO SHOPCART (ID, HASH, TOVAR_ID) VALUES ('','$hash','$id');");
    }

    function minusInnerCart($hash, $id) {
		DbSet("DELETE FROM SHOPCART WHERE HASH = '$hash' AND TOVAR_ID = '$id' LIMIT 1;");
    }

    function deleteFromCart($hash, $id) {
		DbSet("DELETE FROM SHOPCART WHERE HASH = '$hash' AND TOVAR_ID = '$id';");
    }

	function controller($hash) {
		if (isset($_GET['add']) and (int) $_GET['add'] > 0) {
        addToCart($hash, (int) $_GET['add']);
        resetStatus();
		}
		if (isset($_GET['minus']) and (int) $_GET['minus'] > 0) {
        minusInnerCart($hash, (int) $_GET['minus']);
        resetStatus();
		}
		if (isset($_GET['del']) and (int) $_GET['del'] > 0) {
        deleteFromCart($hash, (int) $_GET['del']);
        resetStatus();
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
	SELECT TOVAR_ID, COUNT(SHOPCART.TOVAR_ID), NAME, PRICE, PHOTO1, PHOTO2 FROM STORE
	JOIN SHOPCART ON STORE.ID = TOVAR_ID
	WHERE SHOPCART.HASH = '$cart_hash'
	GROUP BY SHOPCART.TOVAR_ID
	ORDER BY COUNT(SHOPCART.TOVAR_ID) DESC
    ");

	echo '<section id="cart">
			<div class="cart">
				<div class="container">
					<h1 class="header__page">
						Ваша корзина
					</h1>';
	
	$hash = $_COOKIE['cart'];
	$check = mysql_query("SELECT * FROM SHOPCART WHERE HASH = '$hash'");
	$result = mysql_fetch_array($check);
	
	if (empty($result)) {
		echo '<div class="cart__products float-left">
			<div class="cart__product clearfix">
				<img src="content/images/' . $product['PHOTO2'] . '" alt="" class="cart__product_img">
				<div class="cart__product_description clearfix">
					<h2 class="cart__product_name">
						Корзина пуста
					</h2>
				</div>
			</div></div></div>';
	}
	else {
	
	foreach ($productsInCart as $product) {
		
		$total_cost = $product['PRICE'] * $product['COUNT(SHOPCART.TOVAR_ID)'];
		$total_count = $product['COUNT(SHOPCART.TOVAR_ID)'];
		
		echo '<div class="cart__products float-left">
				<div class="cart__product clearfix">
					<img src="content/images/' . $product['PHOTO2'] . '" alt="" class="cart__product_img">
						<div class="cart__product_description clearfix">
								<h2 class="cart__product_name">
									' . $product['NAME'] . '
								</h2>
								
							<div class="cart__product__buttons clearfix">
								<a href="/store.php?page=shopcart&minus=' . $product['TOVAR_ID'] . '"><button class="cart__button cart__less_product">
									-
								</button></a>
								<button class="cart__button cart__abount_prodcut">
									' . $total_count . '
								</button>
								<a href="/store.php?page=shopcart&add=' . $product['TOVAR_ID'] . '"><button class="cart__button cart__add_product">
									+
								</button></a>
							</div>
							<div class="cart__product_cost bebas">
								' . $total_cost . ' P
							</div>
							<p>
								в наличии
							</p>
						</div>	
				</div>
			</div>';
							
			echo '<div class="cart__right float-left">
					<button class="button cart__promo">
						У вас есть промо-код?
					</button>
					<button class="button promo_accept">
						Применить
					</button>
					<div class="cart__right_sum clearfix">
						<p>Всего товаров на сумму: </p> <p>'. $total_cost .'</p> <p>Р</p>
					</div>
					<a href="/store.php?page=buy"><button class="button cart__button_buy">
						Оформить заказ
					</button></a>
				</div>';
	}
	
	}
	
	echo '</div></div></section>';
	
break;

//Оформление заказа
case 'buy':

if ((!(isset($_SESSION['login']))) && (!(isset($_SESSION['key']))) ) {
	header("Location: /index.php?page=user");
}

	function DbQuery($query) {
		$result = mysql_query($query);
		$arr = array();
			while ($row = mysql_fetch_assoc($result)) {
			$arr[] = $row;
			}
		return $arr;
    }
	
	if (isset($_COOKIE['cart']) and ((string) $_COOKIE['cart'])) {
		$cart_hash = (string) $_COOKIE['cart'];
    }
	else {
		header("Location: /store.php?page=shopcart");
    }
    
	$productsInCart = DbQuery("
	SELECT TOVAR_ID, COUNT(SHOPCART.TOVAR_ID), NAME, PRICE FROM STORE
	JOIN SHOPCART ON STORE.ID = TOVAR_ID
	WHERE SHOPCART.HASH = '$cart_hash'
	GROUP BY SHOPCART.TOVAR_ID
	ORDER BY COUNT(SHOPCART.TOVAR_ID) DESC
    ");
	
	$user_query = mysql_query("SELECT * FROM USERS WHERE LOGIN = '$ses'");
	while ($user_data = mysql_fetch_array($user_query)) {
	echo '<section id="buyoneclick">
			<div class="buyoneclick">
				<div class="container clearfix">
					<h1 class="header__page">
						Оформить заказ
					</h1>
					<div class="buyoneclick__buy float-right">
						<div class="form_reg">
							<form class="order" method = "post">
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Фамилия*
									</p>
									<input type="text" name="subname" value = "'.$user_data['SUBNAME'].'" class="order__input order__input_txt float-right" required id="second_name">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Имя*
									</p>
									<input type="text" name="name" value = "'.$user_data['NAME'].'" class="order__input order__input_txt float-right" required id="name">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Отчество
									</p>
									<input type="text" name="otch" value = "'.$user_data['OTCH'].'" class="order__input order__input_txt float-right" required id="patronymic">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Email*
									</p>
									<input type="mail" id="buyoneclick_email" name="mail" value = "'.$user_data['EMAIL'].'" class="order__input order__input_txt float-right" required id="mail">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Город*
									</p>
									<input type="text" name="city" value = "'.$user_data['CITY'].'" class="order__input order__input_txt float-right" required id="city">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Улица*
									</p>
									<input type="text" name="street" value = "'.$user_data['STREET'].'" class="order__input order__input_txt float-right" required id="street">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Дом
									</p>
									<input type="text" name="house" value = "'.$user_data['HOUSE'].'" class="order__input order__input_txtmini order__input_house float-left" required id="house">
									<p class="float-left">
										Корпус
									</p>
									<input type="text" name="housing" value = "'.$user_data['HOUSING'].'" class="float-right order__input order__input_txtmini float-left" id="housing">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Квартира
									</p>
									<input type="text" name="apartment" value = "'.$user_data['APARTMENT'].'" class="order__input order__input_txtmini order__input_apartment float-left" id="apartment">
									<p class="float-left">
										Подъезд
									</p>
									<input type="text" name="entrance" value = "'.$user_data['ENTRANCE'].'" class="order__input order__input_txtmini float-right" required id="Entrance">
								</div>
								<div class="buyoneclick__order_group clearfix">
									<p class="float-left">
										Этаж
									</p>
									<input type="text" name="flat" value = "'.$user_data['FLAT'].'" class="order__input order__input_txtmini order__input_flat float-left" required id="flat">
								</div>
								
								<button type="submit" name = "order" class="order__btn button float-right">
									Оформить заказ
								</button>
							</form>
						</div>
					</div>	
				</div>
			</div>		
		</section>';
		
	}
		
		if (isset($_POST['order'])) {
			
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
			
			mysql_query("UPDATE USERS SET `NAME` = '$ord_name', `SUBNAME` = '$ord_subname', `OTCH` = '$ord_otch', `EMAIL` = '$ord_mail', `CITY` = '$ord_city',`STREET` = '$ord_street',`HOUSE` = '$ord_house',`HOUSING` = '$ord_housing',`APARTMENT` = '$ord_apartment',`ENTRANCE` = '$ord_entrance',`FLAT` = '$ord_flat' WHERE LOGIN = '$ses'");
			
			mysql_query("INSERT INTO `ORDERS` (`ID`,`USER_ID`,`DATE`) VALUES ('','$ses','$ord_date');") or die (mysql_error());
			
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
			setcookie("cart", "", time());
			
			header("Location: /store.php?page=success");
			
		}
		
break;

case 'success':

	if ((!(isset($_SESSION['login']))) && (!(isset($_SESSION['key']))) ) {
	header("Location: /index.php?page=user");
	}

	echo '<section class="main__registration">
			<div class="screen__registration">	
				<div class="container clearfix">
					<h1 class="heading_reg-end">
						Спасибо за покупку!
					</h1>
					<div class="buttons__reg-end clearfix">
						<a href = "/user.php"><button class="order__btn button btn_reg-end float-left">
							Личный кабинет
						</button></a>
						<a href = "/index.php"><button class="order__btn button btn_reg-end float-left">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;На главную&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</button></a>
					</div>
				</div>
			</div>
		</section>';

break;

//Страница отдельного товара
case 'product':

	$tovar_id = $_GET['id'];
	
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
		header("Location: /store.php?page=product&id=$id");
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
		header("Location: /store.php?page=product&id=$id");
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
		
		echo '<section id="product-cart"><div class="product-cart"><div class="container clearfix">
		<div class="product-cart__ask float-left clearfix">
			<p>У вас есть  <br>эндропротез?</p>
			<div class="product-cart__answer float-left">Нет</div>
			<div class="product-cart__check float-left"><input type="checkbox" class="ios"></div>
			<div class="product-cart__answer float-left">Да</div>
		</div>';
		
	echo '<div class="product-cart__main float-left clearfix">
			<img src="content/images/' . $product['PHOTO1'] . '" alt="product" class="product-cart__img float-left">
		</div>
		<div class="product-cart__main_description clearfix float-left">
			<h2 class="product-cart__heading">' .$product['NAME'] . '</h2>
		<div class="product-cart__desk clearfix">
			<p class="float-left">' .$product['DESCRIPTION'] . '<a href="" class="product_cart-instruction">Инструкция</a></p>
		<div class="product-cart__cost bebas">' .$product['PRICE'] . ' P</div>
		</div>
		<div class="cart__product__buttons product-cart__button-abount clearfix">
							<a href="/store.php?page=product&minus='.$tovar_id.'"><button name = "plus_prod" class="cart__button cart__less_product">
								-
							</button></a>
							<button class="cart__button cart__abount_prodcut">
								' . $total_count . '
							</button>
							<a href="/store.php?page=product&add='.$tovar_id.'"><button name = "minus_prod" class="cart__button cart__add_product">
								+
							</button></a>
		</div>
		<div class="product-cart__button-buy">
		<form method = "post">
			<button name = "buy" value = "'. $tovar_id .'" class="button buy_button">
				Купить		
			</button>
		<a href = "/buyclick.php"><button class="button buy_oneclick">
				Купить в один клик	
		</button></a></form>
		</div></div></div></div></section>';
		
		if (isset($_POST['buy'])) {
			if ($total_count == 0) header("Location: /store.php?page=shopcart&add=$tovar_id");
			else header("Location: /store.php?page=shopcart");
		}
		
	}

break;


//Главна¤ страница магазина с перечнем товаров
default:

	$store_query = "SELECT * FROM STORE WHERE STATUS = '1'";
	$store_entry = mysql_query($store_query);
			
		while ($store_list = mysql_fetch_array($store_entry)) {
		
		echo '<section id="product-cart"><div class="product-cart"><div class="container clearfix"><div class="product-cart__main float-left clearfix">
			<img src="content/images/' . $store_list['PHOTO1'] . '" alt="product" class="product-cart__img float-left">
		</div>
		<div class="product-cart__main_description clearfix float-left">
			<h2 class="product-cart__heading">' .$store_list['NAME'] . '</h2>
		<div class="product-cart__desk clearfix">
			<p class="float-left">' .$store_list['DESCRIPTION'] . '<a href="" class="product_cart-instruction">Инструкция</a></p>
		<div class="product-cart__cost bebas">' .$store_list['PRICE'] . '</div>
		</div>
		<div class="product-cart__button-buy">
		<a class="button buy_oneclick" href="/store.php?page=product&id=' .$store_list['ID'] . '">
				Подробнее
		</a>
		</div></div></div></div></section>';

		}

}

include('./template/footer.php');

?>