<?php
require_once("class.XAggSource.php");
require_once("class.XAggPost.php");
require_once("class.XAggUser.php");
/**
 * XAggTwitter
 * class that will perform searches on Twitter posts
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
class XAggTwitter extends XAggSource {

    private $base_url = "http://search.twitter.com/search.atom";

    /**
     * This function translates found twitter entries (XML) to
     * XAggPost objects.
     *
     * @param SimpleXMLElement[] $entries
     * @return XAggPost[]
     */
    protected function parseEntries($xml){
        $entries = $xml->entry;
        $results = array();
        foreach($entries as $entry){
            $id = (string) $entry->id;
            $date = strtotime($entry->published);
            $title = (string) $entry->title;
            $message = (string) $entry->content;
            $author = $entry->author;
            $name = $author->name;
            $fullname = trim(preg_replace("/[^.?]+\s\(/", "", $name));
            $fullname = substr($fullname, 0, -1);
            $username = trim(preg_replace("/\(([^\)]+)\)/", "", $name));
            $url = (string) $author->uri;
            $link = (string) $entry->link->attributes()->href;
            $user = new XAggUser($username, $fullname, $url);
            $post = new XAggPost("Twitter", $id, $user, $title, $message, $date, $link);
            $results[] = $post;
        }
        return $results;
    }

    /**
     * Translate XAggQuery to a Twitter API query
     * 
     * @param XAggQuery $query
     * @return string 
     */
    protected function constructQuery($query){
        //search terms
        if($query->getAll()){
            $url_query = implode(" ", $query->getContains());
        }
        else{
            $url_query = implode(" OR ", $query->getContains());
        }
        //restrict query to posts from user
        if(!is_null($query->getUsername())){
            $url_query .= " from:".$query->getUsername();
        }
        $url_query = "?q=".urlencode($url_query);
        //restrict query to geo location
        if(!is_null($query->getGeo())){
            $geo = $query->getGeo();
            $geo['radius'] = '25km';
            $url_query .= "&geocode=".urlencode(implode(",", $geo));
        }
        $url_query = $this->base_url . $url_query;
        return $url_query;
    }
}
?>
