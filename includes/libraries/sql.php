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
    $exec = TRUE;
    if(isset($_POST)) {
      extract($_POST);
      if($this->checkappid($applicationid)){
        if(isset($username) && isset($password)) {
          if($username == "") {
            $exec = FALSE;
            array_push($error, array('please fill username'));
          }
          if($password == "") {
            $exec = FALSE;
            array_push($error, array('please fill password'));
          }
          if($exec) {
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
            else {
              array_push($error, array('Authentication failed!!!!'));
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
  
  public function getUserdetails() {
    $userdetails = array();
    if(isset($_POST)) {
      extract($_POST);
      if($this->checkappid($applicationid)){
        $sql = "select * from users where id=".$userid;
            $data = $this->database->query($sql);
            if ($data->num_rows > 0) {
              $editstatus = 1;
              $pastcurrentdate = strtotime(date('Y-m-d H:i:s', strtotime('-60 days', strtotime(date("Y-m-d H:i:s")))));
              // output data of each row
              while($row = $data->fetch_assoc()) {
                  $profileupdated_date = strtotime($row['datetime']);
                  if($pastcurrentdate < $profileupdated_date) {
                    $editstatus = 0;
                  }
                  array_push($userdetails, array(
                  'id'=> $row["id"],
                  'username' => $row['username'],
                  'firstname' => $row['firstname'],
                  'lastname' => $row['lastname'],
                  'email' => $row['email'],
                  'editstatus' => $editstatus,
                  'lastupdated' => $row['datetime'],
                  ));
              }
            }
      }
    }
    return $userdetails;
  }
  
  public function getAllusers() {
    $userdetails = array();
    $sql = "select * from users";
    $data = $this->database->query($sql);
    if ($data->num_rows > 0) {
      // output data of each row
      while($row = $data->fetch_assoc()) {
        array_push($userdetails, array(
          'id'=> $row["id"],
          'username' => $row['username'],
          'firstname' => $row['firstname'],
          'lastname' => $row['lastname'],
          'email' => $row['email'],
        ));
      }
    }
    return $userdetails;
  }
  
  public function register() {
    $result = 0;
    $error = array();
    $exec = TRUE;
    if(isset($_POST)) {
      extract($_POST);
      if($this->checkappid($applicationid)){
        if($username == "") {
          $exec = FALSE;
          array_push($error, array('please fill username'));
        }
        if($password == "") {
          $exec = FALSE;
          array_push($error, array('please fill password'));
        }
        if($firstname == "") {
          $exec = FALSE;
          array_push($error, array('please fill firstname'));
        }
        if($lastname == "") {
          $exec = FALSE;
          array_push($error, array('please fill lastname'));
        }
        if($email == "") {
          $exec = FALSE;
          array_push($error, array('please fill emailid'));
        }
        if($exec) {
          $sql = "select * from users where username='".$username."' or email='".$email."'";
          $data = $this->database->query($sql);
          if ($data->num_rows > 0) {
            $exec = FALSE;
            array_push($error, array('User already exist!'));
          }
          else {
            $sql = "insert into users(username, firstname, lastname, email, password) values ('".$username."', '".$firstname."', '".$lastname."', '".$email."', '".$password."')";
            $this->database->query($sql);
            $result = 1;
          }
        }
      }
      else {
        array_push($error, array('Who the hell are you?'));
      }
    }
    echo json_encode(array('status'=>$result, 'error'=>$error));
  }
  
  public function updateProfile() {
    $result = 0;
    $error = array();
    $success = array();
    $exec = TRUE;
    if(isset($_POST)) {
      extract($_POST);
      if($this->checkappid($applicationid)){
        if($username == "") {
          $exec = FALSE;
          array_push($error, array('please fill username'));
        }
        if($password == $currentpassword) {
          $exec = FALSE;
          array_push($error, array('please enter different password from current password'));
        }
        if($currentpassword == "") {
          $exec = FALSE;
          array_push($error, array('please fill Current password'));
        }
        if($firstname == "") {
          $exec = FALSE;
          array_push($error, array('please fill firstname'));
        }
        if($lastname == "") {
          $exec = FALSE;
          array_push($error, array('please fill lastname'));
        }
        if($email == "") {
          $exec = FALSE;
          array_push($error, array('please fill emailid'));
        }
        if($exec) {
          $sql = "select * from users where password='".$currentpassword."' and email='".$email."'";
          $data = $this->database->query($sql);
          if ($data->num_rows == 0) {
            $exec = FALSE;
            array_push($error, array('Please enter valid current password!!!'));
          }
          else {
            $attach = "";
            if(!empty($password)) {
              $attach = ", password='".$password."'";
            }
            
            $sql = "update users set username='".$username."', firstname='".$firstname."', lastname='".$lastname."', email='".$email."'".$attach." where email='".$email."'";
            $this->database->query($sql);
            $result = 1;
            array_push($success, array('Your details got updated successfully!!'));
          }
        }
      }
      else {
        array_push($error, array('Who the hell are you?'));
      }
    }
    echo json_encode(array('status'=>$result, 'error'=>$error, 'success'=>$success));
  }
  
  public function getProjects() {
    $error = array();
    $success = array();
    $projectdetails = array();
    $exec = TRUE;
    if(isset($_POST)) {
      extract($_POST);
      if(!empty($empid)) {
        if($this->checkappid($applicationid)){
          $sql = "select * from projects where empid='".$empid."'";
          $data = $this->database->query($sql);
          if ($data->num_rows > 0) {
            while($row = $data->fetch_assoc()) {
              array_push($projectdetails, array(
                'project' => $row['projectname'],
                'designation' => $row['designation'],
                'description' => $row['description'],
              ));
            }
          }
        }
      }
    }
    echo json_encode(array('result'=>$projectdetails, 'error'=>$error, 'success'=>$success));
  }
  
  public function getAchievements() {
    $error = array();
    $success = array();
    $projectdetails = array();
    $exec = TRUE;
    if(isset($_POST)) {
      extract($_POST);
      if(!empty($empid)) {
        if($this->checkappid($applicationid)){
          $sql = "select * from achievements where empid='".$empid."'";
          $data = $this->database->query($sql);
          if ($data->num_rows > 0) {
            while($row = $data->fetch_assoc()) {
              array_push($projectdetails, array(
                'subject' => $row['subject'],
                'description' => $row['description'],
              ));
            }
          }
        }
      }
    }
    echo json_encode(array('result'=>$projectdetails, 'error'=>$error, 'success'=>$success));
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
