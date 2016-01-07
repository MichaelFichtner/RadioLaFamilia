<?php

class song {

	public static function songR($para)
		{
			$wieviel = NULL;
				$wieviel = count($para);
				var_dump($wieviel); // Anzahl überprüfung
				
					if ($wieviel > '7')
					{
						var_dump($wieviel);
						$differenz = $wieviel-7;
						$reinigen = array($para[6]);

						for ($zu = 1; $zu <= $differenz; $zu++)
						{ 
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
				
		}
	
}