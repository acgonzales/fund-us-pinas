<?php

    class Fundraise
    {

        private $error = "";

        public function create_fundraise($userid,$data)
        {
            if (!empty($data['title']) && !empty($data['amountgoal']) && !empty($data['description'])) 
            {
                
                $title = addslashes($data['title']);
                $amountgoal = addslashes($data['amountgoal']);
                $description = addslashes($data['description']);

                $fundraiseid = $this->create_fundraiseid();

                $query = "insert into fundraise (userid,fundraiseid,title,amountgoal,description) value ('$userid','$fundraiseid','$title','$amountgoal','$description')";

                $DB = new Database();
                $DB->save($query);

            }
            else
            {
                $this->error .= "Please fill in all the requirements<br>";
            }

            return $this->error;
        }


        public function get_fundraise($id)
        {
            $query = "select * from fundraise order by id desc";

            $DB = new Database();
            $result = $DB->read($query);

            if($result)
            {
                return $result;

            }
            else
            {
                return false;
            }
        }

        private function create_fundraiseid()
        {
            $length = rand(4,10);
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