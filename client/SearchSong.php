<?php
echo "hello world";
if(isset($_GET['search_value']))
{

    $search_value = $_GET['search_value']; // "John"
    echo "search_value: " . htmlspecialchars($search_value) ;
}
else{
    echo "no parameter";
}
echo "welcome to Login page";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    hello world
</body>
</html>