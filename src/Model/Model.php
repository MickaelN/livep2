<?php
namespace Model;
class Model{
    protected \PDO $pdo;

    public function __construct()
    {
        try{
        $this->pdo = new \PDO('mysql:host=localhost;dbname=livep2;charset=utf8','root');
        }catch(\Exception $error){
            die($error->getMessage);
        }
    }

}