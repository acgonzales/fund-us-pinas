<?php
/*    $i = 0;
    $name = ["ryan", "pearl", "matt", "isip", "tep"];

    while ($i <= 100) {

        echo $i  ."<br>" ."<br>"; 
        $i++;
    }
    for ($i = 0; $i<=4; $i++){
        "<br>" ."<br>" ;
        echo $name[$i] ."<br>"."<br>";
    }
    
function sample($name, $name2, $name3){
    echo "my name is: " . $name ." " . $name2." " . $name3;
}

sample("ryan", "matt", "pearl");*/


    

    class Database
    {
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $db = "fundraise_db";

            function connect(){
                $connection = mysqli_connect($this->host,$this->username,$this->password,$this->db);
                return $connection;
            }
            function read($query){

                $conn = $this->connect();
                $result = mysqli_query($conn,$query);

                if(!$result){
                    return false;
                }else
                {
                    $data = false;
                    while ($row = mysqli_fetch_assoc($result)) 
                    {
                        $data[] = $row;
                    }
                    return $data;
                }
            }
            function save($query){
                $conn = $this->connect();
                $result = mysqli_query($conn,$query);
                
                if (!$result) {
                    return false;
                }
                else{
                    return true;
                }
                
            }
    }

?>