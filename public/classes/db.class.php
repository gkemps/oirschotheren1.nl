<?php
/******************************************************************************
 * PHP MySQL Database Controller Class
 * Copyright (C) 2006 Berry Zwerts
 *  
 * This class is free software; you can redistribute it and/or modify it under 
 * the terms of the GNU General Public License as published by the Free 
 * Software Foundation; either version 2 of the License, or (at your option) 
 * any later version.
 *****************************************************************************/
    require_once "classes/db.php";

    $connection = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password);
    
    class DB
    {
        const DATABASE = "heren1_oud";
        
        function __construct()
        /**
         * Database class contructor
         */
        {
            global $connection;
            //mysql_select_db(self::DATABASE, $connection)
            //    or die("Could not select database: " . 
            //       mysql_error($connection));
        }

        function query($query)
        /**
         * This function performs a query on the database and returns the
         * result as an array
         *
         * @param $query: The SQL query to perform
         *
         * @result: An array of result rows
         */
        {
            global $connection;
            $result = $connection->query($query)
                or die("MySQL Error " . mysql_errno($connection) . ": " . 
                   mysql_error($connection));

                
             $array = [];
             foreach ($result as $result) {
                $array[] = $result;
             }
             return $array;
        }

        function queryOne($query)
        /**
         * This function performs a custom query on the database and 
         * returns only one result value.
         *
         * @param $query: The SQL query to perform
         *
         * @return: The leftmost cell of the topmost row of the resultset or 
         *          null if the resultset was empty.
         */
        {
            global $connection;
            $result = $connection->query($query) or die(mysql_error()."(".$query.")");
            $array = [];
            foreach ($result as $row) {
              $array[] = $row;
            }
            return isset($array[0][0]) ? $array[0][0] : null;
        }

        function queryMany($query)
        /**
         * This function performs a custom query on the database and
         * returns the first column as an array
         *
         * @param $query: The SQL query to perform
         *
         * @return: The leftmost column of the resultset as an array or
         *          null if the resultset was empty.
         */
        {
            global $connection;
            $result = $connection->query($query) or die(mysql_error()."(".$query.")");
             
            $array = [];
            foreach ($result as $row) {
               $array[] = $row[0];
            }
            return $array;
        }

        function queryRow($query)
        /**
         * This function preforms a custom query on the database and 
         * returns only one result row.
         *
         * @param $query: The SQL query to perform
         *
         * @return: An array with column names as keys
         */
        {
            global $connection;
            $result = $connection->query($query) or die(mysql_error()."(".$query.")");
            $array = [];
             foreach ($result as $result) {
                $array[] = $result;
             }
             $return =  isset($array[0]) ? $array[0] : null;
             
             return $return;
        }

        private function _prepread($table, $key, $fields = null)
        /**
         * This function prepares a select and returns the query as a atring
         */
        {
            if( is_null($fields) )
            {
                $sel = "*";
            } else
            {
                $sel = implode(",", $fields);
            }

            if( is_array($key) )
            {
                foreach($key as $field => $val)
                {
                    $where .= $field . (is_null($val) ? " IS NULL" :
                        "=" . $this->quote($val)) . " AND ";
                }
                $where = substr($where, 0, -4);
            } elseif( is_null($key) )
            {
                $where = "1";
            } else
            {
                $where = "ID=" . $this->quote($key);
            }

            return "SELECT " . $sel . " FROM " . $table . " WHERE " . $where;
        }

        function read($table, $key, $fields = null)
        /**
         * This function reads one specific row from a table and returns it as
         * an array
         *
         * @param $table: Name of the table
         * @param $key: Either one of the following:
         *              1. An array containing one or more keys and their
         *                 corresponding values to identify one row
         *              2. A single value (usually an int). In this case it is
         *                 expected to be the value of the 'ID' column.
         *              3. null, in which case any row is valid (most useful
         *                 for readMany.
         * @param $fields: Optional array of fields to read. If omitted, all
         *                 fields are read. The values of the array has to 
         *                 contain the fields to be read.
         *
         * @return: An array with one row with column names as keys
         */
        {
            $query = $this->_prepread($table, $key, $fields);
            return $this->queryRow($query);
        }

        function readMany($table, $key, $fields = null)
        /**
         * Like read but can return more than one row
         *
         * @return: An array of rows
         */
        {
            $query = $this->_prepread($table, $key, $fields);
            return $this->query($query);
        }

        function update($table, $fields, $key)
        /** 
         * This function updates specific fields of a table in the database 
         * according to the specified key.
         *
         * @param $table: A string indicating the table in the database.
         * @param $fields: An array (FieldName => Value).
         * @param $key: An array (FieldName => Value)  which will be matched
         *              to the row that will be updated.
         *
         * @return: A boolean indicating whether the update was ok.
         */
         {
             global $connection;
            if(is_string($table) && is_array($fields) && is_array($key))
            {
                $query = "UPDATE $table SET ";
                foreach($fields as $name => $val) 
                {
                    $v = ($val == "NOW()" ? $val : $this->quote($val));
                    (is_null($val) ? $v = "NULL" : null);
                    $query .= $name . "=" . $v . ",";
                }
                $query = substr($query, 0, -1) . " WHERE ";
                foreach($key as $name => $val)
                {
                    $query .= $name . "=" . $this->quote($val) . " AND ";
                }
                $query = substr($query, 0, -5);

                try {
                    $connection->beginTransaction();
                    $result1 = $connection->query($query);
                    $result2 = $connection->commit();

                    var_dump($result1);
                    var_dump($result2);

                    return true;
                } catch (Exception $e) {
                    print_r($e);
                    die('stop!');
                }

                return false;
            } else
            {
                return false;
            }
        }
        
        function man_query($query){
			mysql_query($query) or die(mysql_error()."(".$query.")");
		}

        function insert($table, $fields)
        /**
         * This functions inserts values into a table in the database.
         *
         * @param $table: A string indicating the table.
         * @param $fields: An array (FieldName => Value) to be inserted into
         *                 the table.
         *
         * @return: A boolean indicating whether the insertion was succesful.
         */
        {
            global $connection;
            if(is_string($table) && is_array($fields))
            {
                $names = "("; $vals = "(";
                foreach($fields as $name => $val)
                {
                    $names .= $name . ",";
                    $v = ($val == "NOW()" || substr($val, 0, 8) == "PASSWORD" ?
                        $val : $this->quote($val));
                    (is_null($val) ? $v = "NULL" : null);
                    $v .= ",";
                    $vals .= $v;
                }
                $names = substr($names, 0, -1) . ")";
                $vals = substr($vals, 0, -1) . ")";

                $query = "INSERT INTO $table $names VALUES $vals";

                return $connection->query($query);
            } else
            {
                return false;
            }
        }

        function quote($field)
        /**
         * This function escapes a string for insertion in the database
         *
         * @param $field: The field to insert into the database
         *
         * @return: The MySQL escaped string 
         */
        {
            return "'" . strip_tags($field) . "'";
        }
        
        function delete($table, $keys){
            global $connection;
			if(is_string($table) && is_array($keys)){
			 	$query = "";
			 	$sep = "";
				foreach($keys as $value => $waarde){
					$query .= $sep. $value ." = ".$this->quote($waarde);
					$sep = " AND ";
				}
				$query = "DELETE FROM $table WHERE ". $query;
				return $connection->query($query);
			}
		}

        function insertedID()
        /**
         * This function returns the last inserted id for this database
         * connection
         *
         * @return: last inserted id
         */
        {
            global $connection;
            return mysql_insert_id($connection);
        }
    }

    $db = new DB();
?>
