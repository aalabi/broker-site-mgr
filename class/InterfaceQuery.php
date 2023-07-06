<?php

/**
 * InterfaceQuery
 *
 * An interface for work creating queries
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022
 * @link        alabiansolutions.com
 */

interface InterfaceQuery
{
    /**
     * select data from a table
     * @param array cols a single dimensional array whose elements are columns in table [col1, col2, ...coln]
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string table a table name in the database
     * @return array 2 dimensional array of selected rows or empty array if no match
    */
    public function select(array $cols = [], array $where = [], ?string $table = null):array;
    
    /**
     * insert data into a table
     * @param array cols 2 dimensional array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	isFunction if the value is an sql function
     * @param string table a table name in the database
     * @param int the id of the last inserted id
    */
    public function insert(array $cols, ?string $table = null):int;

    /**
     * update data in a table
     * @param array cols 2 dimensional array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function
     * @param array where 2 dimensional array [col1=>[operator, value/function, isValue/isFunction, join], ...coln=>[operator, isValue/isFunction, value/function]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string table a table name in the database
     * @param int the no of updated rows
    */
    public function update(array $cols, array $where, ?string $table = null):int;
    
    /**
     * delete data from a table
     * @param array cols 2 dimensional array [col1=>[value, isFunction/isValue], col2=>[value, isFunction/isValue], ...coln=>[value, isFunction/isValue]]]
     *	function if the value is an sql function, join must be a valid logic, join is not compulsory for the last item
     * @param string table a table name in the database
     * @param @param int the no of deleted rows
    */
    public function delete(array $where, ?string $table = null):int;

    /**
     * select data from a table based on the id
     * @param int rowId the id of the row to be deleted
     * @param string table a table name in the database
     * @return array an array of selected row or empty array if id is invalid
    */
    public function get(int $rowId, ?string $table = null):array;

    /**
     * select the first row from a table
     * @param string table a table name in the database
     * @return array an array of the first row or empty array if the table is empty
    */
    public function getFirst(?string $table = null):array;

    /**
     * select the latest row from a table
     * @param string table a table name in the database
     * @return array an array of the latest row or empty array if the table is empty
    */
    public function getLast(?string $table = null):array;
}
