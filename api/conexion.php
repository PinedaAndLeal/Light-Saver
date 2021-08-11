<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

    class Conexion

    {

        private $server = "localhost"; //server 

        private $user = "root"; //user  

        private $password = ""; //database password

        private $db = "lightsaver"; //database name 

        private $connect;

        

        public function connect(){

        $this->connection=@mysqli_connect($this->server,$this->user,$this->password,$this->db);

        if($this->connection){

            //echo 'Conected';

        }else

        {

            echo 'Not conected';

        }

    }
	
		public function show()

    {

        mysqli_set_charset($this->connection, "utf8");

        $result = $this->connection->query("select * from saver");
		//$range['minValue'] = array("minValue:"=>"0","maxValue:"=>"50");

        $i=0;

        while($row = $result->fetch_assoc())

        {

            $data[$i] = $row; //'{'.'"id":'.$row['id'].
			//'"place":'.$row['place'].
			//'"update":'.$row['update'].
			//'}';
			//array_push($range['minValue']);

            $i++;

        }

        return json_encode($data);



    }
	
	public function showsensor()

    {

        mysqli_set_charset($this->connection, "utf8");

        $result = $this->connection->query("select * from sensor");
		//$range['minValue'] = array("minValue:"=>"0","maxValue:"=>"50");

        $i=0;

        while($row = $result->fetch_assoc())

        {

            $data[$i] = $row; //'{'.'"id":'.$row['id'].
			//'"place":'.$row['place'].
			//'"update":'.$row['update'].
			//'}';
			//array_push($range['minValue']);

            $i++;

        }

        return json_encode($data);



    }
	public function showlight()

    {

        mysqli_set_charset($this->connection, "utf8");

        $result = $this->connection->query("select * from light");
		//$range['minValue'] = array("minValue:"=>"0","maxValue:"=>"50");

        $i=0;

        while($row = $result->fetch_assoc())

        {

            $data[$i] = $row; //'{'.'"id":'.$row['id'].
			//'"place":'.$row['place'].
			//'"update":'.$row['update'].
			//'}';
			//array_push($range['minValue']);

            $i++;

        }

        return json_encode($data);



    }
	
	public function showsaver()

    {

        mysqli_set_charset($this->connection, "utf8");

        $result = $this->connection->query("select * from project");
		//$range['minValue'] = array("minValue:"=>"0","maxValue:"=>"50");

        $i=0;

        while($row = $result->fetch_assoc())

        {

            $data[$i] = $row; //'{'.'"id":'.$row['id'].
			//'"place":'.$row['place'].
			//'"update":'.$row['update'].
			//'}';
			//array_push($range['minValue']);

            $i++;

        }

        return json_encode($data);



    }
}
?>