<?php

include_once('imageSmoothArc.php');

function generateAudio($file,$m,$e) {

//	echo "File: $file et m=$m";

	if ($m=='3' || $m=='4') $width=1200;
	else if ($m=='0') $width=120;
	else if ($m=='1') $width=180;
	else if ($m=='5') $width=180;
	else if ($m=='99') $width=350;
	else if ($m=='100') $width=3000;
	else if ($m=='101') $width=3000;
	else if ($m=='102') $width=3000;
	else if ($m=='103') $width=3000;
	else if ($m=='104') $width=3000;
	else if ($m=='200') $width=230;
	else if ($m=='201') $width=441;
	else if ($m=='202') $width=210;
	else if ($m=='300') $width=41;
	else if ($m=='301') $width=117;
	else if ($m=='302') $width=56;
	else $width=360;

	$accuracysetting=100;

	$wavfilename="generated/$file.wav";
	$handle = fopen($wavfilename, 'rb');

	$heading[] = fread ($handle, 4);
	$heading[] = bin2hex(fread ($handle, 4));
	$heading[] = fread ($handle, 4);
	$heading[] = fread ($handle, 4);
	$heading[] = bin2hex(fread ($handle, 4));
	$heading[] = bin2hex(fread ($handle, 2));
	$heading[] = bin2hex(fread ($handle, 2));
	$heading[] = bin2hex(fread ($handle, 4));
	$heading[] = bin2hex(fread ($handle, 4));
	$heading[] = bin2hex(fread ($handle, 2));
	$heading[] = bin2hex(fread ($handle, 2));
	$heading[] = fread ($handle, 4);
	$heading[] = bin2hex(fread ($handle, 4));

    if ($heading[5] != '0100') {
			echo 'Wave file should be a PCM file : '.$heading[5];
			exit;
		}

    $peek = hexdec(substr($heading[10], 0, 2));
    $byte = $peek / 8;
    $channel = hexdec(substr($heading[6], 0, 2));

    // point = one data point (pixel), width total
    // block = one block, there are $accuracy blocks per point
    // chunk = one data point 8 or 16 bit, mono or stereo
    $filesize  = filesize($wavfilename);
    $chunksize = $byte * $channel;

    $file_chunks = ($filesize - 44) / $chunksize;
    if ($file_chunks < $width) {
			echo "Wave file has $file_chunks chunks, " . ($width) . ' required';
			exit;
		}

	if ($file_chunks < $width * $accuracysetting) $accuracy = 1;
	else $accuracy = $accuracysetting;

    $point_chunks = $file_chunks / ($width);
    $block_chunks = $file_chunks / ($width * $accuracy);

    $blocks = array();
    $liste = array();
    $points = 0;
    $current_file_position = 44.0; // float, because chunks/point and clunks/block are floats too.
    fseek($handle, 44);

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Read the data points and draw the image
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


     $ok=0;
     $c=0;

    while(!feof($handle)) {
        // The next file position is the float value rounded to the closest chunk
        // Read the next block, take the first value (of the first channel)
        $real_pos_diff = ($current_file_position - 44) % $chunksize;
        if ($real_pos_diff > ($chunksize / 2)) $real_pos_diff -= $chunksize;
        fseek($handle, $current_file_position - $real_pos_diff);

        $chunk = fread($handle, $chunksize);
        if (feof($handle) && !strlen($chunk)) break;

        $current_file_position += $block_chunks * $chunksize;

        if ($byte == 1)
            $blocks[] = ord($chunk[0]); // 8 bit
        else
            $blocks[] = ord($chunk[1]) ^ 128; // 16 bit


        // Do we have enough blocks for the current point?
        if (count($blocks) >= $accuracy) {
            // Calculate the mean and add the peak value to the array of blocks
            sort($blocks);
            $mean = (count($blocks) % 2) ? $blocks[(count($blocks) - 1) / 2]
                       : ($blocks[count($blocks) / 2] + $blocks[count($blocks) / 2 - 1]) / 2;
            if ($mean > 127) $point = array_pop($blocks); else $point = array_shift($blocks);

		  $point=abs($point-127);

		  // Si le premier pixel est trop haut, on va le corriger.
		  if ($ok==0 && $point>40) $point=0;


		  if ($point>2 && $ok==0) $ok=1;

		  //if ($ok==1) {
			  //if ($c++>2) $liste[]=$point;
	            //else $liste[]=0;
            //} else $liste[]=0;

            $point-=3;
            if ($point<0) $point=0;

		  $liste[]=$point;
		  if ($m=="debug") echo "<span alt='$m $c' style='height:".$point."px;width:1px;background:red;display:inline-block'></span>";

		  $blocks = array();
        }
    }

    // close wave file
    fclose ($handle);

    if ($m=='debug') exit;

	//header("Content-type: image/png");

	$im = imagecreatetruecolor(1000, 2160);

	//if ($m>98) $im = imagecreatetruecolor(1000, 2160);
	//else $im = imagecreatetruecolor(1000, 1000);

	imagesavealpha($im, true);
	imagealphablending($im, true);

	$blanc = imagecolorallocatealpha($im, 205, 205, 205, 127);
	$gris = imagecolorallocatealpha($im, 205, 205, 205, 0);
	imagefill($im, 0, 0, $blanc);


	$b   = imagecolorallocatealpha ($im, 0, 0, 0, 127);
	if ($e=='debug') $w = imagecolorallocate ($im, 200,200,200);
	else $w = imagecolorallocate ($im, 0,0,0);



	if ($m=='1') {

		$height=200;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 50;
		$new_max = 200;
		foreach ($liste as $i => $v) {
			$liste[$i] = 200 + ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}




		$angle=0;
		$q=0;

		$step=2;

		while($q<($width)) {
			$rand=$liste[$q];
			$q++;

			$startRadian = (0 - $angle) / 180 * M_PI;
			$stopRadian = (0 - $angle + 1) / 180 * M_PI;

			imageSmoothArc($im,200,1040,$rand,$rand,array(0,0,0,0),$startRadian,$stopRadian);
			$angle+=2;
			//if ($angle>360) $angle-=360;
		}

		imagealphablending($im, false);
		imagefilledellipse($im,200,1040,120,250,$b);
	} else if ($m=='0') {

		$height=500;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 5;
		$new_max = $height;
		$cc=0;
		foreach ($liste as $i => $v) {

			$cc++;

			if ($cc<20) {
				$x = 50*($cc/20);
			} else if ($cc>$width-20) {
				$t=$width-$cc;
				$x = 50*($t/20);
			} else {
				$x=50;
			}

			$liste[$i] = $x + 50+ ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}


		$angle=90+45+18;
		$q=0;

		$step=2;

		while($q<$width) {
			$rand=$liste[$q];
			$q++;

			$startRadian = (0 - $angle) / 180 * M_PI;
			$stopRadian = (0 - $angle + 1) / 180 * M_PI;

			imageSmoothArc($im,200,990,$rand,$rand,array(0,0,0,0),$startRadian,$stopRadian);
			$angle+=$step;
			if ($angle>360) $angle-=360;
		}

		imagealphablending($im, false);
		imagefilledellipse($im,200,990,95,95,$b);
	} else if ($m=='6') {

		$height=450;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 0;
		$new_max = $height;
		foreach ($liste as $i => $v) {
			$liste[$i] = 200+ ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}


		$angle=90+20;
		$q=0;

		$step=2;

		while($q<($width)) {
			$rand=$liste[$q];
			$q++;

			$startRadian = (0 - $angle) / 180 * M_PI;
			$stopRadian = (0 - $angle + 1) / 180 * M_PI;

			imageSmoothArc($im,500,500,$rand,$rand,array(0,0,0,0),$startRadian,$stopRadian);
			$angle+=$step;
			if ($angle>360) $angle-=360;
		}

		imagealphablending($im, false);
		imagefilledellipse($im,500,500,200,200,$b);
	} else if ($m=='2'){

		$height=150;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 0;
		$new_max = $height;
		foreach ($liste as $i => $v) {
			$liste[$i] = 150+((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}

		$angle=0;
		while($angle<360) {
			$angler= (0 - $angle + 1) / 180 * M_PI;

			$x1=$liste[$angle]/2 * cos($angler) + 500;
			$y1=$liste[$angle]/2 * sin($angler) + 500;

			//$x1=500;
			//$y1=500;

			$x=$liste[$angle] * cos($angler) + 500;
			$y=$liste[$angle] * sin($angler) + 500;

			imageline($im,$x1,$y1,$x,$y,$w);

			$angle++;
		}


		//imagealphablending($im, false);
		//imagefilledellipse($im,500,500,150,150,$b);


	} else if ($m=='3'){

		$height=200;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 0;
		$new_max = $height;
		foreach ($liste as $i => $v) {
			$liste[$i] = 100+((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}

		$angle=0;
		$a=0;
		while($angle<$width) {
			$angler= (0 - $angle + 1) / 180 * M_PI;

			$x1=(300) * cos($angler) + 500;
			$y1=(300) * sin($angler) + 500;

			$x=$liste[$a] * cos($angler) + 500;
			$y=$liste[$a] * sin($angler) + 500;

			imageline($im,$x1,$y1,$x,$y,$w);

			$a++;
			$angle+=.5;
		}


		//imagealphablending($im, false);
		//imagefilledellipse($im,500,500,150,150,$b);


	} else if ($m=='5'){

		$height=60;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 3;
		$new_max = 75;
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}

		$angle=0;
		$a=60;
		while($a<$width) {
			$angler= (0 - $angle + 1) / 180 * M_PI*6.5;

			$x=(($a*1)) * cos($angler) + 200;
			$y=(20+($a*1.8) )* sin($angler) + 1000;

			if ($liste[$a]<20) imagefilledellipse($im,$x,$y,$liste[$a],$liste[$a],$w);
			else {
				$rayon=0;
				if ($liste[$a]<70) $rmax=2;
				else $rmax=3;
				while ($rayon<$rmax) {
					imageellipse($im,$x,$y,$liste[$a]+$rayon,$liste[$a]+$rayon,$w);
					$rayon+=.2;
				}
				}

			$a++;
			$angle+=1;
		}


	} else if ($m=='4'){

		$height=350;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 60;
		$new_max = 140;
		foreach ($liste as $i => $v) {
			$liste[$i] = 50+((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
			//$new_min+=100/$width;
			//$new_max += 50/$width;
		}

		$angle=0;
		$x1=0;
		$y1=0;

		while($angle<$width) {
			$angler= (0 - $angle + 1) / 180 * M_PI*9;

			$x=$liste[$angle] * cos($angler) + 200;
			$y=$liste[$angle] * sin($angler) + 1040;

			//if ($x1) imageline($im,$x1,$y1,$x,$y,$w);

			if ($x1) thickline($im,$x1,$y1,$x,$y,$w,5);

			$x1=$x;
			$y1=$y;

			$angle++;
		}

	} else if ($m=='7'){

		$height=250;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 0;
		$new_max = 350;
		foreach ($liste as $i => $v) {
			$liste[$i] = 350+((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}

		$angle=0;
		while($angle<360) {
			$angler= (0 - $angle + 1) / 180 * M_PI;

			$x=$liste[$angle] * cos($angler) + 500;
			$y=$liste[$angle] * sin($angler) + 500;

			imageline($im,500,500,$x,$y,$w);

			$angle++;
		}


		imagealphablending($im, false);
		imagefilledellipse($im,500,500,100,100,$b);


	} else if ($m=='99'){

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_max = $width;

		// On égalise
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max) * ($v - $min)) / ($max - $min));
		}

		$angle=0;
		$y=1800;
		$rr=0;
		while($angle<$width) {



				$x=$liste[$angle]+25;
				if ($liste[$angle]>0) {
					$r=$liste[$angle]/$width*5+5;
					imageellipse($im,$x,$y,$r,$r,$w);

				} else {
					$r=1;
				}

				if ($rr==1) thickline($im,$x1,$y1,$x,$y,$w,1);
				$rr=1;
				$x1=$x;
				$y1=$y;
			$y-=5;


			$angle++;
		}


	} else if ($m=='100'){

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_max = 120;

		// On égalise
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max) * ($v - $min)) / ($max - $min));
		}

		$angle=0;
		$rr=0;
		$xx=0;
		$yy=0;
		$rr=80;

		$inc=.0199;

		$xx2=0;
		$yy2=0;

		while($angle<$width) {

			$x=200+(sin($xx)*$rr);
			$y=550+sin($yy)*($rr*3.2);

			$r=$liste[$angle]/2;
			$rr+=.011;




			$x1=$x;
			if ($x>201) $x2=$x+$r;
			//else if ($x<199) $x2=$x-$r;
			else $x2=$x;

			$y1=$y;
			$y2=$y;

			imageline($im,$x1,$y1,$x2,$y2,$w);

			if ($xx2 || $yy2) {
				imageline($im,$x,$y,$xx2,$yy2,$w);
				imageline($im,$xx1,$yy1,$x2,$y2,$w);
			}
			$xx2=$x;
			$yy2=$y;
			$xx1=$x2;
			$yy1=$y2;

			$xx+=$inc;
			$yy+=$inc/2;

			$angle++;
		}


	}  else if ($m=='101'){

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_max = 75;

		// On égalise
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max) * ($v - $min)) / ($max - $min));
		}

		$angle=0;
		$rr=0;
		$xx=0;
		$yy=0;
		$rr=60;

		$inc=.15/4;

		$xx2=0;
		$yy2=0;

		while($angle<$width) {

			$r=$liste[$angle];

			$a=200+(sin($xx)*$rr);
			$b=550+(sin($yy)*($rr*3.2));
			$x=200+(sin($xx)*($rr+$r));
			$y=550+(sin($yy)*((($rr)*3.2)+$r));

			//imageline($im,$a,$b,$x,$y,$w);

			if ($xx2 || $yy2) imageline($im,$xx2,$yy2,$x,$y,$w);
			$xx2=$x;
			$yy2=$y;

			$rr+=.0125;
			$xx+=$inc;
			$yy+=$inc/2;

			$angle++;
		}


	}else if ($m=='102'){

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_max = 75;

		// On égalise
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max) * ($v - $min)) / ($max - $min));
		}

		$angle=0;
		$rr=0;
		$xx=0;
		$yy=0;
		$rr=60;

		$inc=.15/4;

		$xx2=0;
		$yy2=0;

		while($angle<$width) {

			$r=$liste[$angle];

			$a=200+(sin($xx)*$rr);
			$b=550+(sin($yy)*($rr*3.2));
			$x=200+(sin($xx)*($rr+$r));
			$y=550+(sin($yy)*((($rr)*3.2)+$r));

			imageline($im,$a,$b,$x,$y,$w);

			//if ($xx2 || $yy2) imageline($im,$xx2,$yy2,$x,$y,$w);
			$xx2=$x;
			$yy2=$y;

			$rr+=.012;
			$xx+=$inc;
			$yy+=$inc/2;

			$angle++;
		}


	} else if ($m=='103'){

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_max = 75;

		// On égalise
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max) * ($v - $min)) / ($max - $min));
		}

		$angle=0;
		$rr=0;
		$xx=0;
		$yy=0;
		$rr=60;

		$inc=.12/2;

		$xx2=0;
		$yy2=0;

		while($angle<$width) {

			$r=$liste[$angle];

			$a=200+(sin($xx)*$rr);
			$b=550+(sin($yy)*($rr*3.2));
			$x=200+(sin($xx)*($rr+$r));
			$y=550+(sin($yy)*((($rr)*3.2)+$r));

			//imageline($im,$a,$b,$x,$y,$w);

			if ($xx2 || $yy2) imageline($im,$xx2,$yy2,$x,$y,$w);
			$xx2=$x;
			$yy2=$y;

			$rr+=.007;
			$xx+=$inc;
			$yy+=$inc/2;

			$angle++;
		}


	} else if ($m=='104'){

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_max = 120;

		// On égalise
		foreach ($liste as $i => $v) {
			$liste[$i] = ((($new_max) * ($v - $min)) / ($max - $min));
		}

		$angle=0;
		$rr=0;
		$xx=0;
		$yy=0;
		$rr=80;

		$inc=.0329;

		$xx2=0;
		$yy2=0;

		while($angle<$width) {

			$x=200+(sin($xx)*$rr);
			$y=550+sin($yy)*($rr*3.2);

			$r=$liste[$angle]/2;
			$rr+=.007;




			$x1=$x;
			if ($x>201) $x2=$x+$r;
			//else if ($x<199) $x2=$x-$r;
			else $x2=$x;

			$y1=$y;
			$y2=$y;

			//imageline($im,$x1,$y1,$x2,$y2,$w);

			if (($xx2 || $yy2)) {
				imageline($im,$x,$y,$xx2,$yy2,$w);
				imageline($im,$xx1,$yy1,$x2,$y2,$w);
			}
			$xx2=$x;
			$yy2=$y;
			$xx1=$x2;
			$yy1=$y2;

			$xx+=$inc;
			$yy+=$inc/2;

			$angle++;
		}


	} else if ($m>199 && $m<300) {

			if ($m=='200') $f='heart';
			else if ($m=='201') $f='infinite';
			else if ($m=='202') $f='flower';

				$ligne = explode("\n", file_get_contents('curves/'.$f.'.list'));


				$min = min($liste);
				$max = max($liste);

				if ($min==0)
				$ratio=24/$max;

				$q=0;

				while($q<($width)) {
					$rand=($liste[$q]*$ratio)+2;
					list($x,$y)=explode(",",$ligne[$q]);
					$q++;

					if ($x && $y) imagefilledellipse($im,$x,$y-28,$rand,$rand,$w);
				}


				imagealphablending($im, false);
				imagefilledellipse($im,200,1040,40,202,$b);



			} else if ($m>299 && $m<400) {

					if ($m=='300') $f='heart';
					else if ($m=='301') $f='infinite';
					else if ($m=='302') $f='flower';

						$ligne = explode("\n", file_get_contents('curves/'.$f.'-2.list'));


						$min = min($liste);
						$max = max($liste);

						//if ($min==0);
						$ratio=23/$max;

						$ratio2=1.25;
						$ratio3=1.5;

						if ($m=='301') {
							$ratio2=1.15;
							$ratio3=1.3;
						}

						$q=0;

						while($q<$width) {
							$rand=($liste[$q]*$ratio)+5;
							list($x,$y)=explode(",",$ligne[$q]);
							$q++;

							if ($x && $y) {
								imagefilledellipse($im,$x,$y-35,$rand/2,$rand/1.6,$w);

								$x1=($x*$ratio2)-(((400*$ratio2)-400)/2);
								$y1=($y*$ratio2)-(((2160*$ratio2)-2160)/2);
								imagefilledellipse($im,$x1,$y1-35,$rand/1.5,$rand/1.3,$w);

								$x1=($x*$ratio3)-(((400*$ratio3)-400)/2);
								$y1=($y*$ratio3)-(((2160*$ratio3)-2160)/2);
								imagefilledellipse($im,$x1,$y1-35,$rand,$rand,$w);

							}
						}


						imagealphablending($im, false);
						imagefilledellipse($im,200,1040,40,202,$b);



					} else {

		$height=250;

		$min = min($liste);
		$max = max($liste);

		if ($min==$max) $max+=.1;

		$new_min = 0;
		$new_max = $height;
		foreach ($liste as $i => $v) {
			$liste[$i] = 50+((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
		}

		$angle=0;
		while($angle<360) {
			$angler= (0 - $angle + 1) / 180 * M_PI;

			$x=$liste[$angle] * cos($angler) + 500;
			$y=$liste[$angle] * sin($angler) + 500;

			imageline($im,500,500,$x,$y,$w);

			$angle++;
		}


		imagealphablending($im, false);
		imagefilledellipse($im,500,500,100,100,$w);

	}



	if ($e=='debug') {
		//$im2=imagecreatefrompng ('../test-epod.png');
		$im2=imagecreatetruecolor(400,2160);
		imagesavealpha($im2, true);
		imagefill($im2, 0, 0, $blanc);
	} else {
		$im2=imagecreatetruecolor(400,2160);
		imagesavealpha($im2, true);
		imagefill($im2, 0, 0, $blanc);
	}

	imagecopyresampled($im2, $im, 0, 0, 0, 0, 400, 2160, 400, 2160);

	//if ($m>98) imagecopyresampled($im2, $im, 0, 0, 0, 0, 400, 2160, 400, 2160);
	//else imagecopyresampled($im2, $im, 0, 835, 0, 0, 400, 400, 1000, 1000);


	if ($e=='debug') {
		header ('Content-Type: image/png');
		imagepng($im2);
	} else {
		imagepng($im2,"generated/".$file."_".$e."-F.png");

		/*

		ON NE FAIT PLUS LE BACK \o/

		$im3=imagecreatetruecolor(400,2160);
		imagesavealpha($im3, true);
		imagefill($im3, 0, 0, $blanc);
		imagecopyresampled($im3, $im, 0, 700, 0, 0, 400, 400, 1000, 1000);

		imagepng($im3,"generated/".$file."_".$e."-B.png");
		imagedestroy($im3);
		*/

	}
	imagedestroy($im);
	imagedestroy($im2);
}

function thickline( $img, $x1, $y1, $x2, $y2, $color, $thickness ) {
	$radius = $thickness * .5;
	$vx = $x2 - $x1;
	$vy = $y2 - $y1;
	$steps = ceil( .5 + max( abs($vx), abs($vy) ) );
	$vx /= $steps;
	$vy /= $steps;
	$x = $x1;
	$y = $y1;
	while( $steps --> 0 ) {
		imagefilledellipse( $img, $x, $y, $radius, $radius, $color );
		$x += $vx;
		$y += $vy;
	}
}

?>
