<?php

class Db {

    private $_db;
    private $last_query;
    
    /**
     * ISIKDb construction
     */
    public function __construct() {
        $this->_db = mysql_connect(DBHOST, DBUSER, DBPASS)
            or trigger_error("De database kan niet geopend worden", E_USER_ERROR); //database cannot be opened
        mysql_select_db(DATABASE, $this->_db)
            or trigger_error("De database kan niet geselecteerd worden.", E_USER_ERROR); //database cannot be selected
    }

    /**
     * Change the connection to a different database
     * 
     * @param integer $db database to change to, if emtpy then database is set to constant DATABASE 
     */
    public function changeDB($db = "") {
        if (empty($db)) {
            $db = DATABASE;
        }
        mysql_select_db($db, $this->_db)
            or trigger_error("De database: {$db} kan niet geselecteerd worden.", E_USER_ERROR);
    }

    /**
     * Returns the last performed query
     * 
     * @return string 
     */
    public function lastQuery() {
        return $this->last_query;
    }

    /**
     * Run a query
     * 
     * @param string $q query to run
     * @param bool $log if log entry is required
     * @return resoure returns a mysql resource on succes, otherwise false 
     */
    public function query($q, $log = false) {
        //if log set to true, add a log entry, before query cause of LastId() and lastQuery()
        if ($log) {
            $userId 	= @$_SESSION['isik_uid'];
            $entryDate  = time();
            $dateTime   = date("Y-m-d H:i:s", $entryDate);
            $description = mysql_escape_string($q);

            $sql = "INSERT INTO systemlog (id, userId, action, entryDate)
			VALUES ('', '$userId', '$description', '$dateTime');";
            @mysql_query($sql);
        }

        $s = @mysql_query($q);

        if (!$s) {
            print "<pre>";
            print_r(debug_backtrace());
            print "</pre>";
            trigger_error("Database query failed: $q - Error:" . mysql_error(), E_USER_ERROR);
        }
        $this->last_query = $q;
        
        return $s;
    }


    /**
     * Fetch a result row as an associative array
     * 
     * @param resource $s result resource
     * @return array Returns an associative array of strings that corresponds to the fetched row
     */
    public function fetch($s) {
        return mysql_fetch_assoc($s);
    }

    /**
     * Get the ID generated in the last query
     *
     * @return integer
     */
    public function lastId() {
        return mysql_insert_id();
    }

    /**
     * Get number of affected rows in previous MySQL operation
     *
     * @return integer
     */
    public function affectedRows() {
        return mysql_affected_rows();
    }

    /**
     * Get a row from the query result
     * 
     * @param string $q query
     * @param integer $index row number
     * @return array
     */
    public function getRow($q, $index = false) {
        $s = $this->query($q);
        $r = $this->fetch($s);
        
        if(!$r){
            return false;
        }
        
        if($index){
            return $r[$index];
        }
        else{
            return $r;
        }
    }

    /**
     * Get the first cell of the first row from the result of query $q
     * 
     * @param string $q query
     * @return mixed 
     */
    public function getSingle($q) {
        $s = $this->query($q);
        $r = mysql_fetch_row($s);
        
        if(!$r){
            return false;
        }
        return ($r[0]);
    }

    /**
     * Get the result of the query with a pass by reference array
     * 
     * @param array $out result of query $q
     * @param string $q query
     */
    public function getArray(&$out, $q) {
        $s = $this->query($q);
        $out = array();

        while ($r = $this->fetch($s)) {
            $out[] = $r;
        }
    }

    /**
     * Get the result of the query with a pass by reference array with index key
     * 
     * @param array $out result of query $q
     * @param string $p array of query, array key, array value
     */
    public function getArrayExt(&$out,$p) {
        $s = $this->query($p['query']);
        $out = array();
        $k = $p['key'];
        
        while($r = $this->fetch($s)) {
            if(is_array($p['fields'])) {
                foreach($p['fields'] AS $f) {
                    $out[$r[$k]] = $r[$f];
                }
            }
            else{
                $out[$r[$k]] = $r;
            }
        }
    }

    /**
     * Construct insert query token strings
     * 
     * @param array $inarr array of field => value
     * @return array array of token strings for fields and values
     */
    public function insertTokens($inarr) {
        $farr = array();
        $varr = array();

        foreach ($inarr as $k => $v) {
            if (!is_null($v)) {
                if ($v != "NOW()") {
                    $v= "'" . mysql_real_escape_string(stripslashes($v)) . "'";
                }
            }
            else {
                $v = "NULL";
            }
            $farr[] = $k;
            $varr[] = $v;
        }
        $fields = implode(', ', $farr);
        $values = implode(', ', $varr);

        return array($fields, $values);
    }

    /**
     * Construct update query token string
     *
     * @param array $inarr array of field => value
     * @return string update token string
     */
    public function updateTokens($inarr) {
        $finalarr = array();

        foreach ($inarr as $k => $v) {
            if (!is_null($v)) {
                $v = mysql_real_escape_string(stripslashes($v));
                $s = "$k = '$v'";
            }
            else {
                $s = "$k = NULL";
            }
            $finalarr[] = $s;
        }
        $tokens = implode(', ', $finalarr);

        return $tokens;
    }

    /**
     * Construct where token string
     * 
     * @param array $inarr array of field => value
     * @return string where token string 
     */
    public function whereTokens($inarr) {
        $finalarr = array();

        foreach ($inarr as $k => $v) {
            if (!is_null($v)) {
                $v = mysql_real_escape_string(stripslashes($v));
                $s = "$k = '$v'";
            }
            else {
                $s = "$k = NULL";
            }
            $finalarr[] = $s;
        }
        $tokens = implode(' AND ', $finalarr);
        
        return $tokens;
    }

    /**
     * Insert query
     * 
     * @param string $table insert table
     * @param array $data array of field => value
     * @param bool $log if query should be logged 
     */
    public function insert($table, $data, $log = false) {
        list($fields,$values) = $this->insertTokens($data);
        $q = "INSERT INTO $table ($fields) VALUES ($values)";
        $this->query($q,$log);
    }
    
    /**
     * Update query
     * 
     * @param string $table table to update
     * @param array $data array of field => data
     * @param string $where where token string
     * @param bool $log if the query should be logged 
     */
    public function update($table, $data, $where, $log = false) {
        $t = $this->updateTokens($data);
        $q = "UPDATE $table SET $t WHERE $where";
        $this->query($q,$log);
    }

    /**
     * Delete query
     * 
     * @param string $table table to delete from
     * @param array $where array of field => value
     * @param string $post string with subquery after WHERE clause
     * @param bool $log if the query should be logged 
     */
    public function delete($table, $where, $post = "", $log = false) {
        $w = $this->whereTokens($where);
        $q = "DELETE FROM $table WHERE $w $post";
        $this->query($q, $log);
    }
}
?>