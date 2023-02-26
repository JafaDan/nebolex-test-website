<?php

session_start();
include('./system/core.php');
ob_start();

$user_name = $_SESSION['name'];

echo '
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>' .$page_title. '</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

	<link rel="stylesheet" href="css/passfield.min.css">
	<link rel="stylesheet" href="css/iosCheckbox.css">
	<link rel="stylesheet" href="css/strong-password.min.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/slick.css">
	';
	
	if (!isset($is_text_page)) {
		echo '<link rel="stylesheet" href="css/slick-theme.css">';
	}
	
	echo '<link rel="stylesheet" href="css/main.css">';
	
	if ($is_text_page == true) {
		echo '<link rel="stylesheet" href="css/slick-theme-txt.css">';
	}
	
	if (($pagesel == '') && ($is_index_page == true) && (!isset($_COOKIE['first_visit']))) {
	echo '<link rel="stylesheet" href="css/main1.css">';
	}
	
	echo '
	<link rel="stylesheet" href="css/media.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
	
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script src="js/jquery-3.3.1.min.js"></script>
	
	<script src="js/jquery.maskedinput.min.js"></script>
    <script src="js/jquery.pwdMeter.js"></script>
    <script src="js/strong-password.js"></script>
		
	<script src="js/wow.min.js"></script>
	<script src="js/iosCheckbox.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>

</head>

<body onload=';
echo "document.getElementById('captcha-form').focus()";
echo '>';


if (($pagesel == '') && ($is_index_page == true) && (!isset($_COOKIE['first_visit']))) {
echo '<div class="animation_page">
		<img src="img/percent-bg.jpg" alt="" class="animate-bg">
		<div class="container">
			<div class="art">';
			
			?>
			
			<script>
			
			$( function () {
			  var
				count = 10,
				block = $( '.cell' ),
				interval = setInterval( function () {
				  count++;
				  
				  block.text( count );
				  
				  if( count === 45 ) {
					clearInterval( interval );
				  }
				}, 100 );
			  
			  block.text( count );
			} );
			
				$( function () {
			  var
				count = 20,
				block = $( '.cell_1' ),
				interval = setInterval( function () {
				  count++;
				  
				  block.text( count );
				  
				  if( count === 65 ) {
					clearInterval( interval );
				  }
				}, 100 );
			  
			  block.text( count );
			} );
			
			</script>
			
			<?
			
				echo '<div class="art_out wow fadeOutUp clearfix" data-wow-duration="3s">
					<div class="procentArt bebas float-left">
							<div class = "cell" style = "display: inline-block"></div>%
					</div> 
					<div class="procentArt_intro float-left bebas">
						страдающих<br>
						артрозом
					</div>
				</div>				
			</div>
			<div class="art2 wow zoomIn" data-wow-duration="3s">
				<div class="art_out wow fadeOutUp clearfix" data-wow-duration="1.5s">
					<div class="procent2Art bebas float-left">
							<div class = "cell_1" style = "display: inline-block"></div>%
					</div>
					<div class="procent2Art_intro bebas float-left">
						Исходов с<br>
						эндропротезированием
					</div>
				</div>	
			</div>
			
			<div class="art3 wow zoomIn clearfix bebas" data-wow-duration="4s">
				<div class="art_out wow fadeOutUpBig clearfix" data-wow-duration="4s">
					 Но есть решение 
				</div>
			</div>
				<div class="logo_anim">
					<div class="logo_anim-box">
						<div class="wow rotateOutUpLeft delay_logo_anim_out" data-wow-duration="3s">
							<div class="wow rotateInDownLeft delay_logo_anim" data-wow-duration="3s">
							<img src="img/animation/l1.svg" alt="" class="logo_left">
							</div>
						</div>
						<div class="wow zoomOut delay_logo_anim_out" data-wow-duration="3s">
							<div class="wow zoomIn delay_logo_anim" data-wow-duration="3s">
							<img src="img/animation/l2.svg" alt="" class="logo_top">
							</div>
						</div>
						<div class="wow rotateOutUpRight delay_logo_anim_out" data-wow-duration="3s">
							<div class="wow rotateInDownRight delay_logo_anim" data-wow-duration="3s">
							<img src="img/animation/l3.svg" alt="" class="logo_right">
						</div>
						</div>
						<div class="product_anim wow fadeIn" data-wow-duration="3s">
							<img src="img/arthro.png" alt="" class="pruduct_anim_img">
						</div>
					</div>
				</div>	
			</div>			
	</div>';
}

