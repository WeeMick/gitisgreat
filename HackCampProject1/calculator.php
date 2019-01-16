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
    var $r , $t , $m , $pi , $maxDeltaP , $deltaP, $x, $time ;
    var $cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);
    var $uArray =array();
    var $deltaParray =array();
    var $cArray =array();
    var $pArray =array();
    var $tArray = array();

    //constructor
    public function __construct($r, $t, $m, $pi, $maxDeltaP) {
        $this->r = $r;
        $this->t = $t;
        $this->m = $m;
        $this->pi = $pi;
        $this->maxDeltaP = $maxDeltaP;
        $deltaP = null;
        $x = null;
        $time=null;
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
        $apipe = (pi() * pow(2 * $r, 2)) / 4;
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
    public function calculateDeltaT($celerity, $fluid,$density, $deltaPprevious, $deltaTprevious)
    {
        if($celerity == 0 || $fluid == 0){
            $deltaT = $deltaTprevious/2;
        }
        else if(is_null($deltaPprevious)) {
            $deltaT = 0 * ($this->r / 75);
        }
        else
        {
            $deltaT=(($celerity*$fluid*$density)/$deltaPprevious) * ($this->r/75);
        }
        return $deltaT;
    }


    //calculating the time for valve closure (needed for the graphs)
    public function calculateTime($deltat){

        if($deltat==0)
            $this->time = 0;
        else
            $this->time = $deltat+$this->time;
        return $this->time;
    }
}









