<?php
/**
 * XAggegrator Post
 * class that will reflect a result found in one of the XAggregator sources.
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
class XAggPost {
    //XAggregator fields
    private $source;
    private $id;
    private $user;
    private $title;
    private $message;
    private $date;
    private $link;

    /**
     * Class constructor
     * 
     * @param string $source
     * @param string $id
     * @param XAggUser $user
     * @param string $title
     * @param string $message
     * @param int $date
     * @param string $link 
     */
    public function __construct($source, $id, $user, $title, $message, $date, $link){
        $this->source = $source;
        $this->id = $id;
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->date = $date;
        $this->link = $link;
    }

    /**
     * getter function
     *
     * @return string
     */
    public function getSource(){
        return $this->source;
    }

    /**
     * getter function
     *
     * @return string
     */
    public function getId(){
        return $this->id;
    }

    /**
     * getter function
     *
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

   /**
     * getter function
     *
     * @return string
     */
    public function getMessage(){
        return $this->message;
    }

    /**
     * getter function
     *
     * @return string
     */
    public function getDate(){
        return date("d-m-Y h:i:s", $this->date);
    }

    /**
     * getter function
     *
     * @return int
     */
    public function getDateEpoch(){
        return $this->date;
    }

    /**
     * getter function
     *
     * @return XAggUser
     */
    public function getUser(){
        return $this->user;
    }

    /**
     * getter function
     *
     * @return string
     */
    public function getLink(){
        return $this->link;
    }
}
?>
