<?php

use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';


$client = ClientBuilder::create()->build(); 


if(!empty($_POST)){ // (4)
if(isset($_POST['name'],$_POST['gender'], $_POST['age'],
$_POST['complexion'],
$_POST['attributes'])){

$name = $_POST['name'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$complexion = $_POST['complexion'];
$attributes = explode(',', $_POST['attributes']); // (5)

// (6)
$indexed = $client->index([
    'index' => 'children',
    'type' => 'child',
    'body' => [
        'name' => $name,
        'gender' => $gender,
        'age' => $age,
        'complexion' => $complexion,
        'attributes' => $attributes
     ],
]);
}
}
?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Child Details</title>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <form action="add.php" method="post" autocomplete="off">
            <div class="box">
                   <label class="lbl">
                    Name
                    <input type="text" name="name" class="c">
                </label>
                <br>
                <label class="lbl">
                    Gender
                    <input type="text" name="gender"  class="c">
                </label>
                <br>
                <label class="lbl">
                    Age
                    <input type="text" name="age" placeholder="number only" class="c">
                </label>
                <br>
                <label class="lbl">
                    Complexion
                    <input type="text" name="complexion"  class="c">
                </label class="lbl">
                <br>
                <label class="lbl">
                    The Attributes
                    <textarea type="text" name="attributes" rows="4" placeholder="comma, separated attributes" class="c" ></textarea>
                </label>
                   
                <input type="submit" value="Add" class="btn">
            </div>
        </form>
    </body>
 
   </html>