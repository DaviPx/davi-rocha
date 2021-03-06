<?php

require_once ("model/comment.php");
require_once ("database/database.php");
require_once ("exception/requestException.php");

class TestController {
    
    private $allowedOperations = Array('info', 'register');
    private $request;
    
    public function __construct($request) {
        $this->request = $request;
    }
    
    public function routeOperation() {
        $body = json_decode($this->request->getBody(),true);
        switch($this->request->getOperation()) {
            case 'register':
                return $this->create($body);
            case 'info':
                if($this->request->getMethod() == "GET")
                    return $this->search($this->request->getQueryString());
            default:
                return (new RequestException(400, "Bad request"))->toJson();
        }
    }
    
    
    private function create($body) {
        try{
            
            new Comment($body["test"],$body["title"],$body["text"], $body["userName"]); // $test,$title,$text,$userName
            
            return (new DBHandler())->insert($body, 'comments');
        }catch(RequestException $exception) {
            return $exception->toJson();
        }
    }
    
    private function search($queryString) {
        $options = ['mais recente','mais antigo','mais pontos'];
        return (new DBHandler())->search($queryString,$comments,$options);
    }
    
}

