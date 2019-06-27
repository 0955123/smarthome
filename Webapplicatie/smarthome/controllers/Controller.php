<?php
namespace controllers;

use models as MODELS;
use view as VIEW;

class Controller  
{
    private $action;
    private $control;
    private $view;
    private $model;
    
    public function __construct($control,$action, $message=NULL) {
        $this->control = $control;
        $this->action = $action;

        $this->view = new VIEW\View();
        if(!empty($message)) {
            $this->view->set('boodschap',$message);
        }
        $this->model = new MODELS\Model();       
    }
    
    public function execute() {
        $opdracht = $this->action;
        if(!method_exists($this,$opdracht)) {
            $opdracht = 'defaultAction';
            $this->action = 'defaultAction';
        }
        $this->$opdracht();
        $this->view->setAction($this->action);
        $this->view->setControl($this->control);
        $this->view->toon();
    }
    
    private function defaultAction() {
        if ($this->model->hasPost()) {
            if ($this->model->checkAuthentication()) {
                header('Location: http://localhost:8080/smarthome/');
            }
        }

        if ($user = $this->model->isLoggedIn()) {
            $this->view->set('user', $user);
        }

        $classrooms = $this->model->getClassrooms();

        foreach ($classrooms as $key => $classroom) {
            $info = $this->model->getClassroomInfo($classroom['classroom']);            

            foreach ($info as $value) {
                $classrooms[$key][$value['topic']] = $value['message'];
            }
        }

        $this->view->set('classrooms', $classrooms);
    }

    private function uitloggen() {
        if ($this->model->destroySession()) {
            header('Location: http://localhost:8080/smarthome/');
        }
    }


    public function turnStateHot() {
        $classroom = filter_var($_GET['classroom'], FILTER_SANITIZE_STRING);

        if ($this->model->sendMqttMessage('hot', $classroom)) {
            header('Location: http://localhost:8080/smarthome/');
        }
    }

    public function turnStateCold() {
        $classroom = filter_var($_GET['classroom'], FILTER_SANITIZE_STRING);

        if ($this->model->sendMqttMessage('cold', $classroom)) {
            header('Location: http://localhost:8080/smarthome/');
        }
    }

    private function addUser() {
        $user = $this->model->isLoggedIn();

        if (isset($user['id']) && $user['id'] == 'admin') {
            $this->view->set('user', $user);
        } else {
            header('Location: http://localhost:8080/smarthome/');
        }

        if ($this->model->hasPost()) {
            $result = $this->model->addUser();
            
            switch($result) {
                case MODELS\Model::REQUEST_INPUT_GELUKT:
                    header('Location: http://localhost:8080/smarthome/');
                    break;  
                case MODELS\Model::REQUEST_INCOMPLETE:
                    $this->view->set('boodschap', 'Niet alle velden zijn ingevuld.');  
                    $this->view->set('form_data', $_POST);
                    break;
                case MODELS\Model::DB_NOT_ACCEPTABLE_DATA:
                    $this->view->set('form_data', $_POST);
                    break;
            }  
        }
    }

    public function updateUser() {        
        $user = $this->model->getUser();
        $this->view->set('user', $user);

        if (!$this->model->isPostLeeg()) {
            $result = $this->model->updateUser();

            switch($result) {
                case MODELS\Model::REQUEST_INCOMPLETE:
                    $this->view->set("boodschap", "Tijd is niet gewijzigd. Niet alle vereiste data ingevuld.");  
                    break;
                case MODELS\Model::INVALID_DATA_VALUE:
                    $this->view->set("boodschap", "Tijd is niet gewijzigd. Er is foutieve data ingestuurd.");  
                    break;
                case MODELS\Model::REQUEST_INPUT_GELUKT:
                    header('Location: http://localhost:8080/knuffelbeer/');
                    break;  
            }
        }
    }
}
