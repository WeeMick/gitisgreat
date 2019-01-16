<?php


//Include in calculator.php with two arrays to be passed out

if(isset($_GET['diameter'])) {
    $radius = $_GET['diameter']/2;
    $pressure = $_GET['pressure'];
    $temp = $_GET['temp'];
    $mass = $_GET['mass'];
    $dwnPressure = $_GET['dwnPressure'];
}



require_once ("calculator.php");
//testing that the functions work
//test fo,r the first row in the excel spreadsheet


$calculator = new Calculator($radius, $temp, $mass, $dwnPressure, $pressure);
$cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);


for($i=0;$i<11;$i++) {
    //echo $c;
    // echo $u;
    //echo $p;

    $deltaPprevious =$deltaP;
    $deltaTprevious = $deltaTtest;
    $deltaP = $calculator->calculateDeltaP();   //$maxDeltaP, $m, $t, $pi, $pk
    $deltaParray []= $deltaP;
    $pk = $calculator->calculatePk($deltaP);  //$pi, $deltap, $m, $t
    $p = $calculator->calculateDensity($deltaP, $pk);   //$deltap, $m, $t, $pi, $pk
    $pArray [] = $p;
    $x = $calculator->calculateX(); //$r, $cd, $deltap, $density, $deltaT
    $af = $calculator->valveOpeningArea(); //$r, $x, $cd, $deltap, $density, $deltaT
    $q = $calculator->calculateQ($af, $cd[$i], $deltaP, $p);  //$areaF, $cd, $deltap, $density, $deltaT, $r
    $qArray [] = $q;
    $u = $calculator->calculateU($q, $af);  //$q, $areaF, $deltap, $density, $deltaT, $r
    $uArray []= $u;
    $c = $calculator->calculateCelerity($deltaP, $p, $u);  //$deltap, $density, $fluid, $deltaT, $r
    $cArray []= $c;
    //$deltaPprevious=null;

    $deltaTtest = $calculator->calculateDeltaT($c, $u,$p, $deltaPprevious,$deltaTprevious);
    $deltaTArray [] = $deltaTtest;
    $time = $calculator->calculateTime($deltaTtest);
    $timeArray [] = $time;
}

echo '<script>';
echo 'var data; var numbers = ' . json_encode($timeArray) . ';';
echo 'localStorage.setItem(\'xChart1\', JSON.stringify(numbers))' . ';';
echo '</script>';


echo '<script>';
echo 'var data; var numbers2 = ' . json_encode($deltaParray) . ';';
echo 'localStorage.setItem(\'yChart1\', JSON.stringify(numbers2))' . ';';
echo '</script>';

echo '<script>';
echo 'var data; var numbers = ' . json_encode($timeArray) . ';';
echo 'localStorage.setItem(\'xChart2\', JSON.stringify(numbers))' . ';';
echo '</script>';


echo '<script>';
echo 'var data; var numbers2 = ' . json_encode($qArray) . ';';
echo 'localStorage.setItem(\'yChart2\', JSON.stringify(numbers2))' . ';';
echo '</script>';

echo '<script>';
echo 'var data; var numbers = ' . json_encode($timeArray) . ';';
echo 'localStorage.setItem(\'xChart3\', JSON.stringify(numbers))' . ';';
echo '</script>';


echo '<script>';
echo 'var data; var numbers2 = ' . json_encode($uArray) . ';';
echo 'localStorage.setItem(\'yChart3\', JSON.stringify(numbers2))' . ';';
echo '</script>';
