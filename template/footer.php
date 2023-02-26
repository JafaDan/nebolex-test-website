<?php

echo '<section class="footer">
					<div class="container clearfix">
						<p>
								© 2018 Nebolex <br>
								Если Вы хотите сообщить о побочном явлении или жалобе на качество <br>
								продукции, пожалуйста, направьте обращение по следующему адресу: test@test.com
						</p>
					</div>
				</section></div>
		</div>
	</div>	
</body>
</html>';

if (!isset($_COOKIE['first_visit'])) {
setcookie('first_visit','1', time() + 3600);
}



?>