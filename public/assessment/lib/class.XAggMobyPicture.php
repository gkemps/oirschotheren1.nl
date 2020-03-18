<?php
require_once("class.XAggSource.php");
require_once("class.XAggPost.php");
require_once("class.XAggUser.php");
/**
 * XAggMobyPicture
 * class that will perform searches on MobyPicture posts
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
class XAggMobyPicture extends XAggSource {

    private $dev_key = "3nWVm0UITOr2GGNm";
    private $base_url = "http://api.mobypicture.com/?action=searchPosts&";

    /**
     * This function translates found MobyPicture entries (XML) to
     * XAggPost objects.
     * 
     * @param SimpleXMLElement[] $entries
     * @return XAggPost[] 
     */
    protected function parseEntries($xml){
        $entries = $xml->results->result;
        if(count($entries)>0){
            $results = array();
            foreach($entries as $entry){
                $id = (string) $entry->post->id;
                $date = (int) $entry->post->created_on_epoch;
                $title = (string) $entry->post->title;
                $message = (string) $entry->post->description;
                $fullname = (string) $entry->user->name;
                $username =(string) $entry->user->username;
                $url = (string) $entry->user->url;
                $link = (string) $entry->post->link;
                $user = new XAggUser($username, $fullname, $url);
                $post = new XAggPost("MobyPicture", $id, $user, $title, $message, $date, $link);
                $results[] = $post;
            }
            return $results;
        }
        return array();
    }


    /**
     * Translate XAggQuery to a MobyPicture API query
     * 
     * @param XAggQuery $query
     * @return string 
     */
    protected function constructQuery($query){
        //developer key
        $url_query[] = "k=".$this->dev_key;
        //return format
        $url_query[] = "format=xml";
        //search terms
        if(count($query->getContains()) > 0){
            $url_query[] = "searchTerms=".urlencode(implode(" ", $query->getContains()));
        }
        //search term mode ('all' or 'any')
        if($query->getAll()){
            $url_query[] = "searchTermsMode=all";
        }
        else{
            $url_query[] = "searchTermsMode=any";
        }
        //restrict query to posts from user
        if(!is_null($query->getUsername())){
            $url_query[] = "searchUsername=".urlencode($query->getUsername());
        }
        //restrict query to geo location
        if(!is_null($query->getGeo())){
            $url_query[] = "searchGeoLocation=".urlencode(implode(",", $query->getGeo()));
            $url_query[] = "searchGeoDistance=25000";
        }
        $url_query = $this->base_url . implode("&", $url_query);
        return $url_query;
    }
}
?>
