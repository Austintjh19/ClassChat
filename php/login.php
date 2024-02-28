<?php 

    session_start();
    include_once "config.php";
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $classcode = mysqli_real_escape_string($conn, $_POST['classcode']);

    if(!empty($email) && !empty($password)){
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];
            if($user_pass === $enc_pass){
                $status = "Active now";

                $check_class_query = mysqli_query($conn, "SELECT * FROM classes WHERE class_id = '{$classcode}' AND user_id = {$row['user_id']}");
                if (mysqli_num_rows($check_class_query) == 0) {
                    $insert_class_query = mysqli_query($conn, "INSERT INTO classes (class_id, user_id) VALUES ('{$classcode}', {$row['user_id']})");
                    if (!$insert_class_query) {
                        echo "Failed to initialise class code. Please try again!";
                        exit(); 
                    }
                }

                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}', curr_class_id = '{$classcode}' WHERE unique_id = {$row['unique_id']}");
                if($sql2){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    echo "success";
                }else{
                    echo "Something went wrong. Please try again!";
                }
            }else{
                echo "Email or Password is Incorrect!";
            }
        }else{
            echo "$email - This email not Exist!";
        }
    }else{
        echo "All input fields are required!";
    }
?>