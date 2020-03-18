<?php
/**
 * XAggregator Query
 * class that represents a query on one of teh Aggregator sources
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
class XAggQuery {
    //XaggQuery fields
    private $contains;
    private $username;
    private $all;
    private $geo;

    /**
     * class constructor
     * 
     * @param string $query
     * @param string $all
     * @param string $address
     */
    public function __construct($query, $all, $address){
        $this->setUser($query);
        $this->setContains($query);
        $this->setAll($all);
        $this->setGeo($address);
    }

    /**
     * Checks if the query contains a 'user:<username>' statement and extracts the username
     * The query is returned with the found statement removed
     * 
     * @param string $query 
     */
    private function setUser(&$query){
        $matches = array();
        preg_match("/\b(user:\w*)\b/i", $query, $matches);
        if(count($matches)>0){
            $this->username = substr($matches[0], 5);
            $query = str_replace($matches[0], "", $query);
        }
        else{
            $this->username = null;
        }
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
     * If an address is given, it will ask Google Maps to translate the address to
     * a (latitude, longitude) representation 
     * 
     * @param string[]|null $address
     */
    private function setGeo($address){
        if(is_null($address)){
            $this->geo = null;
        }
        else{
            //google API url
            $url = "http://maps.googleapis.com/maps/api/geocode/xml";
            $url .= "?address=".urlencode($address)."&sensor=false";
            //make API call
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
            //translate to XML
            $xml = new SimpleXMLElement($result);
            if($xml->status == "OK"){
                //extraxt latitude and longitude
                $geo = array();
                $geometry = $xml->result->geometry->location;
                $geo['lat'] = (string) $geometry->lat;
                $geo['lng'] = (string) $geometry->lng;
                $this->geo = $geo;
            }
            else{
                //nothing found
                $this->geo = null;
            }
        }
    }

    /**
     * Getter function
     * 
     * @return string[]|null
     */
    public function getGeo(){
        return $this->geo;
    }

    /**
     * Extracts all the search terms into an array
     * 
     * @param string $query 
     */
    private function setContains($query){
        $this->contains = explode(" ", $query);
    }

    /**
     * Getter function
     * 
     * @return string[]
     */
    public function getContains(){
        return $this->contains;
    }

    /**
     * Is set to true if the query has to match all words in $contains
     * 
     * @param string $all
     */
    private function setAll($all){
        $this->all = ($all == "1" ? true : false);
    }

    /**
     * Getter function
     * 
     * @return bool
     */
    public function getAll(){
        return $this->all;
    }
    
}
?>
