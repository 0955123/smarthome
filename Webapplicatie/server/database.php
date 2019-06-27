<?php

class Database 
{
    private $db;
    private $dsn = 'mysql:dbname=smarthome;host=127.0.0.1;charset=utf8';
    private $user = 'root';
    private $password = '';
    const REQUEST_INCOMPLETE = 1;
    const REQUEST_INPUT_GELUKT = 2;
    const DB_NOT_ACCEPTABLE_DATA = 3;
    const INVALID_DATA_VALUE = 4;
    const REQUEST_INPUT_MISLUKT = 5;


    public function __construct(){
       $this->db = new \PDO($this->dsn, $this->user, $this->password);
       $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
    }

    public function updateMqttRow($topic, $message) {
        $topic = explode('/', $topic);

        if ($topic[0] == 'classrooms') {
            $data['classroom'] = filter_var($topic[1], FILTER_SANITIZE_STRING);
            $data['topic'] = filter_var($topic[2], FILTER_SANITIZE_STRING);
            $data['message'] = filter_var($message, FILTER_SANITIZE_STRING);

            $sql = "UPDATE mqtt_messages SET message = :message WHERE classroom = :classroom AND topic = :topic";
            $stmnt = $this->db->prepare($sql)->execute($data);

            if($stmnt){
                return self::REQUEST_INPUT_GELUKT;
            }
        }

        return self::DB_NOT_ACCEPTABLE_DATA; 
    }
}