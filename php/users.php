<?php
    session_start();
    include_once "config.php";

    $class_id = $_SESSION['curr_class_id'];
    $outgoing_id = $_SESSION['unique_id'];

    // SELECT * 
    // FROM users 
    // WHERE NOT unique_id = 725578308
    // AND user_id IN (SELECT user_id FROM classes WHERE class_id = 'CS2030')
    // ORDER BY user_id DESC;
    
    //  $sql = "SELECT * 
    //     FROM users 
    //     WHERE NOT unique_id = {$outgoing_id} 
    //     AND user_id IN (SELECT user_id FROM classes WHERE class_id = {$class_id})
    //     ORDER BY user_id DESC";

    // $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} AND user_id IN (SELECT user_id FROM classes WHERE class_id = {$class_id} ) ORDER BY user_id DESC";

    $sql = "SELECT * 
        FROM users 
        WHERE NOT unique_id = {$outgoing_id} 
        AND user_id IN (SELECT user_id 
                        FROM classes 
                        WHERE class_id = (
                            SELECT curr_class_id 
                            FROM users 
                            WHERE unique_id = {$outgoing_id}
                        )
                       )
        ORDER BY user_id DESC";

    // $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY user_id DESC";
    $query = mysqli_query($conn, $sql);

    $output = "";
    
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>

