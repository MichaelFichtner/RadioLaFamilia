<?php

/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 16.10.15
 * Time: 23:38
 */
class test
{
    private $interpreten = array();
    private $songs = array();

    /**
     * @return array
     */
    public function getInterpreten()
    {
        return $this->interpreten;
    }

    /**
     * @param array $interpreten
     */
    public function setInterpreten($interpret)
    {
        $this->interpreten[] = $interpret;
    }

    /**
     * @return array
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * @param array $songs
     */
    public function setSongs($song)
    {
        $this->songs[] = $song;
    }
/* User online abfragen
* SELECT * FROM PiF_ctII_users WHERE user_id = (SELECT online_user FROM  PiF_ctII_online);
*/

}