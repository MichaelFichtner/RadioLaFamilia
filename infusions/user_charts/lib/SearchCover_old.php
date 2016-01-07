<?php

/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 13.10.15
 * Time: 22:43
 */

require_once "/var/www/html/infusions/user_charts/lib/AmazonECS.class.php";

error_reporting(E_ALL & ~E_NOTICE);

// TODO-Michy Notize fehler suchen

class SearchCover
{

    private $Interpret = '';
    private $Song = '';
    private $res = '';
    private $id = '';
    private $cover = '';

    public function __construct($interpret, $song, $id){
        $result = dbquery("SELECT chart_interpret chart_song FROM " . DB_CHARTS);
        $this->res = $result;
        $this->Interpret = $interpret;
        $this->Song = $song;
        $this->id = $id;
    }
// Todo-Michy Fehler abfangen !!!!
    public function getTest(){
        $res = $this->Interpret . " - " . $this->Song;
        $this->CoverSearch($res);
        if($this->cover['Items']['Item']['SmallImage']){
            $cover = htmlentities($this->cover['Items']['Item']['SmallImage']['URL']);
            $sql = "UPDATE " . DB_CHARTS . " SET chart_cover = \"" . htmlspecialchars($cover) . "\" WHERE chart_id = " . $this->id . ";";
            $ready = dbquery($sql);
            return $ready;
        }else{
            $cover =  htmlentities($this->cover['Items']['Item'][0]['SmallImage']['URL']);
            $sql = "UPDATE " . DB_CHARTS . " SET chart_cover = \"" . htmlspecialchars($cover) . "\" WHERE chart_id = " . $this->id . ";";
            $ready = dbquery($sql);
            return $ready;
        }
    }

    public function CoverSearch($nameSong)
    {
        try
        {
            // get a new object with your API Key and secret key. Lang is optional.
            // if you leave lang blank it will be US.
            $amazonEcs = new AmazonECS('AKIAJ45XKTAZEFQWMN3Q', 'VwUj6FDtpTu/JcAl6cLsGfw0eQIc2A3uvhYv3mYv', 'de', 'ASSOCIATE TAG');
            //foreach ($nameSong as $song){
            $this->cover = $amazonEcs->returnType('1')->country('de')->category('All')->responseGroup('Small,Images')->search($nameSong);
            //$track = $amazonEcs->returnType('1')->country('de')->category('All')->responseGroup('Tracks')->search($nameSong);
            //var_dump($track['Items']['Item']);
            //return $this->cover;
            //}

            /*        $response = $amazonEcs->returnType('1')->country('de')->category('All')->responseGroup('Small,Images')->search('Fields Of The Nephilim - Requiem Xiii-33 (Le Veilleur Silencieux)');
                     //var_dump($response);
                    if (isset($response['Items']['Item']) ) {

                        if (isset($response['Items']['Item'])) {

                            //loop through each item
                            foreach ($response['Items'] as $result) {
                                //var_dump($result['ItemLinks']);

                                //check that there is a ASIN code - for some reason, some items are not
                                //correctly listed. Im sure there is a reason for it, need to check.
                                if (isset($result['ASIN'])) {

                                    //store the ASIN code in case we need it
                                    $asin = $result['ASIN'];
                                    print_r($asin);
                                    //check that there is a URL. If not - no need to bother showing
                                    //this one as we only want linkable items
                                    if (isset($result['DetailPageURL'])) {

                                        //set up a container for the details - this could be a DIV
                                        echo "<p min-height: 60px; font-size: 90%;'>";

                                        //create the URL link
                                        echo "<a target='_Blank' href='" . $result['DetailPageURL'] . "'>";

                                        //if there is a small image - show it
                                        if (isset($result['SmallImage']['URL'])) {

                                            echo "<img src='" . $result['SmallImage']['URL'] . "'>";

                                            echo "<h3>". $result['SmallImage']['URL'] . "</h3>";
                                        }

                                        // if there is a title - show it
            //                            if (isset($result['ItemAttributes']['Title'])) {
            //                                //var_dump($result);
            //                                echo "<h2>" . $result['ItemAttributes']['Title'] . "</h2>";
            //                                echo "<h2>" . $result['ItemAttributes']['Creator']['_'] . "</h2><br/>";
            //                            }

                                        //close the paragraph
                                        echo "</a></p>";

                                    }
                                }
                            }
                        }
                    }*/

        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function wasKommt(){
        return $this->Interpret;
    }

}