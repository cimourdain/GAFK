<?php
namespace GAFK;

/*********************************
#Common Functions trait
> toolbox to handle recurrent controls on values (string values etc.)

*********************************/

trait CommonFunctions
{
		public function isValidString($str, $min = -1, $max = -1)
		{
			if(!is_string($str) || empty($str))
			{
				//throw new \InvalidArgumentException('CommonFunctions :: Invalid string value "'.$str.'" ('.gettype($str).').');
				return false;
			}else if (($min != -1 && strlen($str) < $min) || ($max != -1 && strlen($str) > $max)) {
				//throw new \InvalidArgumentException('CommonFunctions :: Invalid string size for "'.$str.'" '.strlen($str).' < '.$min.' or '.strlen($str).' > '.$max.'.');
				return false;
			}else{
				return true;
			}

		}

		public function array_combine_diff_sizes($a, $b) 
		{ 
		    $acount = count($a); 
		    $bcount = count($b); 
		    $size = ($acount > $bcount) ? $bcount : $acount; 
		    $a = array_slice($a, 0, $size); 
		    $b = array_slice($b, 0, $size); 
		    return array_combine($a, $b); 
		} 
}

?>