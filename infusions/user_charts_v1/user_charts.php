<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright � 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_charts.php
| CVS Version: 1.00
| Author: INSERT NAME HERE
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/header.php";

include INFUSIONS."user_charts/infusion_db.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."user_charts/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."user_charts/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS . "user_charts/locale/English.php";
}
	/**
	 * My Code Start
	 */

	echo "<h1 class='text'>Hörer - Hitparade</h1>";

require INFUSIONS.'user_charts/lib/AmazonECS.class.php';
require INFUSIONS.'user_charts/lib/SearchCover.php';

$result = dbquery("SELECT s.*,(SELECT COUNT(chart_id) + 1 FROM " . DB_CHARTS . " WHERE chart_punkte > s.chart_punkte) AS chart_platz FROM " . DB_CHARTS . " s ORDER BY chart_punkte DESC;");
$i = '1';
?>
	<link rel="stylesheet" href="<?php echo INFUSIONS ?>user_charts/css/my.css">
	<link rel="stylesheet" href="css/ionRangeSliderDemo/css/normalize.css">
	<link rel="stylesheet" href="css/ionRangeSliderDemo/css/ion.rangeSlider.css">
	<link rel="stylesheet" href="css/ionRangeSliderDemo/css/ion.rangeSlider.skinHTML5.css">

	<div id="usercharts"> <!-- 710px -->
		<table class="table">
			<caption></caption>
			<thead>
			<tr>
				<th>Platz</th>
				<th>Interpret</th>
				<th>Song</th>
				<th>Punkte</th>
				<th>Vorw.</th>
				<th>Trend</th>
				<th>Woche</th>
				<th>Cover</th>
				<th>Vote</th>
			</tr>
			</thead>
			<tbody>
			<?php while($row = dbarray($result)){
                ?>
				<tr>
					<td>
						<?php
                        $tempplatz = 0;
						if(!empty($tempplatz) && $tempplatz == $row["chart_platz"]){
							echo $row["chart_platz"] + 1;
						}else{
							echo $row["chart_platz"];
						}
						?>
					</td>

					<td>
						<?php echo $row["chart_interpret"]; ?>
					</td>

					<td>
						<?php echo $row["chart_song"]; ?>
					</td>

					<td>
						<?php echo $row["chart_punkte"]; ?>
					</td>

					<td>
						<?php echo $row["chart_vorwoche"]; ?>
					</td>

					<td>
						<?php echo $row["chart_trend"]; ?>
					</td>

					<td>
						<?php echo $row["chart_woche"]; ?>
					</td>

					<td>
						<img id="cover" src="<?php echo $row["chart_cover"]; ?>">
					</td>

					<td width="20%">

						<input id="songId" type="hidden" name="id" value="<?php echo $row["chart_id"] ?>">

                    <span id="vote_<?php echo $i ?>">

                        <input type="text" id="range_<?php echo $i ?>" value="<?php echo $row["chart_id"] ?>" name="range" />

                    </span>
                    <span id="ready_<?php echo $i ?>" style="display: none">
                        <img id="gruenerman" src="img/Maennchen_gruenerHaken.png">
                    </span>
					</td>
				</tr>
				<?php $tempplatz = $row["chart_platz"];$i++;
			} ?>
			</tbody>
		</table>
	</div>



    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="js/ion.rangeSlider.min.js"></script>

	<script>
        $(document).ready(function()
        {
            console.log("READY");
            for (var i = 1; i <= 20; i++) {
                var $range = $("#range_" + i);
                var test = ('<h1>Das ist ja geil .....</h1>');
                $range.ionRangeSlider({
                    type: "single",
                    grid: true,
                    min: 0,
                    max: 10,
                    from: 5,
                    from_min: 1,
                    values: ["0",
                        "1", "2", "3", "4", "5",
                        "6", "7", "8", "9", "10"
                    ],
                    grid_snap: true,
                    hide_min_max: true,
                    to: 10,
                    onFinish: function (data) {
                        var $DatenBank = <?php echo '"'. DB_CHARTS.'"' ?>;
                        var $songId = data.input.context.defaultValue;
                        var $points = data.from;
                        var $rangenr = data.slider.context.id;
                        var $rangenummer = $rangenr.slice(6, 8);
                        var $elementid = "#vote_";
                        var $elementready = "#ready_";
                        var $elemvote = $elementid.concat($rangenummer);
                        var $elemready = $elementready.concat($rangenummer);

                        console.log($songId);
                        console.log($points);

                        $.ajax({
                            url: "lib/VoteWrite.php",
                            data: {songId: $songId, points: $points, db: $DatenBank},
                            datatype: "json",
                            type: "POST",
                            success: function (res) {
                                $($elemvote).hide();
                                $($elemready).show();
                                console.log($elemready);
                                console.log($elemvote);
                                console.log($points);
                                console.log(res);
                            },
                            error: function () {
                                alert("Fehler .....");
                            }
                        });
                    }
                });
            }
            $("#testBB").click(function () {
                $.ajax({
                    url: "app/test.php",
                    data: {songId: 180, points: 123},
                    datatype: "json",
                    type: "POST",
                    success: function (res) {
                        console.log(res);
                    }
                });
            });
        });
	</script>
<?php



require_once THEMES."templates/footer.php";
