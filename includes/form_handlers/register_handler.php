<?php

//Declaring variables to prevent errors
$fname = ""; //First name
$lname = ""; //Last name
$em = ""; //email
$em2 = ""; //email2
$password = ""; //password
$password2 = ""; //password 2
$date = ""; // Sign up date
$error_array = array(); //Holds error messages

if(isset($_POST['register_button'])){

    //Registration form values

    //First name
    $fname = strip_tags($_POST['reg_fname']); //去除html tags如<a><a>防injection攻擊
    $fname = str_replace(' ', '', $fname); //把fname裡空格改成''＝去除空格
    $fname = ucfirst(strtolower($fname));//Uppercase first letter
    $_SESSION['reg_fname'] = $fname; //Stores first name into session variable

    //Last name
    $lname = strip_tags($_POST['reg_lname']); //去除html tags如<a><a>防injection攻擊
    $lname = str_replace(' ', '', $lname); //把fname裡空格改成''＝去除空格
    $lname = ucfirst(strtolower($lname));//Uppercase first letter
    $_SESSION['reg_lname'] = $lname; //Stores last name into session variable


    //email
    $em = strip_tags($_POST['reg_email']); //去除html tags如<a><a>防injection攻擊
    $em = str_replace(' ', '', $em); //把fname裡空格改成''＝去除空格
    $em = ucfirst(strtolower($em));//Uppercase first letter
    $_SESSION['reg_email'] = $em; //Stores email into session variable


    //email2
    $em2 = strip_tags($_POST['reg_email2']); //去除html tags如<a><a>防injection攻擊
    $em2 = str_replace(' ', '', $em2); //把fname裡空格改成''＝去除空格
    $em2 = ucfirst(strtolower($em2));//Uppercase first letter
    $_SESSION['reg_email2'] = $em2; //Stores email2 into session variable


    //password
    $password = strip_tags($_POST['reg_password']); //去除html tags如<a><a>防injection攻擊
    $password2 = strip_tags($_POST['reg_password2']); //去除html tags如<a><a>防injection攻擊


    $date = date("Y-m-d") ; //current date

    if($em == $em2){
        //check if email is in valid format
        if(filter_var($em, FILTER_VALIDATE_EMAIL)){

            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //Check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$em'");

            //Count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0){
                array_push($error_array, "Email already in use<br>");
            }
        } else {
            array_push($error_array, "Invalid email format<br>");
        }

    } else{
        array_push($error_array, "Emails don't match<br>");
    }

    if(strlen($fname) > 25 || strlen($fname) < 2){
        array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
    }

    if(strlen($lname) > 25 || strlen($lname) < 2){
        array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
    }

    if($password != $password2){
        array_push($error_array, "Your passwords do not match<br>");
    } else{
        if(preg_match('/[^A-Za-z0-9]/', $password)){
            array_push($error_array, "Your password can only contain english characters or numbers<br>");
        }
    }

    if(strlen($password) > 30 || strlen($password) < 5){
        array_push($error_array, "Your password must be between 5 and 30 characters<br>");
    }

    if(empty($error_array)){
        $password = md5($password); //Encrypt password before sending to database

        //Generating username by concatenating first name and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        $i = 0;

        $temp_username = $username;
        //if username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0){
            $i++;
            $temp_username = $username . "_" . $i; //mike_1
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$temp_username'");
        }

        $username = $temp_username;

        //Profile picture assignment
        $rand = rand(1,2); //Random number between 1 and 2
        if($rand == 1)
            $profile_pic = "assets/images/profile_pics/default/head_black.png";
        else if($rand == 2)
            $profile_pic = "assets/images/profile_pics/default/head_white.png";

        //Insert the values into our database
        $query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        array_push($error_array, "<span style='color: #14c800;'>Your're all set! Goahead and login!</span><br>");

        //Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";


    }


}

?>