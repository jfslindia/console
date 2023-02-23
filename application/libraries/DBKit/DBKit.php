<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 11/27/2018
 * Time: 9:15 AM
 */

/****
 *
 * A Custom MS SQL Database library for executing the stored procedures.
 *
 * */
class DBKit
{
    //Protected variable for Codeigniter object.
    protected $CI;
    protected $connection;
    protected $statement;

    function __construct()
    {
        //Setting up CI object in order to manipulate CI methods/features.
        $this->CI =& get_instance();
        //$this->CI->load->model('Web_Model');


    }


    /**
     *Public function for db connection
     */
    public function db_connect()
    {

        /*The generic db connection configuration.*/
        sqlsrv_configure('WarningsReturnAsErrors', 0);
        $serverName = DB_SERVER_IP; //serverName\instanceName
        $connectionInfo = array("Database" => LOCAL_DB, "UID" => "projectadmin", "PWD" => "admin@123");
        $this->connection = sqlsrv_connect($serverName, $connectionInfo);

    }


    /**
     * Public function for query execution
     * @param $query
     */
    public function execute($query)
    {
        $this->statement = sqlsrv_query($this->connection, $query);
    }


    /**
     * Public function for getting the current statement object
     * @return mixed
     */
    public function get_statement()
    {
        return $this->statement;
    }

    /**
     * Getting the result from a SQL statement.
     * @param $statement -- Built from an SQL query
     * @return array
     */
    public function get_result($statement){

        $result = array();
        do {
            while ($row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {

                array_push($result, $row);
            }
        } while ($ok = sqlsrv_next_result($statement));

        return $result;
    }


    /**
     *Public method for closing the current connection
     */
    public function close_connection()
    {
        sqlsrv_close($this->connection);
    }

}