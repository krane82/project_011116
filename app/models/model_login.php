<?php
class Model_Login extends Model {
  public function get_data() {
    $loginUser = array(
      "login_status" => 1
      );
    return $loginUser;
  }
  public function check_data($login, $password) {
      if(isset($_POST['rem_sys'])){
          $rem_the_sys = $_POST['rem_sys'];
      }
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($con->connect_errno) {
      printf("Connect failed: %s\n", $con->connect_error);
      exit();
    }
    $data = array();
    $login = mysqli_real_escape_string($con, $login);
    $password = mysqli_real_escape_string($con, $password);
    $password = md5($password);
    $query    = "SELECT * FROM `users` WHERE email='$login' and password='$password'";
    if( $result = $con->query($query) ) {
      $r  = $result->fetch_assoc();
      if($r){
        foreach($r as $k=>$v) {
          if($k !== 'password') {
            $data[$k] = $v;
          }
        }
        if($data["active"] == 0) {
          $con->close();
          return FALSE;
        }
//        setcookie("user_name", $data['full_name']);
//        setcookie("user_id", $data["id"]);
      }


        if(isset($rem_the_sys)){
            $ip = $_SERVER["REMOTE_ADDR"];
            $username = $data['full_name'];
            $salt = "DJHFY";
            $res_cookie = md5($salt.$username.$ip.$salt);
            setcookie("hash_sys", $res_cookie,time()+36000000);
            $hash_sys = $_COOKIE['hash_sys'];

            $sql = "UPDATE `users`";
            $sql .= "SET `rem_the_sys`='$hash_sys'";
            $sql .= " WHERE `email`='$login' AND `password`='$password'";
            $con = $this->db();
            $res = $con->query($sql);
        }else{
            $sql = "UPDATE `users`";
            $sql .= "SET `rem_the_sys`=''";
            $sql .= " WHERE `email`='$login' AND `password`='$password'";
            $con = $this->db();
            $res = $con->query($sql);
        }
      
    } else {
      $con->close();
      return FALSE;
    }

    $_SESSION["user_name"] = $data['full_name'];
    $_SESSION["user_id"] = (int)$data["id"];
    $con->close();
    return $data;
  }

    public function rem_in_sys()
    {
        if(isset($_COOKIE['hash_sys'])){
            $hash = $_COOKIE['hash_sys'];

            $sql = "SELECT * FROM `users`";
            $sql.= "WHERE `rem_the_sys`='$hash'";
            $con = $this->db();
            $res = $con->query($sql);
            if($res->num_rows>0){
                $r = $res->fetch_assoc();
                return $r;
            }else{
                return "error";
            }
        }

    }
}
