<?php

namespace Model;

use Exception;

class User extends Model
{

    private int $id;
    private string $mail;
    private string $password;
    private string $tokenregister;
    private string $creationdate;
    protected string $table = '`users`';

    public function saveUser(): bool
    {
        $query = 'INSERT INTO ' . $this->table . ' (`mail`,`password`,`tokenregister`) '
            . 'VALUES (:mail,:password,:tokenregister)';
        $queryStatement = $this->pdo->prepare($query);
        $queryStatement->bindValue(':mail', $this->mail, \PDO::PARAM_STR);
        $queryStatement->bindValue(':password', $this->password, \PDO::PARAM_STR);
        $queryStatement->bindValue(':tokenregister', $this->tokenregister, \PDO::PARAM_STR);
        return $queryStatement->execute();
    }

    public function checkTokenRegister(): bool
    {
        $query = 'SELECT COUNT(`id`) AS `number` FROM ' . $this->table
        . ' WHERE `tokenregister` = :tokenregister';
        $queryStatement = $this->pdo->prepare($query);
        $queryStatement->bindValue(':tokenregister', $this->tokenregister, \PDO::PARAM_STR);
        $queryStatement->execute();
        $response = $queryStatement->fetchColumn();
        if($response === 0){
            return false;
        }else if(!$response){
            throw new Exception('La bdd a pas voulu !!!!');
        }
        return true;
    }

    public function deleteToken():bool{
        $query= 'UPDATE '. $this->table
        .' SET `tokenregister` = null'
        .' WHERE `tokenregister` = :tokenregister';
        $queryStatement = $this->pdo->prepare($query);
        $queryStatement->bindValue(':tokenregister', $this->tokenregister, \PDO::PARAM_STR);
       return $queryStatement->execute();
    }

    public function setMail(string $value): void
    {
        $this->mail = $value;
    }

    public function setPassword(string $value): void
    {
        $this->password = $value;
    }

    public function setTokenregister(string $value): void
    {
        $this->tokenregister = $value;
    }

    public function getTokenregister(): string
    {
        return $this->tokenregister;
    }

    public function getMail(){
        return $this->mail;
    }
}
