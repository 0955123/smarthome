<?php
namespace models;

class Model 
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

    public function __construct() {
        $this->db = new \PDO($this->dsn, $this->user, $this->password);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
    }
    
    private function startSession() {
        if(!isset($_SESSION)) {
            session_start();
        }
    }
    
    public function destroySession() {
        $this->startSession();

        $_SESSION = array();
        session_destroy();
        
        return true;
    }

    public function isLoggedIn() {
        $this->startSession();

        if(isset($_SESSION['user_token']) && !empty($_SESSION['user_token'])){
            $user_token = filter_var($_SESSION['user_token'], FILTER_SANITIZE_STRING);

            $sql = 'SELECT id, name FROM `users` WHERE `token` = :user_token LIMIT 1';
            $sth = $this->db->prepare($sql);
            $sth->bindParam(':user_token',$user_token);
            $sth->execute();
            
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            
            if(count($result) === 1 && isset($result[0]['id']) && !empty($result[0]['id'])) {
                return $result[0];
            }
        }

        return false;     
    }
    
    public function hasPost(){
        return !empty($_POST);
    }

    public function getClassrooms() {
        $sql = 'SELECT classroom FROM `classrooms` ';
        $stmnt = $this->db->prepare($sql);
        $stmnt->execute();
        $messages = $stmnt->fetchAll(\PDO::FETCH_ASSOC);
        return $messages;
    }
    
    public function getClassroomInfo($classroom) {
        $classroom = filter_var($classroom, FILTER_SANITIZE_STRING);

        $sql = 'SELECT topic, message FROM `mqtt_messages` WHERE classroom = ' . $classroom;

        $stmnt = $this->db->prepare($sql);
        $stmnt->execute();
        $info = $stmnt->fetchAll(\PDO::FETCH_ASSOC);
        return $info;
    }

    public function checkAuthentication() {
        if (isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password'])  && !empty($_POST['password'])){
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

            $sql = 'SELECT password, token FROM `users` WHERE `id` = :username LIMIT 1';
            $sth = $this->db->prepare($sql);
            $sth->bindParam(':username',$username);
            $sth->execute();
            
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            
            if(count($result) === 1) {
                $isPasswordCorrect = password_verify($_POST['password'], $result[0]['password']);

                if ($isPasswordCorrect) {
                    $this->startSession();

                    $_SESSION['user_token'] = $result[0]['token'];

                    return true;
                }
            }
            return false;
        }

        return false;
    }

    public function addUser() {
        if(empty($_POST['id']) || !isset($_POST['password'])){
            return self::REQUEST_INCOMPLETE;
        }

        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
            'id' => filter_var($_POST['id'], FILTER_SANITIZE_STRING),
            'password' => $password,
            'token' => $this->salt()
        ];

        $sql = "INSERT INTO users (id, password, name, token) VALUES (:id, :password, :name, :token)";
        $stmnt = $this->db->prepare($sql)->execute($data);

        if($stmnt){
            return self::REQUEST_INPUT_GELUKT;
        }
        return self::DB_NOT_ACCEPTABLE_DATA; 
    }

    public function sendMqttMessage($message, $classroom) {
        require("phpMQTT.php");

        $server = 'test.mosquitto.org:1883';
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = uniqid();
        $mqtt = new bluerhinos\phpMQTT($server, $port, $client_id);

        $originalMessage = $message;

        if ($message == 'hot') $message = 'on';
        else if ($message == 'cold') $message = 'off';

        //publish
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish('classrooms/' . $classroom . '/motorVerwarming', $message, 0, 1);
            $mqtt->publish('classrooms/' . $classroom . '/motorGordijn', $message, 0, 1);
            $mqtt->publish('classrooms/' . $classroom . '/status', $originalMessage, 0, 1);
            $mqtt->close();

            sleep(1);

            return true;
        } else {
            return false;
        }
    }

    function salt($length = 24) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}

