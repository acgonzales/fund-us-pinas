<?php

    class Signup
    {

        private $error = "";

        public function evaluate($data)
        {

            foreach ($data as $key => $value) {
                $pass1;
                $pass2;
                if (empty($value)) 
                {
                    if($key == 'first_name')
                    {
                        $this->error = $this->error . "Please enter your first name <br>";
                    }
                    if($key == 'last_name')
                    {
                        $this->error = $this->error . "Please enter your last name <br>";
                    }
                    if($key == 'email')
                    {
                        $this->error = $this->error . "Please enter your email <br>";
                    }
                    if($key == 'password')
                    {
                        $this->error = $this->error . "Please fill up your password <br>";
                    }
                    if($key == 'gender')
                    {
                        $this->error = $this->error . "Please fill up your gender <br>";
                    }
                }
                
                if ($key == "email") 
                {
                    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$value)) {
                        $this->error = $this->error . " Invalid email address <br>";
                    }
                    
                }
                if ($key == "first_name") 
                {
                    if (is_numeric($value)) {
                        $this->error = $this->error . " First name cannot be a number <br>";
                    }
                    if (strstr($value," ")) {
                        $this->error = $this->error . " First name cannot have spaces <br>";
                    }
                }
                if ($key == "last_name") 
                {
                    if (is_numeric($value)) {
                        $this->error = $this->error . " Last name cannot be a number <br>";
                    }
                    if (strstr($value," ")) {
                        $this->error = $this->error . " Last name cannot have spaces <br>";
                    }
                    
                }
                
                if ($key == "password") 
                {
                    $pass1 = $value;
                    
                }
                elseif ($key == "password2") 
                {

                    $pass2 = $value;
                    if ($pass1 !== $pass2) {
                        $this->error = $this->error . " Password mismatch, please re-enter password <br>";
                    }
                    
                }
                

            }

            if ($this->error == "") 
            {
                //no errror
                $this->create_user($data);
            }
            else 
            {
                return $this->error;
            }
        }
        public function create_user($data)
        {
            $first_name = ucfirst ($data['first_name']);
            $last_name = ucfirst ($data['last_name']);
            $gender = $data['gender'];
            $email = $data['email'];
            $password = $data['password'];

            //create these
            $url_address = strtolower($first_name) . "." . strtolower($last_name); 
            $userid = $this->create_userid();

            $query = "insert into users (userid,first_name,last_name,gender,email,password,url_address) values ('$userid','$first_name','$last_name','$gender','$email','$password','$url_address')";
            
            
            $DB = new Database();
            $DB->save($query);
        }

    
        private function create_userid()
        {
            $length = rand(4,19);
            $number = "";
            for ($i=0; $i < $length; $i++) 
            { 
                $new_rand = rand(0,9);

                $number = $number . $new_rand;
            }

            return $number;
        }
    }


?>