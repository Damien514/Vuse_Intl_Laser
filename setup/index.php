<?php

	if (isset($_COOKIE['pin'])) $pin=$_COOKIE['pin'];
	if (isset($_COOKIE['postal'])) $postal=$_COOKIE['postal'];


	$pin=trim(isset($_POST['pin']) ? $_POST['pin'] : (isset($_GET['pin']) ? $_GET['pin'] : $pin));
	$p=trim(isset($_POST['p']) ? $_POST['p'] : (isset($_GET['p']) ? $_GET['p'] : ''));
	$postal=trim(isset($_POST['postal']) ? $_POST['postal'] : (isset($_GET['postal']) ? $_GET['postal'] : $postal));
	$erreur='';
	$epod=trim(isset($_POST['epod']) ? $_POST['epod'] : (isset($_GET['epod']) ? $_GET['epod'] : ''));

	if ($pin && $postal && $epod) {
		include_once('../settings.inc.php');

		$postal = strtoupper(str_replace(' ', '', $postal));
		$pin=strtoupper($pin);

		$result = mysqli_query($link, "SELECT `id` FROM `place` WHERE `postalcode`='".addslashes($postal)."' AND `pincode`='".addslashes($pin)."' AND `active`='1'  LIMIT 1 ;");

		if (mysqli_num_rows($result)==0) $erreur="Postal code not listed or wrong pin code.";
		else {
			$row = mysqli_fetch_array($result);
			$p=$row['id'];
			setcookie("pin", $pin, time()+(3600*24*30));
			setcookie("postal", $postal, time()+(3600*24*30));
		}
	}

	if ($pin && $p>0 && $epod=='operator') header("Location: /_operator_/liste.php?token=".md5($p.$pin)."&place_id=$p&pin=$pin");
	else if ($pin && $p>0) {
		header("Location: /kiosk/_laser_interface_v1.php?token=".md5($p.$pin)."&place_id=$p&pin=$pin");
	}
	else {

		include '../template.header.inc.php';

		if ($pin) $pass='password';
		else $pass='text';
	?>

	<center>
		<form method="post" id='go'>
			<h3>VUSE<br>Laser station<br><?=$mode?></h3>

			Identify the place with the store postal code and your pin code.<br><br><br>
			<input type="text" name='postal' placeholder="Postal code" maxlength="7" value="<?=$postal?>"><br><br>
			<input type="<?=$pass?>" name='pin' placeholder="Pin code" value="<?=$pin?>"><br><br><br>
			<?php
					?>

					<select name="epod">
						<option value="epod2">Engraving - ePod 2</option>
						<option value="operator">Engraving - Operator interface</option>
					</select>

					<?php

			?>
			<br><br><br>
			<span class='bouton' onclick="$('#go').submit()">Submit</span>
		</form>
		<br><br>
		<?php

		if ($erreur) echo "<b style='color:red'>$erreur</b>";
		?>
	</center>

	<?php
		include '../template.footer.inc.php';
	}
