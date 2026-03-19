<?php
class Database
{
  private $servername = "localhost";
  private $username = "root";
  private $password = "";
  private $dbname = "du_an_9";
  private $conn;

  function __construct()
  {
    try {
      $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  private function normalizeParams($args) {
      if (count($args) === 1 && is_array($args[0])) {
          return $args[0];
      }
      return $args;
  }

  function query($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $params = $this->normalizeParams($args); 
      $stmt->execute($params);
      
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "Error SQL: " . $e->getMessage();
      return [];
    }
  }

  function queryOne($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $params = $this->normalizeParams($args);
      $stmt->execute($params);
      
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  function insert($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $params = $this->normalizeParams($args);
      $stmt->execute($params);
      
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  function update($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $params = $this->normalizeParams($args);
      return $stmt->execute($params);
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  function delete($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $params = $this->normalizeParams($args);
      return $stmt->execute($params);
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }
public function lastInsertId() {
      return $this->conn->lastInsertId();
  }
  function __destruct()
  {
    $this->conn = null;
  }
}