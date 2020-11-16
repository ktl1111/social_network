<?php
    $con = mysqli_connect("localhost", "root", "", "social");

    if(mysqli_connect_errno()){
        echo "Failed to connect: " . mysqli_connect_errno;
    }

    $query = mysqli_query($con, "INSERT INTO test VALUES ('2', 'Optimus Prime')");

    ?>
<html>
<head>
    <title></title>
</head>
<body>
Hello Reece!!
</body>
</html>
