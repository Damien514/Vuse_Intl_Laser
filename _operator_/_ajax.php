<?php

	if (!$_SERVER['HTTP_X_REQUESTED_WITH']) {
		echo "Error 500.";
		exit;
	}

	$action=trim(isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : ''));
	$updateid=trim(isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : ''));
	$thezone=trim(isset($_POST['zone']) ? $_POST['zone'] : (isset($_GET['zone']) ? $_GET['zone'] : '0'));
	$epodversion=trim(isset($_POST['epodversion']) ? $_POST['epodversion'] : (isset($_GET['epodversion']) ? $_GET['epodversion'] : '1'));

	include_once('../inc.mysql.php');

	if ($action=="new") {

		$orientation=trim(isset($_POST['orientation']) ? $_POST['orientation'] : (isset($_GET['orientation']) ? $_GET['orientation'] : 'F'));
		$face=trim(isset($_POST['face']) ? $_POST['face'] : (isset($_GET['face']) ? $_GET['face'] : ''));
		$contenu=trim(isset($_POST['contenu']) ? $_POST['contenu'] : (isset($_GET['contenu']) ? $_GET['contenu'] : ''));
		$miniicon=trim(isset($_POST['miniicon']) ? $_POST['miniicon'] : (isset($_GET['miniicon']) ? $_GET['miniicon'] : ''));
		$letexte=trim(isset($_POST['letexte']) ? $_POST['letexte'] : (isset($_GET['letexte']) ? $_GET['letexte'] : ''));
		$choix=trim(isset($_POST['choix']) ? $_POST['choix'] : (isset($_GET['choix']) ? $_GET['choix'] : ''));
		$nom=trim(isset($_POST['nom']) ? $_POST['nom'] : (isset($_GET['nom']) ? $_GET['nom'] : ''));
		$epod=trim(isset($_POST['epod']) ? $_POST['epod'] : (isset($_GET['epod']) ? $_GET['epod'] : ''));
		$positiontexte=trim(isset($_POST['positiontexte']) ? $_POST['positiontexte'] : (isset($_GET['positiontexte']) ? $_GET['positiontexte'] : ''));

		$result = mysqli_query($link, "INSERT INTO `liste` (`epod`, `epodversion`, `choix`, `texte`, `contenu`, `position`, `positiontexte`, `orientation`, `nom`, `zone`) VALUES ('".addslashes($epod)."', '".addslashes($epodversion)."', '".addslashes($choix)."', '".addslashes($letexte)."', '".addslashes($contenu)."', '".addslashes($face)."','".addslashes($positiontexte)."', '".addslashes($orientation)."', '".addslashes($nom)."', '".addslashes($thezone)."');");

		echo mysqli_insert_id($link);
		exit;


	} else if ($action=="liste") {

		if ($updateid>0) {
			$result = mysqli_query($link, "UPDATE `liste` SET `fait`='1' WHERE `fait`='0' AND `id`='".addslashes($updateid)."' LIMIT 1 ;");
		}

		$result = mysqli_query($link, "SELECT * FROM `liste` WHERE `fait`='0' AND `zone`='".addslashes($thezone)."' ORDER BY `quand` ASC LIMIT 10 ;");

		$nb=mysqli_num_rows($result);
		if ($nb>=1) {
			while($row = mysqli_fetch_array($result)) {
				extract($row);
				if ($epodversion=='1' && $epod=='rosegold') $epod='gold';

				if ($epodversion=='3') $epodversion='2+';

				?>1;;oOo;;
				<div class="liste">
					ORDER <?=$id?> for <b style='text-transform:uppercase'><?=$nom?></b> - <?=$quand?> <?=$choix?><br><br>
				<?php if ($choix=="texte") {
					$choix="CUSTOM TEXT";
					if ($orientation=="H") { $orientation="Horizontal"; $angle=0; } else { $orientation="Vertical"; $angle=90; }
					if ($position=="B") { $positiont="Back"; } else { $positiont="Front"; }

					if ($positiontexte=='l') { $positiontxt='Left'; }
					else if ($positiontexte=='r') { $positiontxt='Right'; }
					else { $positiontxt='Center'; }
				?>
					<div class='containerepod <?=$epod?>'><a href="./downloadasset.php?id=<?=$id?>"><img src='generate.text.php?font=<?=$contenu?>&angle=<?=$angle?>&positiontexte=<?=$positiontexte?>&texte=<?=urlencode($texte)?>&orientation=<?=urlencode($orientation)?>&couleur=<?=urlencode($epod)?>'></a></div>
					<div class='legende'>
						<label>Type</label>Text<br>
						<label>Device</label><?=$epod?> <?=$epodversion?><br>
						<label>TEXT</label><?=$texte?><br>
						<label>FONTS</label><?=$contenu?><br>
						<label>SIDE</label><?=$positiont?><br>
						<label>POSITION</label><?=$positiontxt?><br>
						<label>ORIENTATION</label><?=$orientation?>
					</div>
				<?php } else if ($choix=="sneakpeek") { ?>

						<div class='containerepod <?=$epod?>'><a href="./downloadasset.php?id=<?=$id?>"><img src='../assets/icons/<?=$contenu?>'></a></div>

						<div class='legende'>
							<label>Type</label>Design / Mini-Icon<br>
						<label>ePOD</label><?=$epod?> <?=$epodversion?><br>
						<label>FILE</label><?=$contenu?><br>
					</div>
				<?php } else if ($choix=="custom") { ?>

						<div class='containerepod <?=$epod?>'><a href="./downloadasset.php?id=<?=$id?>"><img src='generate.design.php?liste=<?=urlencode($contenu)?>'></a></div>

						<div class='legende'>
						<label>Type</label>Custom Design<br>
						<label>ePOD</label><?=$epod?> <?=$epodversion?><br>
					</div>
				<?php } else if ($choix=="audio") { ?>

						<div class='containerepod <?=$epod?>'><a href="downloadasset.php?id=<?=$id?>"><img src='audio/generated/<?=$contenu?>-F.png'></a></div>

						<div class='legende'>
						<label>Type</label>Audio Signature<br>
						<label>ePOD</label><?=$epod?> <?=$epodversion?><br>
					</div>

				<?php } else if ($choix=="engraving20") {

				if ($position=="B") { $positiont="Back"; } else { $positiont="Front"; }
				?>

					<div class='containerepod <?=$epod?>'><a href="downloadasset.php?id=<?=$id?>"><img src='/2.0/upload/engraving20/<?=$contenu?>'></a></div>

					<div class='legende'>
					<label>Type</label>Engraving 2.0<br>
					<label>ePOD</label><?=$epod?> v<?=$epodversion?><br>
					<label>SIDE</label><?=$positiont?><br>
				</div>

			<?php } else if ($choix=="DesignLab") {

				if ($position=="B") { $positiont="Back"; } else { $positiont="Front"; }
				?>

					<div class='containerepod <?=$epod?>'><a href="downloadasset.php?id=<?=$id?>"><img src='/designlab/upload/generatedcontent/<?=$contenu?>'></a></div>

					<div class='legende'>
					<label>Type</label>DesignLab<br>
					<label>ePOD</label><?=$epod?> v<?=$epodversion?><br>
					<label>SIDE</label><?=$positiont?><br>
				</div>

			<?php } else if ($choix=="CuratedDesign") {

				if ($position=="B") { $positiont="Back"; } else { $positiont="Front"; }
				?>

					<div class='containerepod <?=$epod?>'><a href="downloadasset.php?id=<?=$id?>"><img src='<?=$contenu?>'></a></div>

					<div class='legende'>
					<label>Type</label>DesignLab<br>
					<label>ePOD</label><?=$epod?> v<?=$epodversion?><br>
					<label>SIDE</label><?=$positiont?><br>
				</div>

			<?php } ?>
					<br>
					<a class="bouton" href="./downloadasset.php?id=<?=$id?>">DOWNLOAD</a>
					<br>
					<span class="bouton" onclick="done(<?=$id?>,'<?=addslashes('Order for '.$nom)?>')">DONE / REMOVE</span>
				</div>


				<?php
				 if ($nb>1) {
					echo "<div class='archive'>";
					echo "<span class='titre'>Next ".($nb-1)." engravings</span><br><br>";
					while($row = mysqli_fetch_array($result)) {
						extract($row);
						?>
							<b>ORDER #<?=$id?></b> for <b style='text-transform:uppercase'><?=$nom?></b> on <?=date('F j \a\t Y g:i A', strtotime($quand))?><br>
						<?php if ($choix=="texte") {
							$choix="CUSTOM TEXT";
							if ($orientation=="H") { $orientation="Horizontal"; } else { $orientation="Vertical"; }
							if ($position=="B") { $position="back"; } else { $position="front"; }
						?>
							<i><?=$texte?></i> <?=$orientation?> on the <?=$position?> of a <?=$epod?> ePod <?=$epodversion?>.
						<?php } else if ($choix=="sneakpeek") { ?>
							Selected design on a <?=$epod?> ePod <?=$epodversion?>.
						<?php } else if ($choix=="custom") { ?>
							Custom design on a <?=$epod?> ePod <?=$epodversion?>.
						<?php } else if ($choix=="audio") { ?>
							Audio signature on a <?=$epod?> ePod <?=$epodversion?>.
						<?php } else if ($choix=="engraving20") {
							if ($position=="B") { $position="back"; } else { $position="front"; }
						?>
							Engraving 2.0 on the <b><?=$position?></b> a <b><?=$epod?></b> ePod <?=$epodversion?>.
						<?php } else if ($choix=="DesignLab") {
							if ($position=="B") { $position="back"; } else { $position="front"; }
						?>
							DesignLab on the <b><?=$position?></b> a <b><?=$epod?></b> ePod <?=$epodversion?>.
						<?php } else if ($choix=="CuratedDesign") {
							if ($position=="B") { $position="back"; } else { $position="front"; }
						?>
							CuratedDesign on the <b><?=$position?></b> a <b><?=$epod?></b> ePod <?=$epodversion?>.
						<?php } ?>
							<br><br>
						<?php
					}
					echo "</div><br><br><br><br>";
				}
			}
		} else {

			?>0;;oOo;;
			<div class="liste center">
				<b>NO JOB IN QUEUE.</b>
				<br><br><br>
				<span class="bouton" onclick="reload()">REFRESH</span>
			</div>
			<?php

		}

			$result = mysqli_query($link, "SELECT * FROM `liste` WHERE `fait`='1' AND `zone`='".addslashes($thezone)."' ORDER BY `quand` DESC LIMIT 10 ;");
			$nb=mysqli_num_rows($result);

			if ($nb>0) {

				echo "<div class='archive'>";
				echo "<span class='titre'>Last $nb engravings</span><br><br>";
				while($row = mysqli_fetch_array($result)) {
					extract($row);
					?>
						<b>ORDER #<?=$id?></b> for <b style='text-transform:uppercase'><?=$nom?></b> on <?=date('F j \a\t Y g:i A', strtotime($quand))?><br>
					<?php if ($choix=="texte") {
						$choix="CUSTOM TEXT";
						if ($orientation=="H") { $orientation="Horizontal"; } else { $orientation="Vertical"; }
						if ($position=="B") { $position="back"; } else { $position="front"; }
					?>
						<i><?=$texte?></i> <?=$orientation?> on the <?=$position?> of a <?=$epod?> ePod <?=$epodversion?>.
					<?php } else if ($choix=="sneakpeek") { ?>
						Selected design on a <?=$epod?> ePod <?=$epodversion?>.
					<?php } else if ($choix=="custom") { ?>
						Custom design on a <?=$epod?> ePod <?=$epodversion?>.
					<?php } else if ($choix=="audio") { ?>
						Audio signature on a <?=$epod?> ePod <?=$epodversion?>.
					<?php } else if ($choix=="engraving20") {
						if ($position=="B") { $position="back"; } else { $position="front"; }
					?>

						Engraving 2.0 on the <b><?=$position?></b> a <b><?=$epod?></b> ePod <?=$epodversion?>.
					<?php } ?>
						<br><br>
					<?php
				}
				echo "</div>";
			}
	}


