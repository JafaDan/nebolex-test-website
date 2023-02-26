<?

$page_title = "МедСайт - Подобрать препарат";

include('./template/header.php');

echo '	<section id="qustion">
			<div class="qustion">
				<div class="container clearfix">
					<h1 class="qustion__heading">
						Подобрать препарат
					</h1>
				</div>			
			</div>
			<div class="qustion__main">
				<div class="clearfix">
					<div class="size_answer">
						<p class="first_qustion">
							Ваш пол
						</p>
						<div class="fist_qustion_answer fist_qustion_m float-left">
							Мужской
						</div>
						<div class="float-left">
							<input type="checkbox" class="ios">
						</div>
						<div class="fist_qustion_answer float-left">
							Женский
						</div>
					</div>
				</div>
				<div class="clearfix">
					<div class="size_answer">
						<p class="first_qustion">
							Дата рождения
						</p>
						<input type="text" class="input_birthday">
					</div>	
				</div>
				<div class="clearfix">
					<div class="size_answer">
						<p class="first_qustion">
							Переносили ли Вы операцию <br> по имплантации эндропротеза?
						</p>
						<div class="fist_qustion_answer second_qustion_m float-left">
							Нет
						</div>
						<div class="float-left">
							<input type="checkbox" class="ios">
						</div>
						<div class="fist_qustion_answer float-left">
							Да
						</div>
					</div>	
				</div>
				<div class="clearfix">
					<div class="size_answer clearfix">
						<p class="first_qustion">
							Когда Вы планируете начать<br>первый трехмесячный курс лечения? 
						</p>
						<div class="season summer">
							<input type="radio" id="contactChoice1" name="contact" value="email" class="element_year" checked>
							<label class="img_season img_season_summer" for="contactChoice1">
								
							</label>
						</div>
						<div class="season spring">
							<input type="radio" id="contactChoice2" name="contact" value="email" class="element_year" checked>
							<label class="img_season img_season_spring" for="contactChoice2">
								
							</label>
						</div>
						<div class="season autumn">
							<input type="radio" id="contactChoice3" name="contact" value="email" class="element_year" checked>
							<label class="img_season img_season_autumn" for="contactChoice3">
								
							</label>
						</div>
						<div class="season winter">
							<input type="radio" id="contactChoice4" name="contact" value="email" class="element_year " checked>
							<label class="img_season img_season_winter" for="contactChoice4">
								
							</label>
						</div>
							
						
					</div>	
				</div>
				<div class="clearfix">
					<div class="size_answer">
						<p class="first_qustion last_qustion">
							Я знаю, что старт лечения препаратом Nebolex Arthro<br>
							нужно начать со стартового Arthro Initial?
						</p>
						<div class="fist_qustion_answer second_qustion_m float-left">
							Нет
						</div>
						<div class="float-left">
							<input type="checkbox" class="ios">
						</div>
						<div class="fist_qustion_answer float-left">
							Да
						</div>
					</div>	
				</div>
				<div class="clearfix">
					<div class="size_answer">
						<a href = "/store.php?page=product&id=7"><p class="first_qustion">
							</br></br>Узнать результат
						</p></a>
					</div>	
				</div>
			</div>


		</section>';

include('./template/footer.php');

?>