if (($pagesel == "") && ($is_index_page == true)) {
	echo '<div id="fullpage"><div class="section">
			<div class="screen">';	
}
else echo '<div class="screen_reg">';

echo '
<header>
	<div class="container">
		<div class="head clearfix">
			<div class="float-left">
				<a href="/index.php">
					<img src="img/logo.png" alt="Nebolex" class="head__logo">
				</a>
			</div>';
			
	function DbQueryHeader($query) {
	$result = mysql_query($query);
	$arr = array();
		while ($row = mysql_fetch_assoc($result)) {
		$arr[] = $row;
		}
	return $arr;
	}
	
	$cart_hash = $_COOKIE['cart'];
	$productsInCart = DbQueryHeader("
	SELECT TOVAR_ID, COUNT(SHOPCART.TOVAR_ID), NAME, PRICE FROM STORE
	JOIN SHOPCART ON STORE.ID = TOVAR_ID
	WHERE SHOPCART.HASH = '$cart_hash'
	GROUP BY SHOPCART.TOVAR_ID
	ORDER BY COUNT(SHOPCART.TOVAR_ID) DESC
	");
	
	if (!empty($productsInCart) and (isset($cart_hash)) and ((isset($_SESSION['login']))) && ((isset($_SESSION['key']))) ) {
		
		echo '<div class="basket_img float-left">
			<a href="/store.php?page=shopcart">
				<div class="head__cart">
					
				</div>
			</a>
			<div class="basket_open">';
			
		foreach ($productsInCart as $product) {

		$total_cost = $product['PRICE'] * $product['COUNT(SHOPCART.TOVAR_ID)'];
		$total_count = $product['COUNT(SHOPCART.TOVAR_ID)'];
	
			echo '<div class="name_of_product float-left">
				' . $product['NAME'] . '
			</div>
			<div class="amount_of_product float-right">
				'.$total_count.' шт.
			</div>';

		}

		echo '<a href = "/store.php?page=shopcart"><button class="button_of_basket float-left">
					Перейти к оформлению
			</button></a>
			<div class="basket_cost bebas float-right">
				'.$total_cost.' Р
			</div></div></div>';
	}

	echo '<nav>
	<ul class="menu clearfix">
	<li>
		<a class="menu__underline" href="txt.php">О препаратах</a>
	</li>
	<li>
		<a class="menu__underline" href="/store.php?page=product&id=7">Ассортимент</a>
	</li>
	<li>
		<a class="menu__underline" href="#">Доставка и оплата</a>
	</li>

	';

if ((!(isset($_SESSION['login']))) && (!(isset($_SESSION['key']))) ) {

echo '<li class="menu__lc">
		<a href = "/index.php?page=user">Личный кабинет</a>
			<ul class="menu__drop">
				<li>
					<a href = "/index.php?page=user">
						Вход
					</a>
				</li>
				<li>
					<a href = "/reg.php">
						Регистрация
					</a>
				</li>
				<li class="reg_doc">
					<a href = "/index.php?page=admin">
						<b>Для врача</b> 
					</a>
				</li>
			</ul>
		</li></ul>
	</nav>
	</div></div></header>';

};

if ( (isset($_SESSION['login'])) && (isset($_SESSION['key'])) ) {

echo "<li class='menu__lc'>";
		if (empty($user_name)) {
			echo "<a href='user.php'>Личный кабинет</a>";
		}
		else  echo "<a href='user.php'>$user_name</a>";
			echo "<ul class='menu__drop'>
				<li>
					<a href = 'user.php'>
						Ваш кабинет
					</a>
				</li>
				<li>
					<a href = 'user.php?page=orders'>
						Заказы
					</a>
				</li>
				<li>
					<a href = 'index.php?page=exit'>
						Выйти
					</a>
				</li>
			</ul>
		</li></ul>
	</nav>
	</div></div></header>";

}

 ?>

