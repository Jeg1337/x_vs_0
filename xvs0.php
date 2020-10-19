<?php

$height = 300;
$weight = 300;
$s = imagecreate($height, $weight);
$c1 = imagecolorallocate($s,255,255,255);
$c2 = imagecolorallocate($s,255,0,0);
$c3 = imagecolorallocate($s,0,0,0);

$DBHost = "localhost";
	$DBUser = "root";
	$DBPassword = "";
	$DBName = "x_vs_o";
	
	$Link = mysqli_connect($DBHost, $DBUser, $DBPassword);
	
	mysqli_select_db($Link, $DBName);
	
	$Query = '';

for ($i = 0; $i < 4; $i++) {
	imageline ($s, 0, ($height-100)/3 * $i, $weight-100, ($height-100)/3 * $i, $c2);
	imageline ($s, ($weight-100)/3 *$i, 0, ($weight-100)/3 *$i, $height-100, $c2);
}
echo "<form><br><input type = text name = 'value'><br>
		<input type = submit value = 'Отправить'></form>";

function Draw($s, $c2, $height, $weight) {
	for ($k = 1; $k < 10; $k++) {
		if (@$_SESSION['value' . $k] == 1) {
			imageline ($s, (($weight - 100) / 3) * (($k - 1) % 3), (($height - 100) / 3) * intdiv(($k - 1), 3), 
			(($weight - 100) / 3) * (($k - 1) % 3 + 1), (($height - 100) / 3) * (intdiv(($k - 1), 3) + 1), $c2);
			imageline ($s, (($weight - 100) / 3) * (($k - 1) % 3), (($height - 100) / 3) * (intdiv(($k - 1), 3) + 1), 
			(($weight - 100) / 3) * (($k - 1) % 3 + 1), (($height - 100) / 3) * intdiv(($k - 1), 3), $c2);
		} else if (@$_SESSION['value' . $k] == 2) {
			imageellipse($s, (($weight - 100) / 6) * ((($k - 1) % 3 + 1) + 3 - (3 - (($k - 1) % 3))), 
			(($weight - 100) / 6) * (1 + intdiv($k - 1, 3) * 2), ($weight - 100) / 3, ($weight - 100) / 3, $c2);
		}	
	}
}

function Check($s, $c2, $c3, $height, $weight, $i, $j, $k, $SESSION, $Link, $Query) {
	if (@$_SESSION['value' . $i] == @$_SESSION['value' . $j] and @$_SESSION['value' . $k] == @$_SESSION['value' . $j] 
	and (@$_SESSION['value' . $j] == 1 or @$_SESSION['value' . $j] == 2)) {
		imagesetthickness($s, 5);
		if (($i + $j + $k)  == 6) {
			imageline ($s, 0 , ($height - 100) / 6, $weight - 100, ($height - 100) / 6, $c3);
		}
		if ((($i + $j + $k)  == 15) and $i == 4) {
			imageline ($s, 0 , ($height - 100) / 2, $weight - 100, ($height - 100) / 2, $c3);
		}
		if (($i + $j + $k)  == 24) {
			imageline ($s, 0 , ($height - 100) / 6 * 5, $weight - 100, ($height - 100) / 6 * 5, $c3);
		}
		if (($i + $j + $k)  == 12) {
			imageline ($s, ($weight - 100) / 6 , 0, ($weight - 100) / 6, $height - 100, $c3);
		}
		if ((($i + $j + $k)  == 15) and $i == 2) {
			imageline ($s, ($weight - 100) / 2 , 0, ($weight - 100) / 2, $height - 100, $c3);
		}
		if (($i + $j + $k)  == 18) {
			imageline ($s, ($weight - 100) / 6 * 5 , 0, ($weight - 100) / 6 * 5, $height - 100, $c3);
		}
		if ((($i + $j + $k)  == 15) and $i == 1) {
			imageline ($s, 0 , 0, $weight - 100, $height - 100, $c3);
		}
		if ((($i + $j + $k)  == 15) and $i == 7) {
			imageline ($s, 0 , $height - 100, $weight - 100, 0, $c3);
		}
		if (@$_SESSION['value' . $i] == 1) {
			$_SESSION['x_value']++;
			echo '<h3>X WIN!!!</h3>';
			$Query = "insert into log (id, description, date)
			values(0, 'X WIN!!! result " . $_SESSION['x_value'] . ":" . $_SESSION['n_value'] . "', now())";
			mysqli_query($Link, $Query);
		} else {
			$_SESSION['n_value']++;
			echo '<h3>O WIN!!!</h3>';
			$Query = "insert into log (id, description, date)
			values(0, 'O WIN!!! result " . $_SESSION['x_value'] . ":" . $_SESSION['n_value'] . "', now())";
			mysqli_query($Link, $Query);
			}
		$_SESSION['win'] = true;
		$temp1 = $_SESSION['x_value'];
		$temp2 = $_SESSION['n_value'];
		$_SESSION = array();
		$_SESSION['krestic'] = 1;
		$_SESSION['x_value'] = $temp1;
		$_SESSION['n_value'] = $temp2;
		return $_SESSION;
	}
}

