<?php

namespace Libre3d\Render3d;

/**
 * Customizer class extends from Render3d class.
 * run php composer.phar dump-autoloader when added new class
 */
class Customizer extends Render3d {
	/*@
	 * Read scad file and extract parameters.
	 * 
	 * @return parameters in 2D array
	 */
	 public function readSCAD($scadfile = null) {
	 	if(!empty($scadfile)) {
	 		//@ get file info, fielname, dirname
	 		$pathInfo = pathinfo($scadfile);
	 		//@ check if it is scad file
	 		if(strtolower($pathInfo['extension']) !== "scad") {
	 			return '';
	 		}
	 		//@ open scad file to read
		 	$openscad = fopen($scadfile,"r") or die("Unable to open scad file!");
		 	//@ create new scad filename
		 	//$filename = (strlen($pathInfo['filename']) > 50) ? substr($pathInfo['filename'],0,50) : $pathInfo['filename'];
		 	//$newfilename = $pathInfo['dirname']."/".$filename."_".date('Y_m_d_H-i-s').".scad";
		 	//@ open new scad file to write
			//$newscad = fopen($newfilename,"w") or die("Unable to create new scad file!");
			//@ 2D array variable
			$result = array();
			$indexArray = 0;
			//@read from original scad file to the new one
		 	while(!feof($openscad)) {
	  			$string = fgets($openscad);
	  			if(strpos($string,"{") !== false) {
	  					//@ first { , no more parameter
	  					break;
	  			}
	  			if(strpos($string,"(") == false) {
	  				//@ no parenthesis in the line
	  				
	  				if($string[0] !== "\n") {
		  				//@ not empty line
			  			$strCheck = substr($string,0,2);
			  			if($strCheck == "//") {
			  				//@ there is description
			  				$descriptionStr = $string;
			  			}
			  			else {
			  				//@ variable line
			  				if(strpos($string,"+") == false && strpos($string,"*") == false && strpos($string,"-") == false) {
			  					//@ no calculation with/without other variables, otherwise ignore
				  				list($part1,$part2) = explode("=",$string);
				  				$varName = str_replace(' ','',$part1);
				  				list($part1,$part2) = explode(";",$part2);
				  				//@ remove space and double quote
				  				$varValue = str_replace(' ','',$part1);
				  				$varValue = str_replace('"','',$varValue);
				  				//@ get rid of white space at the end from explode func.
				  				$part2 = trim($part2);
				  				if(strpos($varValue,"/") == false) {
					  				if(!empty($part2)) {
					  					//@ there is possible values
					  					$possibleVal = $part2;
					  				}
					  				else {
					  					//@ no possible value
					  					$possibleVal = '';
					  				}
					  				//@ put into array
					  				$result[$indexArray] = array();
					  				$result[$indexArray][0] = $descriptionStr;
					  				$result[$indexArray][1] = $varName;
					  				$result[$indexArray][2] = $varValue;
					  				$result[$indexArray][3] = $possibleVal;
					  				$indexArray += 1;
					  			}
			  				}
			  			}
			  		}
			  		else {
			  			//@ empty line
			  			$descriptionStr = '';
			  		}
		  		}
		  		
			}

			fclose($openscad);
			//fclose($newscad);
		}
		else {
			//@ return empty if no filename
			return '';
		}
		return $result;
	 }
	 /*@
	 * Write new customized scad file with a new name.
	 * 
	 * @return void
	 */
	 public function writeSCAD($scadfile = null,$result = null) {
	 	//echo "in writeSCAD function...<br>";
	 	//@ get file info, fielname, dirname
	 		$pathInfo = pathinfo($scadfile);
	 		//@ open scad file to read
	 		//echo "original scad file ".$scadfile." ...<br>";
		 	$openscad = fopen($scadfile,"r") or die("Unable to open scad file!");
		 	//@ create new scad filename
		 	$filename = (strlen($pathInfo['filename']) > 50) ? substr($pathInfo['filename'],0,50) : $pathInfo['filename'];
		 	$newscadname = $filename."_".date('Y_m_d_H-i-s').".scad";
		 	$newfilename = $pathInfo['dirname']."/".$newscadname;
		 	//@ open new scad file to write
			$newscad = fopen($newfilename,"w") or die("Unable to create new scad file!");
			//@ write the updated parameters to a new scad file
			
			//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
			$indexArray = 0;			
			while(!feof($openscad)) {
	  			$string = fgets($openscad);
	  			if(strpos($string,"{") !== false) {
	  				//@ first { , no more parameter
	  				//@ start writing
	  				fwrite($newscad, $string);
	  				break;
	  			}
	  			if(strpos($string,"(") == false) {
	  				//@ no parenthesis in the line
	  				if($string[0] !== "\n") {
		  				//@ not empty line
			  			$strCheck = substr($string,0,2);
			  			if($strCheck == "//") {
			  				//@ there is description or just comment
			  				fwrite($newscad, $string);
			  			}
			  			else {
			  				//@ variable line
			  				if(strpos($string,"+") == false && strpos($string,"*") == false && strpos($string,"-") == false) {
			  					//@ no calculation with/without other variables, otherwise ignore
				  				list($part1,$part2) = explode("=",$string);
				  				//$varName = str_replace(' ','',$part1);
				  				list($part1,$part2) = explode(";",$part2);
				  				$varValue = str_replace(' ','',$part1);
				  				if(strpos($varValue,"/") == false) {
					  				//@ variable name, value, and possible value
									//@ if possible value is not empty
									if(!empty($result[$indexArray][3])) {
										if(ctype_alpha($result[$indexArray][2])) {
											fwrite($newscad, $result[$indexArray][1]." = ".'"'.$result[$indexArray][2].'"'."; ".$result[$indexArray][3]."\n");
										}
										else {
											fwrite($newscad, $result[$indexArray][1]." = ".$result[$indexArray][2]."; ".$result[$indexArray][3]."\n");
										}
									}
									else {
										//@ no possible value comment
										if(ctype_alpha($result[$indexArray][2])) {
											fwrite($newscad, $result[$indexArray][1]." = ".'"'.$result[$indexArray][2].'"'."; \n");
										}
										else {
											fwrite($newscad, $result[$indexArray][1]." = ".$result[$indexArray][2]."; \n");
										}
									}
					  				$indexArray += 1;
					  			}
					  			else {
					  				//@ ignore variable with calculation
			  						fwrite($newscad, $string);
					  			}
			  				}
			  				else {
			  					//@ ignore variable with calculation
			  					fwrite($newscad, $string);
			  				}
			  			}
			  		}
			  		else {
			  			//@ empty line
			  			fwrite($newscad, "\n");
			  		}
		  		}
		  		else {
		  			//@ function contain ()
		  			fwrite($newscad, $string);
		  		}
		  		
			}//@ end while(!feof($openscad))
			//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
			
			//@ write the rest from the original file
			while(!feof($openscad)) {
				//@ write the rest of the file, no more check
	  			$string = fgets($openscad);
	  			fwrite($newscad, $string);
			}
			//@ read until found {, then start writing the rest

			fclose($openscad);
			fclose($newscad);
			
			//@ return new scad filename
			return $newscadname;
	 }
	 /*@
	 * Generate variable name in bold
	 * 
	 * @return html format variable name
	 */
	 private function generateVarname($varName = null) {
	 	if(!empty($varName)) {
	 		return "<b>". ucwords(str_replace('_',' ',$varName)) ."</b>";
	 	}
	 }
	  /*@
	 * Generate html element for customization
	 * 
	 * @return html element string
	 */
	 public function generateElement($dataRow = NULL,$index) {
	 	//@ 0:description, 1:variable name, 2: value, 3:possible value
	 	//@ var_dump($dataRow);
	 	if(!empty($dataRow[3]) && strpos($dataRow[3],"[") !== false && strpos($dataRow[3],"]") !== false) {
	 		//@ remove comment sign
 			$possibleVal = str_replace('/','',$dataRow[3]);
 			//@ remove space
 			$possibleVal = str_replace(' ','',$possibleVal);
 			//@ remove []
 			$possibleVal = str_replace('[','',$possibleVal);
 			$possibleVal = str_replace(']','',$possibleVal);
	 		//@ generate dropdown and slider
	 		if(strpos($possibleVal,",") !== false) {
	 			//@ with comma in possible values, drop down box
	 			$dataRow[2] = trim($dataRow[2]);
	 			//@ $dataRow[2] = str_replace('"','',$dataRow[2]);
	 			//@ create select element
	 			$result = "<p>". self::generateVarname($dataRow[1]) ." ".$dataRow[0]."</p><p><select name='var".$index."' >";
	 			//@ check label or not?
	 			if(strpos($possibleVal,":") !== false) {
	 				//@ option with label
	 				$options = explode(",",$possibleVal);
	 				foreach ($options as $option) {
	 					$option = trim($option);
	 					list($val,$label) = explode(":",$option);
	 					$val = trim($val);
	 					$label = trim($label);
	 					if($val == $dataRow[2]) {
	 						$result .= " <option value='".$val."' selected>".$label."</option>";
	 					}
	 					else {
	 						$result .= " <option value='".$val."'>".$label."</option>";
	 					}
	 				}
	 			}
	 			else {
	 				//@ option with no label
	 				$options = explode(",",$possibleVal);
	 				foreach ($options as $option) {
	 					$option = trim($option);
	 					if($option == $dataRow[2]) {
	 						$result .= " <option value='".$option."' selected>".$option."</option>";
	 					}
	 					else {
	 						$result .= " <option value='".$option."'>".$option."</option>";
	 					}
	 				}
	 			}
	 			$result .= "</select></p>";
	 			return $result;
	 		}
	 		else {
	 			//@ no comma in possible values, slider
	 			if(strpos($possibleVal,":") == false) {
	 				//@ only max value, min is 0
	 				//@ create range element
	 				$result = "<p>". self::generateVarname($dataRow[1]) ." ".$dataRow[0]."</p><p><input type=\"range\" name=\"var".$index."\" min=\"0\" max=\"".trim($possibleVal)."\" value=\"".trim($dataRow[2])."\" oninput=\"showValue(this.value,'range".$index."')\"><span id=\"range".$index."\">".trim($dataRow[2])."</span></p>";
	 			}
	 			else {
	 				//@ min/max, min/step/max
	 				$values = explode(":",$possibleVal);
	 				$num = count($values);
	 				if($num == 2) {
	 					//@ only min and max
	 					$result = "<p>". self::generateVarname($dataRow[1]) ." ".$dataRow[0]."</p><p><input type=\"range\" name=\"var".$index."\" min=\"".trim($values[0])."\" max=\"".trim($values[1])."\" oninput=\"showValue(this.value,'range".$index."')\" value=\"".trim($dataRow[2])."\" ><span id=\"range".$index."\">".trim($dataRow[2])."</span></p>";
	 				}
	 				else {
	 					//@ min,max,and step
	 					$result = "<p>". self::generateVarname($dataRow[1]) ." ".$dataRow[0]."</p><p><input type=\"range\" name=\"var".$index."\" min=\"".trim($values[0])."\" max=\"".trim($values[2])."\" step=\"".trim($values[1])."\" value=\"".trim($dataRow[2])."\" oninput=\"showValue(this.value,'range".$index."')\"><span id=\"range".$index."\">".trim($dataRow[2])."</span></p>";
	 				}
	 			}
	 			return $result;
	 		}
	 	}
	 	else {
	 		//@ generate textbox, no possible value
	 		return "<p>". self::generateVarname($dataRow[1]) ." ".$dataRow[0]."</p><p><input type='text' name='var".$index."' value='".$dataRow[2]."'></p>";
	 	}
	 }
	 
}