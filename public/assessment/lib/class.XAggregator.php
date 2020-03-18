<?php
require_once("class.XAggTwitter.php");
require_once("class.XAggMobyPicture.php");
require_once("class.XAggQuery.php");
/**
 * XAggregator library
 * Aggregates results from several soial media sources
 * Currently including:
 * - MobyPicture
 * - Twitter
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
class XAggregator {

    //sources
    private $twitter;
    private $mobypicture;

    /**
     * Class constructor
     */
    public function __construct(){
        $this->twitter = new XAggTwitter();
        $this->mobypicture = new XAggMobyPicture();
    }

    /**
     *
     * @param string $query
     * @param string $all
     * @param string $sort
     * @param string $dir
     * @param string $address
     * @return DOMDocument 
     */
    public function search($query, $all, $sort, $dir, $address = null){
        //create XAggQuery
        $query = new XAggQuery($query, $all, $address);
        //retrieve results from all sources
        $results = array();
        $results[] = $this->twitter->search($query);
        $results[] = $this->mobypicture->search($query);
        //merge results
        $results = array_merge($results[0], $results[1]);
        //sort results
        $results = $this->sort($results, $sort, $dir);
        //translate to XML
        $dom = $this->toXML($results);
        return $dom->saveXML();
    }

    /**
     * Sort results
     * 
     * @param XAggPost[] $results
     * @param string $sort
     * @param string $dir
     * @return XAggPost[] 
     */
    private function sort($results, $sort, $dir){
        $output = array();
        //create array with appropriate key
        foreach($results as $result){
            $date = $result->getDateEpoch();
            if($sort=="date"){
                $output[$date] = $result;
            }
            elseif($sort=="user"){
                $output[strtolower($result->getUser()->getUsername()).$date] = $result;
            }
            else{
                $output[$result->getSource().$date] = $result;
            }
        }
        //sort in desired direction
        if($dir=="asc"){
            ksort($output);
        }
        else{
            krsort($output);
        }
        return $output;
    }

    /**
     * Translate the results to XML
     * 
     * @param XAggPost[] $results
     * @return DOMDocument 
     */
    private function toXML($results){
        //DOM document
        $dom = new DOMDocument("1.0","iso-8859-1");
        //set some pretty print settings
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        //create <results> node
        $nodes = $dom->createElement("results");
        foreach($results as $result){
            //create <result> node
            $node = $dom->createElement("result");
            $source = $dom->createElement("source", $result->getSource());
            $id = $dom->createElement("id", $result->getId());
            $title = $dom->createElement("title", utf8_encode(htmlentities($result->getTitle())));
            $message = $dom->createElement("message", utf8_encode(htmlentities($result->getMessage())));
            $date = $dom->createElement("date", $result->getDate());
            $date_epoch = $dom->createElement("date_epoch", $result->getDateEpoch());
            $link = $dom->createElement("link", $result->getLink());
            $author = $dom->createElement("author");
            $username = $dom->createElement("username", $result->getUser()->getUsername());
            $fullname = $dom->createElement("fullname", $result->getUser()->getFullname());
            $url = $dom->createElement("url", $result->getUser()->getUrl());
            $author->appendChild($username);
            $author->appendChild($fullname);
            $author->appendChild($url);
            $node->appendChild($source);
            $node->appendChild($id);
            $node->appendChild($title);
            $node->appendChild($message);
            $node->appendChild($date);
            $node->appendChild($date_epoch);
            $node->appendChild($link);
            $node->appendChild($author);
            $nodes->appendChild($node);
        }
        $dom->appendChild($nodes);
        return $dom;
    }

}
?>
