<?php

class DB {

    protected $connection;
    protected $db_name = 'news';
    protected $db_user = 'root';
    protected $db_pass = '';
    protected $db_host = 'localhost';

    public function __construct() {
        $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        $this->query("SET NAMES UTF8");

        if( !$this->connection ) {
            throw new Exception('Could not connect to DB ');
        }
    }

    public function query($sql){
        if ( !$this->connection ){
            return false;
        }

        $result = $this->connection->query($sql);

        if ( mysqli_error($this->connection) ){
            throw new Exception(mysqli_error($this->connection));
        }

        if ( is_bool($result) ){
            return $result;
        }

        $data = array();
        while( $row = mysqli_fetch_assoc($result) ){
            $data[] = $row;
        }

        mysqli_free_result($result);

        return $data;
    }

    public function escape($str){
        return mysqli_escape_string($this->connection, $str);
    }

    public function preparation($str){
        $stmt = $this->connection->prepare($str);
        return $stmt;
    }

}
