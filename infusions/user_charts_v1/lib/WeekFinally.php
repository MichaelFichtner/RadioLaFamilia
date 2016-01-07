<?php

/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 26.10.15
 * Time: 21:00
 */

//require_once "../../../../maincore.php";
//include INFUSIONS."user_charts/infusion_db.php";

class WeekFinally
{

    private $platz = '';

    private function platz(){
        $this->platz = dbquery("SELECT s.*,(SELECT COUNT(chart_id) + 1 FROM " . DB_CHARTS . " WHERE chart_punkte > s.chart_punkte) AS chart_platz FROM " . DB_CHARTS . " s ORDER BY chart_punkte DESC;");
    }

    public function UpdateChartVorwoche() {
        $this->platz();
        while($temp = dbarray($this->platz)){
            //echo "Platz : " .$temp['chart_platz'] . " ID: " . $temp['chart_id'] . "<br>";
            //echo "Sql : " . "UPDATE " . DB_CHARTS . " SET chart_vorwoche = ".$temp['chart_platz']. " WHERE chart_id = " . $temp['chart_id'] . "<br>";
            $resvorw = dbquery( "UPDATE " . DB_CHARTS . " SET chart_vorwoche = ".$temp['chart_platz']. " WHERE chart_id = " . $temp['chart_id']);
        }

        return $resvorw;

    }

    public function UpdateChartPoints (){
        $sql = "UPDATE " . DB_CHARTS . " SET chart_punkte = chart_punkte + chart_vote" ;

        $res = dbquery($sql);

        return $res;
    }

    public function VotePointsDelete (){

        $resnull = "UPDATE " . DB_CHARTS . " SET chart_vote = 0" ;

        $resnull = dbquery($resnull);

        return $resnull;

    }

    public function AddWeek(){
        $sql = "UPDATE " . DB_CHARTS . " SET chart_woche = chart_woche + 1";

        $res = dbquery($sql);

        return $res;
    }

    /**
     * wenn 8. Woche dann neu Songs einpflegen...
     * @return string
     */
    public function NewUpdate(){

        $sql1 = "DELETE FROM " . DB_CHARTS . " WHERE chart_woche = 8";

        /*$res1 = dbquery($sql1);*/

        $sql2 = "INSERT INTO " . DB_CHARTS . " (chart_interpret, chart_song) SELECT neu_interpret, neu_song FROM " . DB_NEUEINTRAG . " ON DUPLICATE KEY UPDATE chart_interpret = (SELECT neu_interpret FROM ". DB_NEUEINTRAG ."), chart_song = (SELECT neu_song FROM ".DB_NEUEINTRAG.");";

        /*$res2 = dbquery($sql2);*/

        $sql3 = "TRUNCATE TABLE " . DB_NEUEINTRAG;

        /*$res3 = dbquery($sql3);*/

        if($res = dbquery($sql1)){
            if($res = dbquery($sql2)){
                if($res = dbquery($sql3)){
                    $res = true;
                }else{
                    $res = false;
                }
            }else{
                $res = false;
            }
        }else{
            $res = false;
        }

        return $res = $res;

    }

}