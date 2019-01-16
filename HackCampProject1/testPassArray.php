<?php

//Include in calculator.php with two arrays to be passed out

if(isset($_GET['diameter'])) {
$value1 = $_GET['diameter']/2;
$value2 = $_GET['pressure'];
$value3 = $_GET['temp'];
$value4 = $_GET['mass'];
$value5 = $_GET['dwnPressure'];
} else {
    $value1 = 1;
    $value2 = 2;
    $value3 = 3;
    $value4 = 4;
    $value5 = 5;
}

//Changing the values in these arrays changes the output to the graph
$xAxis = array( 1,2,3,4,5); //x-axis values

$yAxis = array( $value1,$value2,$value3,$value4,$value5); //y-axis values


echo '<script>';
echo 'var data; var numbers = ' . json_encode($xAxis) . ';';
echo 'localStorage.setItem(\'data\', JSON.stringify(numbers))' . ';';
echo '</script>';


echo '<script>';
echo 'var data; var numbers2 = ' . json_encode($yAxis) . ';';
echo 'localStorage.setItem(\'data2\', JSON.stringify(numbers2))' . ';';
echo '</script>';