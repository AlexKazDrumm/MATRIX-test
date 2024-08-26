<?php
if (isset($_POST['go'])) {
	$str = $_POST['str'];
	$pattern = $_POST['pattern'];
	$arr = array();
	$fin = array();
	// Определение границ
	$borders = $pattern{2} . substr($pattern, -3, 1);
	preg_match_all($pattern, $str, $arr, PREG_SET_ORDER);
	if (substr_count($str,$pattern{2}) == substr_count($str,substr($pattern, -3, 1))) { 
			the_thing($arr,$str, $pattern, $borders);

			$fin = array_unique($fin);
			foreach ($fin as $fin2) {
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
function the_thing($arr, $str, $pattern, $borders) {
	global $fin;
	$ef = implode($arr[0]);
	$pieces = explode("::", $ef);
	
	foreach ($pieces as $pi) {
		$str1 = str_replace($ef, trim($pi, $borders), $str);
		$arr2 = array();
		preg_match_all($pattern, $str1, $arr2, PREG_SET_ORDER);
		
		if(!empty($arr2)){
			the_thing($arr2,$str1, $pattern, $borders);}

		else { array_push($fin, $str1);}
	}

}
?>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
	<div>
		Введите строку:<br>
		<textarea name="str" cols="60" rows="20"></textarea>
		<br><br>
		Введите паттерн:<br>
		<input type="text" name="pattern">
		<br><br>
		<input type="submit" name="go" value="Сгенерировать">
	</div>
</form>

