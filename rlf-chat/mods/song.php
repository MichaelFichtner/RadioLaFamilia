<?php

class song {

	public static function songR($para)
		{
			$wieviel = NULL;
			$coma = ', ';
			//var_dump($para);
				$wieviel = count($para);
				//var_dump($wieviel); // Anzahl überprüfung
				
					if ($wieviel > '7')
					{
						
						$differenz = $wieviel-7;
						$reinigen = array($para[6]);

						for ($zu = 1; $zu <= $differenz; $zu++)
						{ 
							
							array_push($reinigen, $coma);
							$was = $para[6 + $zu];

							array_push($reinigen, $was);
													
						}

						for ($zy = 0; $zy < $differenz; $zy++)
						{
							$last = array_pop($para);
							unset($last);
						}

						$neweinzel = implode(' ', $reinigen);
						$para[6] = $neweinzel;   
					}
			return $para[6];	
		}
	
}