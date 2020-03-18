<?php
/**
 * XAggSource
 * abstract base class for every source
 *
 * @author GCM Kemps
 * @date 15-06-2011
 */
abstract class XAggSource {

    /**
     * Function that will perform the search on a source (Twitter, MobyPicture)
     * given a XAggQuery and will translate the results found to XAggregator posts
     *
     * @param XAggQuery $query
     * @return XAggPost[] 
     */
    public function search($query){
        //translate the XAggQuery to source query
        $url_query = $this->constructQuery($query);
        //call the API
        $curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url_query);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	curl_close($curl);
        //translate result to XML and parse the results
        libxml_use_internal_errors(true);
        try{
            $xml = new SimpleXMLElement($result);
            return $this->parseEntries($xml);
        }
        catch(Exception $e){
            return array();
        }
    }

    abstract protected function parseEntries($xml);
    abstract protected function constructQuery($query);
}
?>
