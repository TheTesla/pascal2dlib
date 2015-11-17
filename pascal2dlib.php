<?php
header("Content-Type: application/xml");
?>
<?xml version='1.0' encoding='ISO-8859-1'?>
<?xml-stylesheet type='text/xsl' href='image_metadata_stylesheet.xsl'?>
<dataset>
<name>imglab dataset</name>
<comment>Converted from PASCAL format by pascal2dlib.php.</comment>
<images>
<?php
	$annodirname = "Annotation";
	$annofilenamevec = scandir($annodirname, SCANDIR_SORT_ASCENDING);

	foreach($annofilenamevec as $annofilename) {
		if(".txt"!=substr($annofilename,-4)) continue;
		$annofile = fopen($annodirname."/".$annofilename , "r") or die("Unable to open file!");
		$boxveclen = 0;
		while(!feof($annofile)){
			$line = fgets($annofile);
			if("Image filename" == substr($line, 0, 14)){
				$imgfilename = substr($line, 18, -3);
			}
			if("Bounding box for object" == substr($line, 0, 23)){
				$value = substr($line, strpos($line, ":")+1);
				$second = substr($value, strpos($value, " - ")+1);
				$boxvec_x1[(int)substr($line, 24)] = (int) substr($value, strpos($value, "(")+1);
				$boxvec_y1[(int)substr($line, 24)] = (int) substr($value, strpos($value, ", ")+1);
				$boxvec_x2[(int)substr($line, 24)] = (int) substr($second, strpos($second, "(")+1);
				$boxvec_y2[(int)substr($line, 24)] = (int) substr($second, strpos($second, ", ")+1);
				$boxveclen++;
			}
		}
		echo "<image file='$imgfilename'>";
		for($i=1; $i<=$boxveclen; $i++){
			$w = $boxvec_x2[$i]-$boxvec_x1[$i];
			$h = $boxvec_y2[$i]-$boxvec_y1[$i];
			echo "<box top='$boxvec_y1[$i]' left='$boxvec_x1[$i]' width='$w' height='$h'/>";
		}
		echo "</image>";
		fclose($annofile);
	}

?>
</images>
</dataset>
