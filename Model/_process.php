<?php
require_once("_config.php");

//*********************************//
//     Author - Nazrul             //
//     Year: 2017                  //
//*********************************//


class Process
{
	function InitDB($host,$uname,$pwd,$database){
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd      = $pwd;
        $this->database = $database;		
    }

    //NAZURURU
    function getOrganizerKulliyyah($organizer_id,$status_id){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()){
            echo "Connection getOrganizerKulliyyah Failed: " . mysqli_connect_errno();
            exit();
        }

        $service_id = 9;

        if($stmt = $mysqli -> prepare("SELECT e.id, e.organizer_id, u.fullname, d.address, d.postcode, d.city, d.area_id, r.area, s.state, d.latitude, d.longitude, u.contact_no, u.profile_img, p.period, p.start, p.end, e.area_id, a.area, z.state, e.rate, e.attendance, e.topic, e.note, e.post_id, o.post, e.created, x.expert_id, t.title, y.fullname, y.profile_img, x.start, x.end FROM event_kulliyyah e INNER JOIN user_organization u ON u.login_id = e.organizer_id INNER JOIN address d ON d.id = u.address_id INNER JOIN area r ON r.id = d.area_id INNER JOIN state s ON s.id = r.state_id INNER JOIN period p ON p.id = e.period_id INNER JOIN area a ON a.id = e.area_id INNER JOIN state z ON z.id = a.state_id INNER JOIN post o ON o.id = e.post_id INNER JOIN expert_event x ON x.event_id = e.id INNER JOIN user_expert y ON y.login_id = x.expert_id INNER JOIN title t ON t.id = y.title_id WHERE x.service_id = ? AND x.status_id = ? AND e.organizer_id =? ORDER BY x.start")){

            $stmt -> bind_param("iii",$service_id,$status_id,$organizer_id);
            // $stmt -> bind_param("iiiss",$area,$service_id,$status_id,$event_datetime_start,$event_datetime_end);

            $stmt -> execute();

            $result = array();
    
            $stmt->bind_result($event_id,$organizer_id,$organizer_name,$address,$postcode,$city,$area_id,$area,$state,$latitude,$longitude,$phone,$organizer_img,$period,$starttime,$endtime,$event_area_id,$event_area,$event_state,$rate,$attendance,$topic,$note,$post_id,$post,$created,$expert_id,$title,$expert_name,$expert_img,$start,$end);
            $count = 0;
            while($stmt->fetch()){

                $result[$count]["title"] = $topic;
                $result[$count]["kulliyyah"] = "kuliah";
                $result[$count]["allday"] = "false";
                $result[$count]["borderColor"] = "#fff";
                $result[$count]["color"] = "#04dbac";
                $result[$count]["textColor"] = "#fff";
                $result[$count]["speakerID"] = $expert_id;
                $result[$count]["speaker"] = $title." ".$expert_name;
                $result[$count]["starttime"] = $starttime;
                $result[$count]["endtime"] = $endtime;
                $result[$count]["period"] = $period;
                $result[$count]["url"] = "javascript:;";
                $result[$count]["link"] = "personal.php";
                $result[$count]["start"] = $start;
                $result[$count]["end"] = $end;
                $result[$count]["username"] = "";
                $result[$count]["address"] = "";
                $result[$count]["attendance"] = $attendance;
                $result[$count]["bookedby"] = $organizer_name;
                $result[$count]["phonenumber"] = $phone;

                if(isset($note)){
                    $result[$count]["note"] = "Tiada";
                }else{
                    $result[$count]["note"] = $note;
                }

                $result[$count]["jobtype"] = $post_id;
                $result[$count]["lat"] = $latitude;
                $result[$count]["long"] = $longitude;
                $result[$count]["event_id"] = $event_id;
                $result[$count]["organizerLoginID"] = $organizer_id;
                // $result[$count]["areaID"] = $area_id;              
                $result[$count]["service_id"] = $service_id;
                $result[$count]["rate"] = $rate;                
                $result[$count]["topic"] = $topic;
                $result[$count]["note"] = $note;
                $result[$count]["postID"] = $post_id;
                $result[$count]["created"] = $created;
                $result[$count]["post"] = $post;
                $result[$count]["expertLoginID"] = $expert_id;
                $result[$count]["expertName"] = $title." ".$expert_name;
                $result[$count]["organizerName"] = $organizer_name;

                $count++;               
            }

            $stmt -> close();
        }else{
            echo "Prepared getOrganizerKulliyyah Statement Error: %s\n". $mysqli->error;
        }

        $mysqli -> close();

        // echo "db: ";
        // var_dump($result);

