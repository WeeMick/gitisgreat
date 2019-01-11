<?php
/**
 * Created by PhpStorm.
 * User: Elena Secara
 * Date: 10/01/2019
 * Time: 12:03
 */

class Calculator
{
    var $r = 75, $t = 35, $m = 0.0195, $pi = 20, $deltaP = 0, $maxDeltaP = 205, $x = 0, $cd=1;

    //constructor
    public function __construct($r, $t, $m, $pi, $deltaP, $maxDeltaP, $x, $cd) {
        $this->r = $r;
        $this->t = $t;
        $this->m = $m;
        $this->pi = $pi;
        $this->deltaP = $deltaP;
        $this->maxDeltaP = $maxDeltaP;
        $this->x = $x;
        $this->cd = $cd;
    }

    //valve opening area calculations
    public function valveOpeningArea($r, $x, $cd, $deltap, $density, $deltaT, $r){
        $x = ($r / 5) + $x;
        $d = ($r - (($r * 2 - $x) / 2)) / 360;
        $e = acos($d) * 180 / Pi();
        $c = Pi() * pow($r, 2) * 2;
        $g = $r - ((2 * $r - $x) / 2);
        $h = sqrt(pow($r, 2) - pow($r - (2 * $r - $x) / 2, 2));
        $af = ($e * $c) - ($g * $h);
        $this->calculateQ($af, $cd, $deltap, $density, $deltaT, $r);
        return $af;
    }

    //delta P calculations
    public function calculateDeltaP($deltaP, $maxDeltaP, $m, $t, $pi, $pk){
        if (!is_numeric($deltaP)) {
            // echo "Delta P max is: ".
            $deltaP = $maxDeltaP;
            //return $deltaP;
        } else {
//            echo "Delta P is: ".
            $deltaP = $deltaP * 0.677;
            //return $deltaP;
        }
        $this->density($deltaP, $m, $t, $pi, $pk);   //this before the return statement (if i need one)
    }

    // Upstream Pressure at valve (Pk) calculations
    public function calculatePk($pi, $deltaP, $m, $t){
        $pk =  $pi + $deltaP;
        $this->density($deltaP, $m, $t, $pi, $pk);
        return $pk;
    }

    //density calculations
    public function density($deltap, $m, $t, $pi, $pK){
        $deltaP = $deltap;
        $pk = $pK;
        $p = ($deltaP * pow(10, 5) * $m)/(8.3142 * (273 + $t * $pi/$pk));
        return $p;
    }

    //Internal Area of Pipe (apipe) calculations
    public function calculatePipeArea($r){
        $apipe = (pi() * pow(2 * $r, 2)) / 4 . "<br>";
        return $apipe;
    }

    //Volumetric flow across (ValveQ(t)) calculations
    public function calculateQ($areaF, $cd, $deltap, $density, $deltaT, $r){
        $af = $areaF;
        $deltaP = $deltap;
        $p = $density;

        $q = $cd * $af * pow(10, -6) * (sqrt((2 * $deltaP) / $p)) . '</br>';
        $this->calculateU($q, $areaF, $deltap, $density, $deltaT, $r);
        return $q;
    }

    //Fluid velocity (u) calculations
    public function calculateU($q, $areaF, $deltap, $density, $deltaT, $r){
        $af = $areaF;
        $u = $q / ($af * pow(10, -6));
        $this->calculateCelerity($deltap, $density, $u, $deltaT, $r);
        return $u;
    }

    //celerity (c) calculations
    public function calculateCelerity($deltap, $density, $fluid, $deltaT, $r){
        $deltaP = $deltap;
        $p = $density;
        $u = $fluid;
        if($u != 0) {
            $c = $deltaP / ($p * $u);
//            return $c;
        }else{
            $c = 0;
//            return $c;
        }
        $this->calculateDeltaT($c, $fluid, $deltaT, $density, $deltap, $r);
    }

    //time of closure (delta T) calculations
    public function calculateDeltaT($celerity, $fluid, $deltaT, $density, $deltap, $r){
        $c = $celerity;
        $u = $fluid;
        $p = $density;
        $deltaP = $deltap;
        if($c == 0 || $u == 0) {
            $deltaT = $deltaT/2;
            return $deltaT;
        } else if(is_numeric($deltaP)) {
            $deltaT = (($c*$p*$u)/$deltaP);
            return $deltaT;
        } else {
            $deltaT = 0 * ($r / 75);
            return $deltaT;
        }
    }
}