<?php
// Задаем исходную строку, паттерны и объявляем массивы
$str = 'Товарищи!< С другой стороны::< Равным:: Таким > образом> практика показывает, что <реализация <намеченных заданий::развития <<организационной::форм> деятельности::обучения кадров:: плановых заданий>>::постоянный рост активности> требует от нас анализа <новых предложений::<финансовых::административных> условий:: поставленных <задач::целей>>';
$pattern = '/[<][^<>]*[:{2}][^<>]*[>]/';
$pattern2 = '/[^:][:][^:]/';
$arr = array();
$fin = array();
// Выделение вхождений первого уровня
preg_match_all($pattern, $str, $arr, PREG_SET_ORDER);
// Проверка на ошибки в синтаксисе
if (substr_count($str,'<') == substr_count($str,'>')) { 
	if (preg_match($pattern2, $str) == 0) {
		// Передача в функцию исходных массива, строки и паттерна
		the_thing($arr,$str, $pattern);
		// Удаление повторяющихся строк
		$fin = array_unique($fin);
		foreach ($fin as $fin2) {
			// Вывод уникальных строк в БД или вывод ошибки
			@$db = new mysqli('localhost', 'root', '', 'tests');
			if(!mysqli_connect_errno()) {
				mysqli_set_charset($db, 'utf8');
				$q = "SELECT `string` FROM `tz` WHERE `string`='$fin2'";
				$sql = "INSERT INTO `tz` VALUES ('$fin2', null)";
				$res_add = @mysqli_query($db, $q) or die('Ошибка ' . mysqli_errno($db));
				if (mysqli_num_rows($res_add) === 0) {
					if (mysqli_query($db, $sql)) {
     				 echo "Новая строка в таблице! " . $fin2 . '<br>';
     				}
					 else {
      				echo "Error: " . $sql . "<br>" . mysqli_error($db);
					}
				}
			}
		}
	}
else echo 'Ошибка в синтаксисе строки';
}
else echo 'Ошибка в синтаксисе строки';

function the_thing($arr, $str, $pattern) {
	global $fin;
	// Выделение первого вхождения
	$ef = implode($arr[0]);
	// Разделение на подстроки(элементы массива вариантов)
	$pieces = explode("::", $ef);
	foreach ($pieces as $pi) {
		// Удаление ограничителей
		$str1 = str_replace($ef, trim($pi,'<>'), $str);
		// Объявление массива вхождений второго уровня
		$arr2 = array();
		preg_match_all($pattern, $str1, $arr2, PREG_SET_ORDER);
		// Рекурсивное ображение к функции, пока существуют вхождения
		if(!empty($arr2)){
			the_thing($arr2,$str1, $pattern);}
			// Вывод полностью раскрытых строк в финальный массив
		else { array_push($fin, $str1);}
	}

}
?>


