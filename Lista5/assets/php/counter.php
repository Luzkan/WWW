<?php 
    // Check IP (could be from shared internet or passed by proxy)
    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }
    $ip = getUserIpAddr();
?>

<?php
    include 'dbconfig.php';

    // Look for detected IP in the database
    $qry = "SELECT * FROM `ipdb` WHERE `ip` = '$ip'";
    $result = mysqli_query($con,$qry);

    // $found is the number of results for our query...
    $found = mysqli_num_rows($result);

    // ...and if its equal to 0 - it means the IP wasn't found
    if ($found == 0){
        // We store this new IP into the table
        $qry3 = "INSERT INTO `ipdb`(`ip`) VALUES ('$ip')";
        mysqli_query($con, $qry3);

        // And we look for the counter of unique visits...
        $qry1 = "SELECT * FROM `counter` WHERE `id` = 0";
        $result1 = mysqli_query($con, $qry1);
        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);

        // ...to update it with new unique visitor
        $count = $row1['visitors'];
        $count = $count + 1;
        $qry2 = "UPDATE `counter` SET `visitors`='$count' WHERE `id`=0";
        $result2=mysqli_query($con,$qry2);

        // Print current visit counter
        echo "$count | New IP: [$ip]";
    }else{
        // Retrieve information about the number of unique visits
        $qry1 = "SELECT * FROM `counter` WHERE `id` = 0";
        $result1 = mysqli_query($con,$qry1);
        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
        $count = $row1['visitors'];

        // And print it
        echo "$count | Visitor IP: [$ip]";
    }
?>