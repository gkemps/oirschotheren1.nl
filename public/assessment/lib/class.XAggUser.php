<?php
/**
 * XAggregator User
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
class XAggUser {
    private $username;
    private $fullname;
    private $url;

    /**
     * Class constructor
     * 
     * @param string $username
     * @param string $fullname
     * @param string $url 
     */
    public function __construct($username, $fullname, $url){
        $this->username = $username;
        $this->fullname = $fullname;
        $this->url = $url;
    }

    /**
     * Getter function
     *
     * @return string
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Getter function
     * 
     * @return string 
     */
    public function getFullname(){
        return $this->fullname;
    }

    /**
     * Getter function
     *
     * @return string
     */
    public function getUrl(){
        return $this->url;
    }
}
?>
