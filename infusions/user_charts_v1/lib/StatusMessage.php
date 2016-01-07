<?php

/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 24.10.15
 * Time: 21:13
 */
class StatusMessage
{
    private $messages = array();

    /**
     * @return array
     */
    public function printMessages()
    {
        if ($this->messages)
        {
            for ($i = 0; $i < count($this->messages); $i++)
            {
                echo '<h4 id="status">Status Meldung : ' . $this->messages[$i] . '!<br></h4>';
            }
        }
    }

    /**
     * @param array $messages
     */
    public function addMessages($message)
    {
        array_push($this->messages, $message);
    }

    public function hasMesasage()
    {
        if (count($this->messages) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }



}