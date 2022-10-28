<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh{
    public $db;         

    private function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        $check_email = $this->checkEmailExist($email);
        if ($check_email) {
            echo "<script>alert('Opps!! Email already exists');
            window.location = './forms/register.php'; </script>"; 
        }
        if($this->confirmPasswordMatch($password, $confirmPassword)){
            $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
            if($conn->query($sql)){
                echo "<script>alert('Ok!! User successfully registered');
            window.location = './forms/register.php'; </script>"; 
            } else {
                echo "<script>alert('Opps!! Password not matched');
                window.location = './forms/register.php'; </script>"; 
            }
        }   
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $_SESSION['email'] = $email;
            header("Location: ./dashboard.php");
        } else {
            header("Location: forms/login.php");
        }
    }

    public function getUser($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th> 
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='./action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['id'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> 
                    </td>".
                    
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

    public function deleteUser($id){
        $conn = $this->db->connect();
        $get_students_by_id = "SELECT * FROM Students WHERE id = '$id'";
        $run_get_students_by_id = $conn->query($get_students_by_id);
        if($run_get_students_by_id->num_rows > 0 ){
                $delete_student = "DELETE FROM Students WHERE id = '$id'";
                    if($conn->query($delete_student) == TRUE)
                        {
                            echo "<script>alert('User deleted successfully');
                            window.location = './dashboard.php'; </script>";  
                        } 
                    else 
                        {
                            echo "<script>alert('User not deleted');
                            window.location = './dashboard.php''; </script>";      
                        }
        }
    }

    public function updateUser($email, $password){
        $conn = $this->db->connect();
        $check_email = $this->checkEmailExist($email);
        if ($check_email) {
            $sql = "UPDATE Students SET password = '$password' WHERE email = '$email'";
            if($conn->query($sql) === TRUE){
                echo "<script>alert('Password updated successfully');
                window.location = 'forms/login.php'; </script>"; 
            } 
            }
        else {
            echo "<script>alert('User does not exist!');
            window.location = 'forms/resetpassword.php'; </script>";
        }
    }

    public function getUserByUsername($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function logout($email){
        session_start();
        session_destroy();
        header('Location: index.php');
    }

    public function confirmPasswordMatch($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            echo "<script>alert('Opps!! password not matched');
                window.location = './forms/register.php'; </script>";
        }
    }

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $this->email = $email;
        $sql = "SELECT * FROM Students WHERE email='$this->email'";
        $result = $this->db->connect()->query($sql);
        if($result->num_rows > 0){
            return TRUE;
         }
        else {
            return FALSE;
        }
    }
}