        return $result;
    }


    function getExpertKulliyyah($expert_id,$status_id){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()){
            echo "Connection getExpertKulliyyah Failed: " . mysqli_connect_errno();
            exit();
        }

        $service_id = 9;

        // if($stmt = $mysqli -> prepare("SELECT e.id, e.organizer_id, u.fullname, d.address, d.postcode, d.city, s.state, d.phone, d.latitude, d.longitude, u.profile_img, p.period, p.start, p.end, a.area, z.state, e.rate, e.attendance, e.topic, e.note, e.post_id, o.post, e.created, x.expert_id, t.title, y.fullname, y.profile_img, x.start, x.end  FROM event_kulliyyah e INNER JOIN user_organization u ON u.login_id = e.organizer_id INNER JOIN address d ON d.id = u.address_id INNER JOIN state s ON s.id = d.state_id INNER JOIN period p ON p.id = e.period_id INNER JOIN area a ON a.id = e.area_id INNER JOIN state z ON z.id = a.state_id INNER JOIN post o ON o.id = e.post_id INNER JOIN expert_event x ON x.event_id = e.id INNER JOIN user_expert y ON y.login_id = x.expert_id INNER JOIN title t ON t.id = y.title_id WHERE x.service_id = ? AND x.status_id = ? AND x.expert_id =? ORDER BY x.start")){

        if($stmt = $mysqli -> prepare("SELECT e.id, e.organizer_id, u.fullname, d.address, d.postcode, d.city, d.area_id, r.area, s.state, d.latitude, d.longitude,u.contact_no, u.profile_img, p.period, p.start, p.end, e.area_id, a.area, z.state, e.rate, e.attendance, e.topic, e.note, e.post_id, o.post, e.created, x.expert_id, t.title, y.fullname, y.profile_img, x.start, x.end FROM event_kulliyyah e INNER JOIN user_organization u ON u.login_id = e.organizer_id INNER JOIN address d ON d.id = u.address_id INNER JOIN area r ON r.id = d.area_id INNER JOIN state s ON s.id = r.state_id INNER JOIN period p ON p.id = e.period_id INNER JOIN area a ON a.id = e.area_id INNER JOIN state z ON z.id = a.state_id INNER JOIN post o ON o.id = e.post_id INNER JOIN expert_event x ON x.event_id = e.id INNER JOIN user_expert y ON y.login_id = x.expert_id INNER JOIN title t ON t.id = y.title_id WHERE x.service_id = ? AND x.status_id = ? AND x.expert_id =? ORDER BY x.start")){            

            $stmt -> bind_param("iii",$service_id,$status_id,$expert_id);
            // $stmt -> bind_param("iiiss",$area,$service_id,$status_id,$event_datetime_start,$event_datetime_end);

            $stmt -> execute();

            $result = array();
        
            $stmt->bind_result($event_id,$organizer_id,$organizer_name,$address,$postcode,$city,$area_id,$area,$state,$latitude,$longitude,$phone,$organizer_img,$period,$starttime,$endtime,$event_area_id,$event_area,$event_state,$rate,$attendance,$topic,$note,$post_id,$post,$created,$expert_id,$title,$expert_name,$expert_img,$start,$end);
            
            // $stmt->bind_result($event_id,$organizer_id,$organizer_name,$address,$postcode,$city,$state,$phone,$latitude,$longitude,$organizer_img,$period,$starttime,$endtime,$area,$state_area,$rate,$attendance,$topic,$note,$post_id,$post,$created,$expert_id,$title,$expert_name,$expert_img,$start,$end);
            $count = 0;
            while($stmt->fetch()){

                $result[$count]["title"] = $topic;
                $result[$count]["kulliyyah"] = "kuliah";
                $result[$count]["allday"] = "false";
                $result[$count]["borderColor"] = "#fff";
                $result[$count]["color"] = "#04dbac";
                $result[$count]["textColor"] = "#fff";
                $result[$count]["speakerID"] = $expert_id;
                $result[$count]["speaker"] = $title." ".$expert_name;
                $result[$count]["starttime"] = $starttime;
                $result[$count]["endtime"] = $endtime;
                $result[$count]["period"] = $period;
                $result[$count]["url"] = "javascript:;";
                $result[$count]["link"] = "personal.php";
                $result[$count]["start"] = $start;
                $result[$count]["end"] = $end;
                $result[$count]["username"] = "";
                $result[$count]["address"] = $address.", ".$postcode." ".$state;
                $result[$count]["attendance"] = $attendance;
                $result[$count]["bookedby"] = $organizer_name;
                $result[$count]["phonenumber"] = $phone;

                if(isset($note)){
                    $result[$count]["note"] = "Tiada";
                }else{
                    $result[$count]["note"] = $note;
                }

                $result[$count]["jobtype"] = $post_id;
                $result[$count]["lat"] = $latitude;
                $result[$count]["long"] = $longitude;
                $result[$count]["event_id"] = $event_id;
                $result[$count]["organizerLoginID"] = $organizer_id;
                // $result[$count]["areaID"] = $area_id;              
                $result[$count]["service_id"] = $service_id;
                $result[$count]["rate"] = $rate;                
                $result[$count]["topic"] = $topic;
                $result[$count]["note"] = $note;
                $result[$count]["postID"] = $post_id;
                $result[$count]["created"] = $created;
                $result[$count]["post"] = $post;
                $result[$count]["expertLoginID"] = $expert_id;
                $result[$count]["expertName"] = $title." ".$expert_name;
                $result[$count]["organizerName"] = $organizer_name;

                $count++;               
            }

            $stmt -> close();
        }else{
            echo "Prepared getExpertKulliyyah Statement Error: %s\n". $mysqli->error;
        }

        $mysqli -> close();

        // echo "db: ";
        // var_dump($result);

        return $result;
    }


    function checkUserRecord($email){
        // echo "here";

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
          echo "Connection checkUserRecord Failed: " . mysqli_connect_errno();
          exit();
        }

        if($stmt = $mysqli -> prepare("SELECT * FROM user_login WHERE login_name = ?")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("s", $email);
            /* Execute it */
            $stmt -> execute();

            /* store result */
            $stmt->store_result();

            $result = $stmt->num_rows;

            $stmt -> close();

        }else{
            /* Error */
            echo "Prepared checkUserRecord Statement Error: %s\n". $mysqli->error;
            return false;
        }


        /* Close connection */
        $mysqli -> close();
        return $result;
    }

    function insertExpert($oauth_provider,$oauth_uid,$level,$email,$password,$title,$fullname,$nric,$education,$contactno,$tauliah,$bank,$account,$area,$img_link,$facebook,$youtube,$instagram,$other,$services=array(),$rate=array()){

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection insertExpert Failed: " . mysqli_connect_errno();
            exit();
        }

        $status = 0;
        $current = date("Y-m-d H:i:s");


        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("INSERT INTO user_login (login_name,password,oauth_provider,oauth_uid,user_type_id,status,created,modified) VALUES (?,?,?,?,?,?,?,?)")) {
            $stmt -> bind_param("ssssiiss",$email,$password,$oauth_provider,$oauth_uid,$level,$status,$current,$current);
            /* Execute it */
            $stmt -> execute();
            /* Take new id */
            $login_id = $mysqli->insert_id;
            /* Close statement */
            $stmt -> close();

            if($stmt = $mysqli -> prepare("INSERT INTO summary (created,modified) VALUES (?,?)")) {
                // echo "inside: "+$note_id+" "+$current;
                $stmt -> bind_param("ss",$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                $summary_id = $mysqli->insert_id;
                /* Close statement */
                $stmt -> close();   

            }else{
                /* Error */
                echo "Prepared insertExpert SUMMARY Statement Error: %s\n". $mysqli->error;
                return false;
            }


            if($stmt = $mysqli -> prepare("INSERT INTO links (facebook,youtube,instagram,other,created,modified) VALUES (?,?,?,?,?,?)")) {
                // echo "inside: "+$note_id+" "+$current;
                $stmt -> bind_param("ssssss",$facebook,$youtube,$instagram,$other,$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                $link_id = $mysqli->insert_id;

                /* Close statement */
                $stmt -> close();   

            }else{
                /* Error */
                echo "Prepared insertExpert LINKS Statement Error: %s\n". $mysqli->error;
                return false;
            }  

            /* Create a prepared statement */
            // if($stmt = $mysqli -> prepare("INSERT INTO address (address,postcode,state_id,phone,created,modified_id) VALUES (?,?,?,?,?,?)")) {
            if($stmt = $mysqli -> prepare("INSERT INTO user_expert (login_id,title_id,fullname,nric,education,contact_no,tauliah_no,bank_id,account_no,area_id,profile_img,summary_id,link_id,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
                $stmt -> bind_param("iisssssisisiiss",$login_id,$title,$fullname,$nric,$education,$contactno,$tauliah,$bank,$account,$area,$img_link,$summary_id,$link_id,$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                // $address_id = $mysqli->insert_id;
                /* Close statement */
                $stmt -> close();


                $loop = 0;
                $temp = 1;


                foreach($services as $service) {
                    // echo " ** Expert: ".$login_id;
                    // echo " ** Service: ".$service;
                    // echo " ** Session: ".$temp;
                    // echo " ** Rate: ".$rate[$loop];
                    // echo " ** Created: ".$current;

                    if($stmt = $mysqli -> prepare("INSERT INTO expert_service (expert_id,service_id,session_id,min_rate,created,modified) VALUES (?,?,?,?,?,?)")) {
                        /* Bind parameters
                         s - string, b - blob, i - int, etc */
                        $stmt -> bind_param("iiiiss",$login_id,$service,$temp,$rate[$loop],$current,$current);
                        /* Execute it */
                        $stmt -> execute();
                        /* Close statement */
                        $stmt -> close();
                    }else{
                        /* Error */
                        echo "Prepared insertExpert SERVICES Statement Error: %s\n". $mysqli->error;
                        return false;
                    }

                    $loop++;
                }

            }else{
                /* Error */
                echo "Prepared insertExpert USER EXPERT Statement Error: %s\n". $mysqli->error;
                return false;
            }

        }else{
            /* Error */
            echo "Prepared insertExpert USER LOGIN Statement Error: %s\n". $mysqli->error;
            return false;
        }       

        /* Close connection */
        $mysqli -> close();
        return true;
    }


    function insertOrganization($oauth_provider,$oauth_uid,$level,$email,$password,$fullname,$contactno,$address,$address1,$address2,$postcode,$city,$area,$state,$latitude,$longitude,$img_link,$facebook,$youtube,$instagram,$other){

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection insertOrganization Failed: " . mysqli_connect_errno();
            exit();
        }

        $status = 0;
        $current = date("Y-m-d H:i:s");

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("INSERT INTO user_login (login_name,password,oauth_provider,oauth_uid,user_type_id,status,created,modified) VALUES (?,?,?,?,?,?,?,?)")) {
            $stmt -> bind_param("ssssiiss",$email,$password,$oauth_provider,$oauth_uid,$level,$status,$current,$current);
            /* Execute it */
            $stmt -> execute();
            /* Take new id */
            $login_id = $mysqli->insert_id;
            /* Close statement */
            $stmt -> close();

            if($stmt = $mysqli -> prepare("INSERT INTO summary (created,modified) VALUES (?,?)")) {
                // echo "inside: "+$note_id+" "+$current;
                $stmt -> bind_param("ss",$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                $summary_id = $mysqli->insert_id;
                /* Close statement */
                $stmt -> close();   

            }else{
                /* Error */
                echo "Prepared insertOrganization SUMMARY Statement Error: %s\n". $mysqli->error;
                return false;
            }


            if($stmt = $mysqli -> prepare("INSERT INTO links (facebook,youtube,instagram,other,created,modified) VALUES (?,?,?,?,?,?)")) {
                // echo "inside: "+$note_id+" "+$current;
                $stmt -> bind_param("ssssss",$facebook,$youtube,$instagram,$other,$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                $link_id = $mysqli->insert_id;

                /* Close statement */
                $stmt -> close();   

            }else{
                /* Error */
                echo "Prepared insertOrganization LINKS Statement Error: %s\n". $mysqli->error;
                return false;
            }

            if($stmt = $mysqli -> prepare("INSERT INTO address (address,address_line1,address_line2,postcode,city,area_id,latitude,longitude,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?)")) {
                $stmt -> bind_param("sssisissss",$address,$address1,$address2,$postcode,$city,$area,$latitude,$longitude,$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                $address_id = $mysqli->insert_id;
                /* Close statement */
                $stmt -> close();               
            }else{
                /* Error */
                echo "Prepared insertOrganization ADDRESS Statement Error: %s\n". $mysqli->error;
                return false;
            }

            /* Create a prepared statement */
            if($stmt = $mysqli -> prepare("INSERT INTO user_organization (login_id,fullname,address_id,contact_no,profile_img,bg_img,summary_id,link_id,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?)")) {
                $stmt -> bind_param("isisssiiss",$login_id,$fullname,$address_id,$contactno,$img_link,$img_link,$summary_id,$link_id,$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Close statement */
                $stmt -> close();
            }else{
                /* Error */
                echo "Prepared insertOrganization USER ORGANIZATION Statement Error: %s\n". $mysqli->error;
                return false;
            }        

        }else{
            /* Error */
            echo "Prepared insertOrganization USER LOGIN Statement Error: %s\n". $mysqli->error;
            return false;
        }

        /* Close connection */
        $mysqli -> close();
        return true;
    }

    function insertIndividual($oauth_provider,$oauth_uid,$level,$email,$password,$title,$fullname,$nric,$contactno,$address,$address1,$address2,$postcode,$city,$area,$state,$latitude,$longitude,$img_link){

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection insertIndividual Failed: " . mysqli_connect_errno();
            exit();
        }

        $status = 0;
        $current = date("Y-m-d H:i:s");

        // echo $email." ".$password." ".$oauth_provider." ".$oauth_uid." ".$level_id." ".$status." ".$current." ".$current;

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("INSERT INTO user_login (login_name,password,oauth_provider,oauth_uid,user_type_id,status,created,modified) VALUES (?,?,?,?,?,?,?,?)")) {
            // echo "inside: "+$note_id+" "+$current;
            $stmt -> bind_param("ssssiiss",$email,$password,$oauth_provider,$oauth_uid,$level,$status,$current,$current);
            /* Execute it */
            $stmt -> execute();
            /* Take new id */
            $login_id = $mysqli->insert_id;
            /* Close statement */
            $stmt -> close();

            if($stmt = $mysqli -> prepare("INSERT INTO address (address,address_line1,address_line2,postcode,city,area_id,latitude,longitude,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?)")) {
                $stmt -> bind_param("sssisissss",$address,$address1,$address2,$postcode,$city,$area,$latitude,$longitude,$current,$current);
                /* Execute it */
                $stmt -> execute();
                /* Take new id */
                $address_id = $mysqli->insert_id;
                /* Close statement */
                $stmt -> close();

                /* Create a prepared statement */
                if($stmt = $mysqli -> prepare("INSERT INTO user_individual (login_id,title_id,fullname,nric,address_id,contact_no,profile_img,created,modified) VALUES (?,?,?,?,?,?,?,?,?)")) {
                    $stmt -> bind_param("iississss",$login_id,$title,$fullname,$nric,$address_id,$contactno,$img_link,$current,$current);
                    /* Execute it */
                    $stmt -> execute();
                    /* Close statement */
                    $stmt -> close();
                }else{
                    /* Error */
                    echo "Prepared insertIndividual USER INDIVIDUAL Statement Error: %s\n". $mysqli->error;
                    return false;
                }

            }else{
                /* Error */
                echo "Prepared insertIndividual ADDRESS Statement Error: %s\n". $mysqli->error;
                return false;
            }            

        }else{
            /* Error */
            echo "Prepared insertIndividual USER LOGIN Statement Error: %s\n". $mysqli->error;
            return false;
        }

        /* Close connection */
        $mysqli -> close();
        return true;
    }

    //Naz check job claim
    function CheckUserJobClaim($log_id, $event_id){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection CheckUserJobClaim Failed: " . mysqli_connect_errno();
            exit();
        }

        if($stmt = $mysqli -> prepare("SELECT * FROM expert_jobclaim WHERE expert_id = ? AND event_id = ? ")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("ii", $log_id, $event_id);
            /* Execute it */
            $stmt -> execute();

            /* store result */
            $stmt->store_result();

            $result = $stmt->num_rows;

            $stmt -> close();

        }else{
            /* Error */
            echo "Prepared CheckUserJobClaim Statement Error: %s\n". $mysqli->error;
            return false;
        }


        /* Close connection */
        $mysqli -> close();
        return $result;
    }

    //Naz
    function ClaimTerbuka($log_id,$event_id){

        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection ClaimTerbuka Failed: " . mysqli_connect_errno();
            exit();
        }

        if($stmt = $mysqli -> prepare("UPDATE expert_event SET expert_id = ? WHERE event_id = ?")){

            $stmt -> bind_param("ii",$log_id,$event_id);

            $stmt -> execute();

            $stmt -> close();

        }else{

            echo "Prepared ClaimTerbuka Statement Error: %s\n". $mysqli->error;
            return false;
        }

        $mysqli -> close();
        return true;    

    }

    //Naz
    function ClaimPilihan($log_id,$event_id){

        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection ClaimPilihan Failed: " . mysqli_connect_errno();
            exit();
        }

        $current = date("Y-m-d H:i:s");


        if($stmt = $mysqli -> prepare("INSERT INTO expert_jobclaim (expert_id,event_id,created) VALUES (?,?,?)")){


            $stmt -> bind_param("iis", $log_id,$event_id,$current);
                /* Execute it */
            $stmt -> execute();
            // printf($mysqli->error);
           
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared ClaimPilihan Statement Error: %s\n". $mysqli->error;
            return false;
        }

        /* Close connection */
        $mysqli -> close();
        return true;

    }

    function updateEventStatus($status,$event_id){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
          echo "Connection updateEventStatus Failed: " . mysqli_connect_errno();
          exit();
        }

        if($status==0){
            $status_id=2;
        }else if($status==1){
            $status_id=3;
        }else{
            $status_id=1;
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("UPDATE expert_event SET status_id = ? WHERE event_id = ?")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */ 
            $stmt -> bind_param("ii", $status_id,$event_id);
            /* Execute it */
            $stmt -> execute();
        }else{
            /* Error */
            echo "Prepared updateEventStatus Statement Error: %s\n". $mysqli->error;
            return false;
        }

        /* Close connection */
        $mysqli -> close();
        return true;
    }    

    function isPaymentRefNoExisted($refno){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection isPaymentRefNoExisted Failed: " . mysqli_connect_errno();
            exit();
        }

        if($stmt = $mysqli -> prepare("SELECT payment_refno FROM payment WHERE payment_refno = ?")){
            $stmt -> bind_param("i",$id);

            $stmt -> execute();

            $stmt -> store_result();

            $count = $stmt->num_rows;

            /* Close statement */
            $stmt -> close();

        }else{

            echo "Prepared isPaymentRefNoExisted Statement Error: ". $mysqli->error;
        }
        /* Close connection */
        $mysqli -> close();

        return $count;
    }

    // function updateEventKulliyahPaymentStatus($data,$event_id){
    function createPaymentForEventKulliyah($data,$event_id){
        $result = true;

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
          echo "Connection updateEventStatus Failed: " . mysqli_connect_errno();
          exit();
        }

        // Begin Transaction
        $mysqli->autocommit(FALSE);
        $mysqli->begin_transaction();

        /* Create New Payment */
        if($stmt = $mysqli -> prepare("INSERT INTO payment (payment_channel, payment_refno, payment_desc, payment_bill_id, payment_status) VALUES (?,?,?,?,?)")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */ 
            $stmt -> bind_param("ssssi", $data['payment_channel'], $data['payment_refno'], $data['payment_desc'], $data['payment_bill_id'], $data['payment_status']);
            /* Execute it */
            if ( false===$stmt->execute() ) {
                echo 'updateEventStatus: create payment execute() failed: ' . htmlspecialchars($stmt->error);
                $result = false;
            }
            else {
                $payment_id = $mysqli->insert_id;
            }
            $stmt -> close();
        }else{
            /* Error */
            echo "updateEventStatus: Prepared create payment Statement Error: ". $mysqli->error;
            $result = false;
        }

        /* Update Event Kulliyah with new Payment ID */
        if ($result) {
            if($stmt = $mysqli -> prepare("UPDATE event_kulliyyah SET payment_id = ? WHERE id = ?")){
                /* Bind parameters
                 s - string, b - blob, i - int, etc */ 
                $stmt -> bind_param("ii", $payment_id, $event_id);
                /* Execute it */
                if ( false===$stmt->execute() ) {
                    echo 'updateEventStatus: update event_kulliyyah execute() failed: ' . htmlspecialchars($stmt->error);
                    $result = false;
                }
                $stmt -> close();
            }else{
                /* Error */
                echo "updateEventStatus: Prepared event_kulliyyah Statement Error: ". $mysqli->error;
                $result = false;
            }
        }
        // Commit transaction if everything OK
        if ($result) 
            $mysqli->commit();
        else
            $mysqli->rollback();

        /* Close connection */
        $mysqli -> close();
        return $result;
    } 

    function updatePaymentStatusAndExpertEvent($bill_id, $status){
        $result = true;

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
          echo "Connection updateEventStatus Failed: " . mysqli_connect_errno();
          exit();
        }

        // Begin Transaction
        $mysqli->autocommit(FALSE);
        $mysqli->begin_transaction();

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT id FROM payment WHERE payment_bill_id = ?")){
            $stmt -> bind_param("s",$bill_id);
            $stmt -> bind_result($payment_id);
            $stmt -> execute();
            $stmt -> fetch();
            $stmt -> close();
        }else{
            /* Error */
            echo "updatePaymentStatus: Prepared select payment Statement Error: ". $mysqli->error;
            $result = false;
        }
        if ($result) {
            if($stmt = $mysqli -> prepare("UPDATE payment SET payment_status = ? WHERE id = ?")){
                /* Bind parameters
                 s - string, b - blob, i - int, etc */ 
                $stmt -> bind_param("is", $status, $payment_id);
                /* Execute it */
                if ( false===$stmt->execute() ) {
                    echo 'updatePaymentStatus: create payment execute() failed: ' . htmlspecialchars($stmt->error);
                    $result = false;
                }
                $stmt -> close();
            }else{
                /* Error */
                echo "updatePaymentStatus: Prepared update payment Statement Error: ". $mysqli->error;
                $result = false;
            }
        }
        /* Update Expert Event with Pending */
        $status_id = 1;

        if ($result) {
            if($stmt = $mysqli -> prepare("UPDATE expert_event SET status_id = ? WHERE event_id IN (SELECT id FROM event_kulliyyah WHERE payment_id = ?)")){
                /* Bind parameters
                 s - string, b - blob, i - int, etc */ 
                $stmt -> bind_param("ii", $status_id, $payment_id);
                /* Execute it */
                if ( false===$stmt->execute()) {
                    echo 'updatePaymentStatus: update expert_event execute() failed: ' . htmlspecialchars($stmt->error);
                    $result = false;
                }
                else if ($mysqli->affected_rows == 0) {
                    echo 'updatePaymentStatus: update expert_event execute() failed: No record updated! ($payment_id = '.$payment_id.')';
                    $result = false;
                }
                $stmt -> close();
            }else{
                /* Error */
                echo "updatePaymentStatus: Prepared expert_event Statement Error: ". $mysqli->error;
                $result = false;
            }
        }
        // Commit transaction if everything OK
        if ($result) 
            $mysqli->commit();
        else
            $mysqli->rollback();

        /* Close connection */
        $mysqli -> close();
        return $result;
    }

    function getNewNotification($expert_id){        
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()){
            echo "Connection getNewNotification Failed: " . mysqli_connect_errno();
            exit();
        }

        $statusAccepted = 2;
        $statusRejected = 3;

        if($stmt = $mysqli -> prepare("SELECT x.event_id, x.event_id, x.service_id, x.status_id, x.start, x.end, y.fullname, y.profile_img, e.organizer_id, e.period_id, p.period, a.area, s.state, e.rate, e.attendance, e.topic, e.note, e.post_id, u.fullname, u.profile_img FROM expert_event x INNER JOIN user_expert y ON y.login_id = x.expert_id INNER JOIN event_kulliyyah e ON e.id = x.event_id INNER JOIN period p ON p.id = e.period_id INNER JOIN area a ON a.id = e.area_id INNER JOIN state s ON s.id = a.state_id INNER JOIN user_organization u ON u.login_id = e.organizer_id WHERE x.expert_id = ? AND (x.status_id <> ? AND x.status_id <> ?)")){

        // if($stmt = $mysqli -> prepare("SELECT * FROM event WHERE area_id = ? AND event_datetime_start AND event_datetime_end BETWEEN ? AND ?")){

            $stmt -> bind_param("iii",$expert_id,$statusAccepted,$statusRejected);

            $stmt -> execute();

            $result = array();
    
            $stmt->bind_result($event_id,$expert_id,$service_id,$status_id,$start,$end,$expert_name,$expert_image,$organizer_id,$period_id,$period,$area,$state,$rate,$attendance,$topic,$note,$post_id,$organizer_name,$organizer_image);
            
            $count = 0;
            while($stmt->fetch()){
                $result[$count]["event_id"]     = $event_id;
                $result[$count]["expert_id"]    = $expert_id;
                $result[$count]["service_id"]    = $service_id;
                $result[$count]["status_id"]    = $status_id;
                $result[$count]["start"] = $start;
                $result[$count]["end"] = $end;
                $result[$count]["expert_name"]  = $expert_name;
                $result[$count]["expert_image"] = $expert_image;
                $result[$count]["organizer_id"] = $organizer_id;
                $result[$count]["period_id"]      = $period_id;
                $result[$count]["period"]  = $period;
                $result[$count]["area"] = $area;
                $result[$count]["state"] = $state;
                $result[$count]["rate"] = $rate;
                $result[$count]["attendance"] = $attendance;
                $result[$count]["topic"] = $topic;
                $result[$count]["note"] = $note;
                $result[$count]["post_id"] = $post_id;           
                $result[$count]["organizer_name"] = $organizer_name;
                $result[$count]["organizer_image"] = $organizer_image;              
                $count++;               
            }

            $stmt -> close();

        }else{
            echo "Prepared getNewNotification Statement Error: %s\n". $mysqli->error;
        }

        $mysqli -> close();
        return $result;
    }

