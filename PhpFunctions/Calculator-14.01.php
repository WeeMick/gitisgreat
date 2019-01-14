<?php
/**
 * Created by PhpStorm.
 * User: Elena Secara
 * Date: 10/01/2019
 * Time: 12:03
 */
error_reporting(0);
class Calculator
{
    var $r , $t , $m , $pi , $maxDeltaP , $deltaP, $x ;
    var $cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);
//    var $cd =1;
    var $uArray =array();
    var $deltaParray =array();
    var $cArray =array();
    var $pArray =array();

    //constructor
    public function __construct($r, $t, $m, $pi, $maxDeltaP) {
        $this->r = $r;
        $this->t = $t;
        $this->m = $m;
        $this->pi = $pi;
        $this->maxDeltaP = $maxDeltaP;
        $deltaP = null;
        $x = null;
    }

    /**
     * @return array
     */
    public function getCd($cd)
    {
//        return $this->cd;
        foreach ($cd as $value) {
            return $value;
        }
    }

    //calculating x; x is dependent on the previous value of x and on r
    public function calculateX(){

        if(is_numeric($this->x)){
           $this->x = ($this->r/5) + $this->x;

        }else{
            $this->x = 0;

        }

        return $this->x;
    }

    //valve opening area calculations
    public function valveOpeningArea(){

        $d = ($this->r - (($this->r * 2 - $this->x) / 2)) / 360;
        $e = acos($d) * 180 / Pi();
        $c = Pi() * pow($this->r, 2) * 2;
        $g = $this->r - ((2 * $this->r - $this->x) / 2);
        $h = sqrt(pow($this->r, 2) - pow($this->r - (2 * $this->r - $this->x) / 2, 2));
        $af = ($e * $c) - ($g * $h);

        return $af;
    }

    //delta P calculations
    public function calculateDeltaP(){
        if (!is_numeric($this->deltaP)) {
            $this->deltaP = $this->maxDeltaP;
            return $this->deltaP;
        } else {

            $this->deltaP = $this->deltaP * 0.677;
            return $this->deltaP;
        }

    }

    // Upstream Pressure at valve (Pk) calculations
    public function calculatePk($deltap){
        $pk =  $this->pi + $deltap;
        return $pk;
    }

    //density calculations
    public function calculateDensity($deltap,$pk){
        $p = ($deltap * pow(10, 5) * $this->m)/(8.3142 * (273 + $this->t * $this->pi/$pk));
        return $p;
    }

    //Internal Area of Pipe (apipe) calculations
    public function calculatePipeArea($r){
        $apipe = (pi() * pow(2 * $r, 2)) / 4 . "<br>";
        return $apipe;
    }

    //Volumetric flow across (ValveQ(t)) calculations
    public function calculateQ($areaF, $cd, $deltap, $p){

         $q = $cd * $areaF * pow(10, -6) * (sqrt((2* $deltap)/$p));
        return $q;
    }

    //Fluid velocity (u) calculations
    public function calculateU($q, $areaF){
        $u = $q / ($areaF * pow(10, -6));
        return $u;
    }

    //celerity (c) calculations
    public function calculateCelerity($deltap, $density, $fluid){

        if($density != 0) {
            $c = $deltap / ($density * $fluid);

        }else{
            $c = 0;

        }

        return $c;
    }

    //time of closure (delta T) calculations
    public function calculateDeltaT($celerity, $fluid,$density, $deltaPprevious)
    {
        if(is_null($deltaPprevious))
        {
            $deltaT = 0*($this->r/75);
            //echo "is null";
        }
        else
        {
            $deltaT=(($celerity*$fluid*$density)/$deltaPprevious)*($this->r/75);
            //echo "is not null";
        }

        return $deltaT;
    }

    public function calculateDeltaTtest($c){
//        var_dump($c);
//        var_dump($u);
//        var_dump($p);
//        var_dump($deltap);
//        var_dump($r);
        echo $c;
    }

    //calculating the time for valve closure (needed for the graphs)
    public function calculateTime($deltat){
        $tci = 0;
        $deltaT = $deltat;
        if(is_numeric($tci)){
            $tci = $tci + $deltaT;
            return $tci;
        }else{
            $tci = $deltaT;
            return $tci;
        }
    }




}

//testing that the functions work
//test fo,r the first row in the excel spreadsheet

$calculator = new Calculator(75, 35, 0.0195, 20, 205);
$cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);

for($i=0;$i<11;$i++) {
    //echo $c;
   // echo $u;
    //echo $p;

     $deltaPprevious =$deltaP;
     var_dump( $deltaP);
     $deltaP = $calculator->calculateDeltaP() .'</br>';   //$maxDeltaP, $m, $t, $pi, $pk
     //$deltaParray []= $deltaP;
     $pk = $calculator->calculatePk($deltaP) .'</br>';  //$pi, $deltap, $m, $t
    echo $p = $calculator->calculateDensity($deltaP, $pk) .'</br>';   //$deltap, $m, $t, $pi, $pk
     $pArray [] = $p;
     $x = $calculator->calculateX() .'</br>'; //$r, $cd, $deltap, $density, $deltaT
     $af = $calculator->valveOpeningArea() .'</br>'; //$r, $x, $cd, $deltap, $density, $deltaT
     $q = $calculator->calculateQ($af, $cd[$i], $deltaP, $p) .'</br>';  //$areaF, $cd, $deltap, $density, $deltaT, $r
    echo $u = $calculator->calculateU($q, $af) .'</br>';  //$q, $areaF, $deltap, $density, $deltaT, $r
    $uArray []= $u;
     echo $c = $calculator->calculateCelerity($deltaP, $p, $u) .'</br>';  //$deltap, $density, $fluid, $deltaT, $r
     $cArray []= $c;
     //$deltaPprevious=null;
    echo  $deltaTtest = $calculator->calculateDeltaT($c, $u,$p, $deltaPprevious).'</br>';

}



