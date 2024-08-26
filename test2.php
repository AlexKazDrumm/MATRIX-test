<?php
// Запуск скрипта при условии отправки формы
if (isset($_POST['go'])) {
	// Присвоение строке значения из textarea
	$str = $_POST['str'];
	$pattern = '/[<][^<>]*[:{2}][^<>]*[>]/';
	$pattern2 = '/[^:][:][^:]/';
	$arr = array();
	$fin = array();
	preg_match_all($pattern, $str, $arr, PREG_SET_ORDER);
	if (substr_count($str,'<') == substr_count($str,'>')) { 
		if (preg_match($pattern2, $str) == 0) {
			the_thing($arr,$str, $pattern);

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
	else echo 'Ошибка в синтаксисе строки';
}
function the_thing($arr, $str, $pattern) {
	global $fin;
	$ef = implode($arr[0]);
	$pieces = explode("::", $ef);
	
	foreach ($pieces as $pi) {
		$str1 = str_replace($ef, trim($pi,'<>'), $str);
		$arr2 = array();
		preg_match_all($pattern, $str1, $arr2, PREG_SET_ORDER);
		
		if(!empty($arr2)){
			the_thing($arr2,$str1, $pattern);}

		else { array_push($fin, $str1);}
	}

}
?>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
	<div>
		Введите строку:<br>
		<textarea name="str" cols="60" rows="20"></textarea>
		<br>
		<input type="submit" name="go" value="Сгенерировать">
	</div>
</form>

