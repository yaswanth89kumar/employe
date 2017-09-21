<?php
include('database.php');
class enhanceClass extends dbConnect {
  private $query;
  private $db;
  private $database;
  private $appid;
  public function __construct() {
    $this->db = new dbConnect();
    $this->database = $this->db->connect();
    $this->appid = 'JAPCAP100025NTR';
  }
  
  public function getUsers(){
    $users_list = array();
    $error = array();
    if(isset($_POST)) {
      extract($_POST);
      if($this->checkappid($applicationid)){
        if(isset($username) && isset($password)) {
          $sql = "select * from users where username='".$username."' and password='".$password."'";
          $data = $this->database->query($sql);
          if ($data->num_rows > 0) {
            // output data of each row
            while($row = $data->fetch_assoc()) {
                array_push($users_list, array(
                'id'=> $row["id"],
                'username' => $row['username'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                ));
            }
          }
        }
        else {
          array_push($error, array('please send proper post values'));
        }
      }
      else {
        array_push($error, array('Who the hell are you?'));
      }
    }
    echo json_encode(array('userlist'=>$users_list, 'error'=>$error));
  }
  
  public function checkappid($applicationid) {
    if($applicationid != $this->appid){ 
      return FALSE;
    }
    return TRUE;
  }
}

$db = new enhanceClass();



?>
