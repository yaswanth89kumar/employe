<?php

class dbConnect {
  private $servername;
  private $username;
  private $password;
  private $dbname;
  private $conn;
  private $error;
  public function __construct() {
    $this->servername = "localhost";
    $this->username = "root";
    $this->password = "";
    $this->dbname = "employee";
  }
  public function connect() {
    $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    if ($this->conn->connect_error) {
      return FALSE;
    }
    else {
       return $this->conn;
    }
  }
}



?>