session_start();

if (@$_GET['go'] == 'Сброс') {
	$Query = "insert into log (id, description, date)
	values(0, 'reset, result 0:0', now())";
	mysqli_query($Link, $Query);
	$_SESSION = array();
}
if (!isset($_SESSION['x_value'])) {
	$_SESSION['x_value'] = 0;
}

if (!isset($_SESSION['n_value'])) {
	$_SESSION['n_value'] = 0;
}

echo "<form>
		<input type = submit name = 'go' value = 'Сброс'></form>";

@$_SESSION['value'] = $_GET['value'];
if (!isset($_SESSION['krestic'])) {
	$_SESSION['krestic'] = 1;
}

if (!isset($_SESSION['win'])) {
	$_SESSION['win'] = false;
}
if ($_SESSION['win'] == false) {
if (!isset($_SESSION['value'. $_SESSION['value']]) and $_SESSION['value'] > 0 and $_SESSION['value'] < 10) {
	if ($_SESSION['krestic'] == 1) {
		$_SESSION['value'. $_SESSION['value']] = 1;
		$Query = "insert into log (id, description, date)
		values(0, 'X on " . $_SESSION['value'] . "', now())";
		mysqli_query($Link, $Query);
		$_SESSION['krestic'] = 2;
	} else {
		$_SESSION['value'. $_SESSION['value']] = 2;
		$Query = "insert into log (id, description, date)
		values(0, 'O on " . $_SESSION['value'] . "', now())";
		mysqli_query($Link, $Query);
		$_SESSION['krestic'] = 1;
	}
}
}
Draw($s, $c2, $height, $weight);
Check($s, $c2, $c3, $height, $weight, 1, 2, 3, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 4, 5, 6, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 7, 8, 9, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 1, 4, 7, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 2, 5, 8, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 3, 6, 9, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 1, 5, 9, $_SESSION, $Link, $Query);
Check($s, $c2, $c3, $height, $weight, 7, 5, 3, $_SESSION, $Link, $Query);

if ((@$_SESSION['value1'] + @$_SESSION['value2'] + @$_SESSION['value3'] + @$_SESSION['value4'] + @$_SESSION['value5']
 + @$_SESSION['value6'] + @$_SESSION['value7'] + @$_SESSION['value8'] + @$_SESSION['value9']) == 13) {
	echo '<h3>DRAWN!</h3>';
	$Query = "insert into log (id, description, date)
			values(0, 'DRAWN!!! result " . $_SESSION['x_value'] . ":" . $_SESSION['n_value'] . "', now())";
	mysqli_query($Link, $Query);
	$_SESSION['win'] = true;
	$temp1 = $_SESSION['x_value'];
	$temp2 = $_SESSION['n_value'];
	$_SESSION = array();
	$_SESSION['krestic'] = 1;
	$_SESSION['x_value'] = $temp1;
	$_SESSION['n_value'] = $temp2;
}
if ($_SESSION['krestic'] == 1) {
	echo "Текущий игрок X";
} else if ($_SESSION['krestic'] == 2) {
	echo "Текущий игрок O";
}

echo "<form><h2>Счет<br>" . $_SESSION['x_value'] . ":" . $_SESSION['n_value'] . "</h2></form>";
imagepng($s, "2.png");
echo "<img src = '2.png'>";
?>