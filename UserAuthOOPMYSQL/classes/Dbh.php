
<?php
class Dbh{
      //properties
  public $hostname = 'localhost'; 
  public $username = 'root';  
  public $password = ''; 
  public $dbname = 'zuriphp';                        

      //method
  protected function connect(){
    $hostname = 'localhost';
    $username = 'root';
    $password = '';  
    $dbname = 'zuriphp'; 
    $conn = mysqli_connect($hostname, $username, $password, $dbname);
    
    if(!$conn){
     echo "<script> alert('Error connecting to the database') </script>";
    }
    return $conn;
  }

}