function searchJob($area,$service,$from,$to){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()){
            echo "Connection searchJob Failed: " . mysqli_connect_errno();
            exit();
        }

        // $service_id = 9;
        $expert_id = 0; //only search unclaimed job.
        $status_id = 5;

        if($stmt = $mysqli -> prepare("SELECT e.id, e.area_id, e.organizer_id, e.rate, e.attendance, e.topic, e.post_id, a.area, x.service_id, x.status_id, x.expert_id, x.start, x.end, u.fullname, o.fullname, s.state, p.post, r.period FROM event_kulliyyah e INNER JOIN period r ON r.id = e.period_id INNER JOIN area a ON a.id = e.area_id INNER JOIN state s ON s.id = a.state_id INNER JOIN expert_event x ON x.event_id = e.id LEFT JOIN user_expert u ON u.id = x.expert_id LEFT JOIN user_organization o ON o.login_id = e.organizer_id INNER JOIN post p ON p.id = e.post_id WHERE x.expert_id = ? AND e.area_id = ? AND x.service_id = ? AND x.status_id = ? AND x.start AND x.end BETWEEN ? AND ?")){
        // if($stmt = $mysqli -> prepare("SELECT * FROM event WHERE area_id = ? AND event_datetime_start AND event_datetime_end BETWEEN ? AND ?")){

            $stmt -> bind_param("iiiiss",$expert_id,$area,$service,$status_id,$from,$to);

            $stmt -> execute();

            $result = array();
    
            $stmt->bind_result($event_id,$area_id,$organizer_id,$rate,$attendance,$topic,$post_id,$area,$service_id,$status_id,$expert_id,$start,$end,$user_name,$org_name,$state,$post,$period);
            
            $count = 0;
            while($stmt->fetch()){
                $result[$count]["event_id"]     = $event_id;
                $result[$count]["area_id"]      = $area_id;
                $result[$count]["organizer_id"] = $organizer_id;
                $result[$count]["rate"]         = $rate;
                $result[$count]["attendance"]   = $attendance;
                $result[$count]["topic"]        = $topic;
                $result[$count]["post_id"]      = $post_id;
                $result[$count]["area"]         = $area;
                $result[$count]["service_id"]   = $service_id;
                $result[$count]["status_id"]    = $status_id;
                $result[$count]["expert_id"]    = $expert_id;
                $result[$count]["start"]        = date('d/m/Y', strtotime($start));
                $result[$count]["end"]          = $end;
                $result[$count]["user_name"]    = $user_name;
                $result[$count]["org_name"]     = $org_name;
                $result[$count]["state"]        = $state;
                $result[$count]["post"]        = $post;
                $result[$count]["period"]        = $period;
                $count++;               
            }

            $stmt -> close();
        }else{
            echo "Prepared searchJob Statement Error: %s\n". $mysqli->error;
        }

        $mysqli -> close();

        return $result;
    }    
    //NAZ

    function filterUstaz($area_id, $start, $end){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()){
            echo "Connection filterUstaz Failed: " . mysqli_connect_errno();
            exit();
        }

        //FOR FUTURE IF other service is available, THEN open OPTION to check other service TABLE IN Database for now check kulliyyah_event table since the service_id return is always 9.

        $status_id = 2; // Accepted Event 

        if($stmt = $mysqli -> prepare("SELECT u.login_id, u.fullname, u.area_id, a.area, x.expert_id, e.period_id, x.start, x.end, x.status_id FROM user_expert u LEFT JOIN expert_event x ON x.expert_id = u.login_id LEFT JOIN event_kulliyyah e ON e.id = x.event_id INNER JOIN area a ON a.id = u.area_id WHERE u.area_id = ? AND (x.status_id IS NULL OR x.status_id != ?) AND ((x.start AND x.end NOT BETWEEN ? AND ?) OR (x.start IS NULL AND x.end IS NULL )) GROUP BY u.login_id")){

            /* Bind parameters
             s - string, b - blob, i - int, etc */

            $stmt -> bind_param("iiss",$area_id,$status_id,$start,$end);

            $stmt -> execute();

            $result = array();
    
            $stmt->bind_result($login_id,$fullname,$area_id,$area,$expert_id,$period_id,$start,$end,$status_id);
            
            $count = 0;
            while($stmt->fetch()){
                $result[$count]["login_id"]     =   $login_id;
                $result[$count]["fullname"]     =   $fullname;
                $result[$count]["area_id"]      =   $area_id;
                $result[$count]["area"]         =   $area;
                $result[$count]["expert_id"]    =   $expert_id;
                $result[$count]["period_id"]    =   $period_id;
                $result[$count]["start"]        =   $start;
                $result[$count]["end"]          =   $end;
                $result[$count]["status_id"]    =   $status_id;
                
                $count++;               
            }

            $stmt -> close();
        }else{
            echo "Prepared filterUstaz Statement Error: %s\n". $mysqli->error;
        }

        $mysqli -> close();

        return $result;

    }
    
    function getPeriod($id){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getPeriod Failed: " . mysqli_connect_errno();
            exit();
        }

        if($stmt = $mysqli -> prepare("SELECT * FROM period WHERE id = ?")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> bind_param("i",$id);

            $stmt -> execute();

            $result = array();
            
            $stmt->bind_result($id,$period,$start,$end);

            while ($stmt->fetch()) {
                $result["id"] = $id;
                $result["period"] = $period;
                $result["start"] = $start;
                $result["end"] = $end;
                
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getPeriod Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }

    //Naz
    function InsertEvent($ustaz_id,$organizer_id,$area,$period,$datestart,$dateend,$service,$kadar,$kedatangan,$topik,$tambahan,$pilihan){

        $result = array('result' => true);

        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection InsertEvent Failed: " . mysqli_connect_errno();
            exit();
        }

        // Begin Transaction
        $mysqli->autocommit(FALSE);
        $mysqli->begin_transaction();

        $current = date("Y-m-d H:i:s");
        $status_id = 5; // new job id
        // $post_id = 2; // pilihan job id

        if($stmt = $mysqli -> prepare("INSERT INTO event_kulliyyah (organizer_id,period_id,area_id,rate,attendance,topic,note,post_id) VALUES (?,?,?,?,?,?,?,?)")) {
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("iiidissi", $organizer_id,$period,$area,$kadar,$kedatangan,$topik,$tambahan,$pilihan);
            /* Execute it */
            if ( false===$stmt->execute() ) {
                echo 'insert event_kulliyyah execute() failed: ' . htmlspecialchars($stmt->error);
                $result['result'] = false;
            }
            else {
                // printf($mysqli->error);
                /* Take new id */
                $event_id = $mysqli->insert_id;
                $result['event_id'] = $event_id;
                /* Close statement */
                $stmt -> close();
            
                if($stmt = $mysqli -> prepare("INSERT INTO expert_event (event_id,expert_id,status_id,service_id,start,end) VALUES (?,?,?,?,?,?)")) {
                    /* Bind parameters
                     s - string, b - blob, i - int, etc */
                    $stmt -> bind_param("iiiiss", $event_id,$ustaz_id,$status_id,$service,$datestart,$dateend);
                    /* Execute it */
                    if ( false===$stmt->execute() ) {
                        echo 'insert expert_event execute() failed: ' . htmlspecialchars($stmt->error);
                        $result['result'] = false;
                    }
                    else {
                        // printf($mysqli->error);
                        /* Take new id */
                        $expert_event_id = $mysqli->insert_id;
                        $result['expert_event_id'] = $expert_event_id;
                    }
                    /* Close statement */
                    $stmt -> close();
                }else{
                    /* Error */
                    echo "Prepared InsertEvent ExpertEvent Error: ". $mysqli->error;
                    $result['result'] = false;
                }
            }
        }else{
            /* Error */
            echo "Prepared InsertEvent Statement Error: ". $mysqli->error;
            $result['result'] = false;
        }

        // Commit transaction if everything OK
        if ($result) 
            $mysqli->commit();
        else
            $mysqli->rollback();

        /* Close connection */
        $mysqli -> close();
        return $result;
    }


    //Naz
    function InsertEventModule3($ustaz_id,$organizer_id,$area,$period,$datestart,$dateend,$service,$kadar,$kedatangan,$topik,$tambahan,$pilihan){

        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection InsertEvent Failed: " . mysqli_connect_errno();
            exit();
        }

        $current = date("Y-m-d H:i:s");
        $status_id = 5; // new job id
        // $post_id = 2; // pilihan job id

        if($stmt = $mysqli -> prepare("INSERT INTO event_kulliyyah (organizer_id,period_id,area_id,rate,attendance,topic,note,post_id,created) VALUES (?,?,?,?,?,?,?,?,?)")) {
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("iiidissis", $organizer_id,$period,$area,$kadar,$kedatangan,$topik,$tambahan,$pilihan,$current);
            /* Execute it */
            $stmt -> execute();
            // printf($mysqli->error);
            /* Take new id */
            $event_id = $mysqli->insert_id;
            /* Close statement */
            $stmt -> close();

            if($stmt = $mysqli -> prepare("INSERT INTO expert_event (event_id,expert_id,status_id,service_id,start,end,created) VALUES (?,?,?,?,?,?,?)")) {
                /* Bind parameters
                 s - string, b - blob, i - int, etc */
                $stmt -> bind_param("iiiisss", $event_id,$ustaz_id,$status_id,$service,$datestart,$dateend,$current);
                /* Execute it */
                $stmt -> execute();
                // printf($mysqli->error);
                /* Take new id */
                $new_id = $mysqli->insert_id;
                /* Close statement */
                $stmt -> close();


            }else{
                /* Error */
                echo "Prepared InsertEvent ExpertEvent Error: %s\n". $mysqli->error;
                return false;
            }

        }else{
            /* Error */
            echo "Prepared InsertEvent Statement Error: %s\n". $mysqli->error;
            return false;
        }

        /* Close connection */
        $mysqli -> close();
        return true;

    }



    function getProfileData($login_id){
        $result = null;

        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getProfileData Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT u.login_id, u.title_id, u.fullname, u.area_id, a.area, s.state, t.title FROM user_expert u INNER JOIN area a ON a.id = u.area_id INNER JOIN state s ON s.id = a.state_id INNER JOIN title t ON t.id = u.title_id WHERE u.login_id = ?")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("i",$login_id);
            /* Execute it */
            $stmt -> execute();

            $result = array();
            
            $stmt->bind_result($login_id,$title_id,$fullname,$area_id,$area,$state,$title);

            
            while ($stmt->fetch()) {
                $result["login_id"] = $login_id;
                $result["title_id"] = $title_id;
                $result["fullname"] = $fullname;
                $result["area_id"] = $area_id;
                $result["area"] = $area;
                $result["state"] = $state;
                $result["title"] = $title;
            
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getProfileData Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }

    function getAllPeriod(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllPeriod Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT * FROM period ORDER BY id ASC")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($id,$period,$start,$end);
            while ($stmt->fetch()) {
                $result[$count]["id"] = $id;
                $result[$count]["period"] = $period;
                $result[$count]["start"] = $start;
                $result[$count]["end"] = $end;
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllPeriod Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }

    function getAllUstaz(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllUstaz Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT u.id, u.login_id, u.title_id, u.fullname, u.nric, u.education, u.contact_no, u.area_id, u.created, u.modified, a.area, s.state, t.title FROM user_expert u INNER JOIN area a ON a.id = u.area_id INNER JOIN state s ON s.id = a.state_id INNER JOIN title t ON t.id = u.title_id")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($id,$login_id,$title_id,$fullname,$nric,$education,$mobile,$area_id,$created,$modified,$area,$state,$title);

            while ($stmt->fetch()) {
                $result[$count]["id"] = $id;
                $result[$count]["login_id"] = $login_id;
                $result[$count]["title_id"] = $title_id;
                $result[$count]["fullname"] = $fullname;
                $result[$count]["nric"] = $nric;
                $result[$count]["education"] = $education;
                $result[$count]["mobile"] = $mobile;
                $result[$count]["area_id"] = $area_id;
                $result[$count]["created"] = $created;
                $result[$count]["modified"] = $modified;
                $result[$count]["area"] = $area;
                $result[$count]["state"] = $state;
                $result[$count]["title"] = $title;
                
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllUstaz Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }

    function getExpertServices($id){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getExpertServices Failed: " . mysqli_connect_errno();
            exit();
        }

        if($stmt = $mysqli -> prepare("SELECT e.service_id, s.service FROM expert_service e LEFT JOIN service s ON s.id = e.service_id WHERE e.expert_id = ?")){
            $stmt -> bind_param("i",$id);

            $stmt -> execute();
            $result = array();
            $count = 0;

            $stmt->bind_result($service_id,$service);
            while ($stmt->fetch()) {
                $result[$count]["service_id"] = $service_id;
                $result[$count]["service"] = $service;
                $count++;
            }
            /* Close statement */
            $stmt -> close();

        }else{

            echo "Prepared getExpertServices Statement Error: %s\n". $mysqli->error;
        }
        /* Close connection */
        $mysqli -> close();
        return $result;
    }    




    // fuction for personal page
    function getUserTypeID($login_id){      
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
          echo "Connection getUserTypeID Failed: " . mysqli_connect_errno();
          exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT user_type_id FROM user_login WHERE id = ?")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("i", $login_id);
            /* Execute it */
            $stmt -> execute();
            /* Bind results */
            $stmt -> bind_result($result);
            /* Fetch the value */
            $stmt -> fetch();
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getUserTypeID Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result;
    }   

    function getOrganizationData($login_id){

        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getOrganizationData Failed: " . mysqli_connect_errno();
            exit();
        }

        // if($stmt = $mysqli -> prepare("SELECT * FROM user WHERE id = ?")){

        if($stmt = $mysqli -> prepare("SELECT l.login_name, l.status, u.id, u.login_id, u.fullname, u.address_id, d.address, d.postcode, d.city, d.area_id, a.area, t.id, t.state, d.latitude, d.longitude, u.contact_no, u.profile_img, u.bg_img, m.data1, m.data2, m.data3, m.data4, i.facebook, i.youtube, i.instagram, i.other FROM user_login l INNER JOIN user_organization u ON u.login_id = l.id INNER JOIN address d ON d.id = u.address_id INNER JOIN area a ON a.id = d.area_id INNER JOIN state t ON t.id = a.state_id INNER JOIN summary m ON m.id = u.summary_id LEFT JOIN links i ON i.id = u.link_id WHERE u.login_id = ?")){

            $stmt -> bind_param("i",$login_id);
            $stmt -> execute();
            $result = array();            
            $stmt->bind_result($email,$status,$user_id,$login_id,$fullname,$address_id,$address,$postcode,$city,$area_id,$area,$state_id,$state,$latitude,$longitude,$phone,$image,$background,$kulliyyah_booked,$myr_earned_spent,$upcoming_events,$unaccepted_bookings,$facebook,$youtube,$instagram,$other);           

            while ($stmt->fetch()) {
                $result["email"]    = $email;
                $result["status"]   = $status;
                $result["userID"]   = $user_id;
                $result["loginID"]  = $login_id;
                $result["fullname"] = $fullname;
                $result["addressID"]    = $address_id;
                $result["address"]  = $address;
                $result["postcode"] = $postcode;
                $result["city"]     = $city;
                $result["areaID"]   = $area_id;
                $result["area"]     = $area;
                $result["stateID"]  = $state_id;
                $result["state"]    = $state;
                $result["latitude"]     = $latitude;
                $result["longitude"]    = $longitude;
                $result["phone"]        = $phone;            
                $result["image"]        = $image;
                $result["background"]   = $background;
                $result["kulliyyahBooked"]      = $kulliyyah_booked;
                $result["myrEarnedSpent"]       = $myr_earned_spent;
                $result["upcomingEvents"]       = $upcoming_events;
                $result["unacceptedBookings"]   = $unaccepted_bookings;
                $result["facebook"]             = $facebook;
                $result["youtube"]              = $youtube;
                $result["instagram"]            = $instagram;                
                $result["other"]                = $other;
            }
            /* Close statement */
            $stmt -> close();

        }else{

            echo "Prepared getOrganizationData Statement Error: %s\n". $mysqli->error;
        }
        /* Close connection */
        $mysqli -> close();
        return $result;

    }    

    function getExpertData($login_id){

        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getExpertData Failed: " . mysqli_connect_errno();
            exit();
        }

        if($stmt = $mysqli -> prepare("SELECT l.login_name, l.status, u.id, u.login_id, u.title_id, t.title, u.fullname, u.nric, u.education, u.contact_no, u.area_id, a.area, s.id, s.state, u.profile_img, m.data1, m.data2, m.data3, m.data4 FROM user_login l INNER JOIN user_expert u ON u.login_id = l.id INNER JOIN title t ON t.id = u.title_id INNER JOIN area a ON a.id = u.area_id INNER JOIN state s ON s.id = a.state_id INNER JOIN summary m ON m.id = u.summary_id WHERE u.login_id = ?")){

            $stmt -> bind_param("i",$login_id);
            $stmt -> execute();
            $result = array();            
            $stmt->bind_result($email,$status,$user_id,$login_id,$title_id,$title,$fullname,$nric,$education,$mobile,$area_id,$area,$state_id,$state,$image,$kulliyyah_booked,$myr_earned_spent,$upcoming_events,$unaccepted_bookings);

            while ($stmt->fetch()) {
                $result["email"]    = $email;
                $result["status"]   = $status;
                $result["userID"]   = $user_id;
                $result["loginID"]  = $login_id;
                $result["titleID"]  = $title_id;
                $result["title"]    = $title;
                $result["fullname"] = $fullname;
                $result["nric"]     = $nric;
                $result["education"]= $education;
                $result["mobile"]   = $mobile;

                $result["areaID"]   = $area_id;
                $result["area"]     = $area;
                $result["stateID"]  = $state_id;
                $result["state"]    = $state;
                $result["image"]    = $image;
                $result["kulliyyahBooked"]      = $kulliyyah_booked;
                $result["myrEarnedSpent"]       = $myr_earned_spent;
                $result["upcomingEvents"]       = $upcoming_events;
                $result["unacceptedBookings"]   = $unaccepted_bookings;
                $result["status"]               = $status;
            }
            /* Close statement */
            $stmt -> close();

        }else{

            echo "Prepared getExpertData Statement Error: %s\n". $mysqli->error;
        }
        /* Close connection */
        $mysqli -> close();
        return $result;

    }    

    function getUserRecord($login_name,$password){       
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
          echo "Connection getUserRecord Failed: " . mysqli_connect_errno();
          exit();
        }

        /* Create a prepared statement */
        // if($stmt = $mysqli -> prepare("SELECT * FROM users WHERE email = ? AND password = ?")){

        if($stmt = $mysqli -> prepare("SELECT id, user_type_id, status FROM user_login WHERE login_name = ? AND password = ?")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            $stmt -> bind_param("ss",$login_name,$password);
            /* Execute it */
            $stmt -> execute();

            /* store result */
            $stmt->store_result();

            if($stmt->num_rows > 0){
                $result = array();
                $stmt->bind_result($login_id,$user_type_id,$status);

                while ( $stmt->fetch() ) {
                    $result["login_id"]     = $login_id;
                    $result["user_type_id"] = $user_type_id;
                    $result["status"]       = $status;
                }

            }else{

                $stmt -> close();
                $result = 0;

            }
            /* Close statement */
            
        }else{
            /* Error */
            echo "Prepared getUserRecord Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();

        return $result;
    }


    function searchKulliyyah($state,$area,$event_datetime_start,$event_datetime_end){
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()){
            echo "Connection searchKulliyyah Failed: " . mysqli_connect_errno();
            exit();
        }

        $service_id = 9;
        $status_id = 2;

        if($stmt = $mysqli -> prepare("SELECT e.id, e.organizer_id, e.period_id, p.period, e.area_id, e.rate, e.attendance, e.topic, e.note, e.post_id, u.login_id, u.fullname, a.area, s.state, x.expert_id, x.start, x.end, y.login_id, y.fullname, t.title FROM event_kulliyyah e INNER JOIN period p ON p.id = e.period_id INNER JOIN user_organization u ON u.login_id = e.organizer_id INNER JOIN area a ON a.id = e.area_id INNER JOIN state s ON s.id = a.state_id INNER JOIN expert_event x ON x.event_id = e.id INNER JOIN user_expert y ON y.login_id = x.expert_id INNER JOIN title t ON t.id = y.title_id WHERE e.area_id = ? AND x.service_id = ? AND x.status_id = ? AND x.start AND x.end BETWEEN ? AND ? ORDER BY x.start")){

            $stmt -> bind_param("iiiss",$area,$service_id,$status_id,$event_datetime_start,$event_datetime_end);

            $stmt -> execute();

            $result = array();
    
            $stmt->bind_result($event_id,$organizer_id,$period_id,$period,$area_id,$rate,$attendance,$topic,$note,$post_id,$organizer_login_id,$organizer_name,$area,$state,$expert_id,$start,$end,$expert_login_id,$expert_name,$title);
            
            $count = 0;
            while($stmt->fetch()){
                $result[$count]["eventID"]      = $event_id;
                $result[$count]["organizerID"]  = $organizer_id;
                $result[$count]["periodID"]     = $period_id;
                $result[$count]["period"]       = $period;
                $result[$count]["areaID"]       = $area_id;
                $result[$count]["rate"]         = $rate;
                $result[$count]["attendance"]   = $attendance;
                $result[$count]["topic"]        = $topic;
                $result[$count]["note"]         = $note;
                $result[$count]["postID"]       = $post_id;
                $result[$count]["organizerLoginID"]= $organizer_login_id;
                $result[$count]["organizerName"]= $organizer_name;
                $result[$count]["area"]         = $area;
                $result[$count]["state"]        = $state;
                $result[$count]["expertID"]     = $expert_id;
                $result[$count]["start"]        = $start;
                $result[$count]["end"]          = $end;
                $result[$count]["expertLoginID"]= $expert_login_id;
                $result[$count]["expertName"]   = $expert_name;
                $result[$count]["title"]        = $title;
                $count++;               
            }

            $stmt -> close();
        }else{
            echo "Prepared searchKulliyyah Statement Error: %s\n". $mysqli->error;
        }

        $mysqli -> close();

        return $result;
    }

    function getAllAreas(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllAreas Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT * FROM area")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($area_id,$area,$state_id);
            while ($stmt->fetch()) {
                $result[$count]["area_id"] = $area_id;
                $result[$count]["area"] = $area;
                $result[$count]["state_id"] = $state_id;
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllAreas Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }

    function getAllBanks(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllBanks Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT * FROM bank ORDER BY bank ASC")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($id,$bank);
            while ($stmt->fetch()) {
                $result[$count]["id"] = $id;
                $result[$count]["bank"] = $bank;
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllBanks Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }


    function getAllServices(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllServices Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT * FROM service ORDER BY service ASC")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($id,$service);
            while ($stmt->fetch()) {
                $result[$count]["id"] = $id;
                $result[$count]["service"] = $service;
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllServices Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }    

    function getAllStates(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllStates Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT * FROM state WHERE status_id = 1 ORDER BY state ASC")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($state_id,$state,$status_id);
            while ($stmt->fetch()) {
                $result[$count]["state_id"] = $state_id;
                $result[$count]["state"] = $state;
                $result[$count]["status_id"] = $status_id;
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllStates Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }

    function getAllTitles(){
        /* Create a new mysqli object with database connection parameters */
        $mysqli = new mysqli($this->db_host, $this->username, $this->pwd, $this->database);

        if(mysqli_connect_errno()) {
            echo "Connection getAllTitles Failed: " . mysqli_connect_errno();
            exit();
        }

        /* Create a prepared statement */
        if($stmt = $mysqli -> prepare("SELECT * FROM title ORDER BY title ASC")){
            /* Bind parameters
             s - string, b - blob, i - int, etc */
            /* Execute it */
            $stmt -> execute();

            $result = array();
            $count = 0;
            $stmt->bind_result($id,$title);
            while ($stmt->fetch()) {
                $result[$count]["id"] = $id;
                $result[$count]["title"] = $title;
                $count++;
            }
            /* Close statement */
            $stmt -> close();
        }else{
            /* Error */
            echo "Prepared getAllTitles Statement Error: %s\n". $mysqli->error;
        }

        /* Close connection */
        $mysqli -> close();
        return $result; 
    }	
}

$process = new Process();

$process->InitDB(/*hostname*/$DBSERVER,
                 /*username*/$DBUSER,
                 /*password*/$DBPASS,
                 /*database name*/$DBNAME
				 );


$title  = $process->getAllTitles();
$state  = $process->getAllStates();
$area   = $process->getAllAreas();
$service = $process->getAllServices();
$bank   = $process->getAllBanks();

$_SESSION["title"]  = $title;
$_SESSION["area"]   = $area;
$_SESSION["state"]  = $state;
$_SESSION["service"] = $service;
$_SESSION["bank"]   = $bank;


?>