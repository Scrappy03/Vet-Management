<?php
class User {
    protected $Conn;
    public function __construct($Conn){
$this->Conn = $Conn;
}

public function createUser($user_data){

    $sec_password = password_hash($user_data['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (email, password) VALUES
(:email, :password)";
$stmt = $this->Conn->prepare($query);

return $stmt->execute(array(
'email' => $user_data['email'],
'password' => $sec_password
));
}

}