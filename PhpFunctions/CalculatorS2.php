<?php
/**
 * Created by PhpStorm.
 * User: Elena Secara
 * Date: 10/01/2019
 * Time: 12:03
 */
error_reporting(0);
class CalculatorS2
{
    var $r , $t , $m , $pi , $maxDeltaP , $deltaP, $x, $time, $qOutflow, $areaRatio, $deltaTmj, $deltaTp, $deltaToutflow;
    var $cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);
    var $uArray =array();
    var $deltaParray =array();
    var $cArray =array();
    var $pArray =array();
    var $tArray = array();
    var $qmjArray = array();

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
        $qOutflow = null;
        $areaRatio = 1;
        $deltaTmj = null;
        $deltaTp = null;
        $deltaToutflow = null;
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
        return round(($this->x), 3);
    }
    //valve opening area calculations
    public function valveOpeningArea(){
        $d = ($this->r - (($this->r * 2 - $this->x) / 2)) / 360;
        $e = acos($d) * 180 / Pi();
        $c = Pi() * pow($this->r, 2) * 2;
        $g = $this->r - ((2 * $this->r - $this->x) / 2);
        $h = sqrt(pow($this->r, 2) - pow($this->r - (2 * $this->r - $this->x) / 2, 2));
        $af = ($e * $c) - ($g * $h);
        return round($af, 3);
    }
    //delta P calculations
    public function calculateDeltaP(){
        if (!is_numeric($this->deltaP)) {
            $this->deltaP = $this->maxDeltaP;
            return $this->deltaP;
        } else {
            $this->deltaP = $this->deltaP * 0.677;
            return round(($this->deltaP), 3);
        }
    }
    // Upstream Pressure at valve (Pk) calculations
    public function calculatePk($deltap){
        $pk =  $this->pi + $deltap;
        return round($pk, 3);
    }
    //density calculations
    public function calculateDensity($deltap,$pk){
        $p = ($deltap * pow(10, 5) * $this->m)/(8.3142 * (273 + $this->t * $this->pi/$pk));
        return round($p, 3);
    }
    //Internal Area of Pipe (apipe) calculations
    public function calculatePipeArea($r){
        $apipe = (pi() * pow(2 * $r, 2)) / 4;
        return round($apipe, 3);
    }

    //Volumetric flow across (ValveQ(t)) calculations
    public function calculateQ($areaF, $cd, $deltap, $p){
        $q = $cd * $areaF * pow(10, -6) * (sqrt((2* $deltap)/$p));
        return round($q, 3);
    }

    //Fluid velocity (u) calculations
    public function calculateU($q, $areaF){
        $u = $q / ($areaF * pow(10, -6));
        return round($u, 3);
    }

    //celerity (c) calculations
    public function calculateCelerity($deltap, $density, $fluid){
        if($density != 0) {
            $c = $deltap / ($density * $fluid);
        }else{
            $c = 0;
        }
        return round($c, 3);
    }

    //time of closure (delta T) calculations
    public function calculateDeltaT($celerity, $fluid, $density, $deltaPprevious, $deltaTprevious)
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
        return round($deltaT, 3);
    }

    //calculating the time for valve closure (needed for the graphs)
    public function calculateTime($deltat){
        if($deltat==0)
            $this->time = 0;
        else
            $this->time = $deltat+$this->time;
        return round(($this->time), 3);
    }














                        //    **********Started here on 15 Jan **********

    //function to calculate the Qmj (mass flow rate @ rupture)
    public function calculateQmj($r, $deltap){
        $qmj =  $this->calculatePipeArea($r) * pow(10, -6) * 0.98 * sqrt($this->calculatePk($deltap) *
                $this->calculateDensity($deltap, $this->calculatePk($deltap)) * 1.29);
        return round($qmj, 3);
    }

    //function to calculate the Qj (volumetric flow @ rupture)
    public function calculateQj($r, $deltap){
        $qj = $this->calculateQmj($r, $deltap)/$this->calculateDensity($deltap, $this->calculatePk($deltap));
        return round($qj, 5);
    }

    //function to calculate the Qrk (volumetric flow @ rupture)    //not working at the moment
    public function calculateQrk($areaF, $cd, $fluid){
        $qrk = $areaF * pow(10, -6) * (1-$cd) * $fluid;
        return round($qrk, 5);
    }

    //function to calculate the total Qoutflow (m3/s)
    public function calculateTotalQoutflow($qj, $qrk)
    {
        $tQoutflow = $qj + $qrk;
        return round($tQoutflow, 5);
    }

    //function to calculate the cumulative Qoutflow (m3/s)
    public function calculateCumulativeQoutflow($tOutflow){
        if(is_null($this->qOutflow)){
            $this->qOutflow = $tOutflow;
        }else{
            $this->qOutflow = $tOutflow + $this->qOutflow;
        }
        return round(($this->qOutflow), 5);
    }

    //function to calculate the cumulative Qoutflow (SCFM)
    public function calculateQscfm($qoutflow){
        $qSCFM = $qoutflow * 2119;
        return round($qSCFM, 3);
    }

    //function to calculate Radius Ratio D
    public function calculateRadiusRatio(){
        $radiusRatio = (2*$this->r)/50;
        return round($radiusRatio, 3);
    }

    //function to calculate the time decay constant (alfa)
    public function calculateAlfa($radiusRatio, $areaRatio){
        $alfa = pow($radiusRatio, 0.25) * (0.22 * $areaRatio - 0.13 *
                pow($areaRatio, 1.5) + 0.00068 * ($this->t - 15));
        return round($alfa, 3);
//        =(RR^0.25*(0.22*A-0.13*A^1.5+0.00068*(M-15)))
    }

    //function to calculate Qmj/Qmj-1 --RowE
    public function calculateRowE($qmj, $qmjPrev){
        $rowE = $qmj/$qmjPrev;
        return round($rowE, 5);
    }

    //function to calculate deltaP/deltaP-1 --RowF
    public function calculateRowF($deltap, $pk){
        $rowF = $deltap/($pk);
        return round($rowF, 3);
    }

    //function to calculate Qi/Qi-1 --RowG
    public function calculateRowG($tq, $tqPrev){
        $rowG = $tq/$tqPrev;
        return round($rowG, 3);
    }

    //function to calculate tmj
    public function calculateTmj($rowE, $alfa){
        $tmj = log($rowE/$alfa) * ($this->r/75);
        return round($tmj, 3);
    }

    //function to calculate deltaTmj
    public function calculateDeltaTmj($tmj){
//        $tmj = $this->calculateTmj($rowE, $alfa);
        if (!is_null($this->deltaTmj)) {
            $this->deltaTmj = $tmj + $this->deltaTmj;
        } else {
            $this->deltaTmj = $tmj;
        }
        return round(($this->deltaTmj), 3);
    }

    //function to calculate tp
    public function calculateTp($rowF, $alfa){
        $tp = log($rowF/$alfa) * ($this->r/75);
        return round($tp, 3);
    }

    //function to calculate deltaTp
    public function calculateDeltaTp($tp){
        if (!is_null($this->deltaTp)) {
            $this->deltaTp = $tp + $this->deltaTp;
        } else {
            $this->deltaTp = $tp;
        }
        return round(($this->deltaTp), 3);
    }

    //function to calculate tOutflow
    public function calculateToutflow($rowG, $alfa){
        $tOutflow = log($rowG/$alfa) * ($this->r/75);
        return $tOutflow;
    }

    //function to calculate deltaToutflow
    public function calculateDeltaToutflow($tOutflow){
        if (!is_null($this->deltaToutflow)) {
            $this->deltaToutflow = $tOutflow + $this->deltaToutflow;
        } else {
            $this->deltaToutflow = $tOutflow;
        }
        return round(($this->deltaToutflow), 3);
    }

    //function to calculate qj --row N
    public function calculateRowN($qj, $alfa, $deltaTmj){
        $rowN = $qj * exp(-$alfa * $deltaTmj);
        return $rowN;
    }

    //function to calculate volumetric outflow after closure (qac)
    public function calculateQac($tqOutflow, $alfa, $tOutflow){
        $qac = $tqOutflow * exp(-$alfa * $tOutflow);
        return $qac;
    }

    //function to calculate cumulative pressure
    public function calculateCumulativePressure($deltap){
        $cumulativePressure = $deltap;
        return $cumulativePressure;
    }

    //function to calculate deltaP time decay pressure
    public function calculateDeltaPtimeDecayPressure($pk, $alfa, $tp){
        $deltaPDecayP = $pk * exp(-$alfa * $tp);
        return $deltaPDecayP;
    }

}




$calculator = new CalculatorS2(75, 35, 0.0195, 20, 205);
$cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);
$r = 75;
$areaRatio = 1;
for($i=0;$i<11;$i++) {
    //echo $c;
    // echo $u;
    //echo $p;

    $deltaPprevious =$deltaP;
    $deltaTprevious = $deltaTtest;
    $deltaP = $calculator->calculateDeltaP() .'</br>';   //$maxDeltaP, $m, $t, $pi, $pk
    $deltaParray []= $deltaP;
    $pk = $calculator->calculatePk($deltaP) .'</br>';  //$pi, $deltap, $m, $t
    $p = $calculator->calculateDensity($deltaP, $pk) .'</br>';   //$deltap, $m, $t, $pi, $pk
    $pArray [] = $p;
    $x = $calculator->calculateX() .'</br>'; //$r, $cd, $deltap, $density, $deltaT
    $af = $calculator->valveOpeningArea() .'</br>'; //$r, $x, $cd, $deltap, $density, $deltaT
    $q = $calculator->calculateQ($af, $cd[$i], $deltaP, $p) .'</br>';  //$areaF, $cd, $deltap, $density, $deltaT, $r
    $qArray[] = $q;
    $u = $calculator->calculateU($q, $af) .'</br>';  //$q, $areaF, $deltap, $density, $deltaT, $r
    $uArray []= $u;
    $c = $calculator->calculateCelerity($deltaP, $p, $u) .'</br>';  //$deltap, $density, $fluid, $deltaT, $r
    $cArray []= $c;
    //$deltaPprevious=null;

    $deltaTtest = $calculator->calculateDeltaT($c, $u,$p, $deltaPprevious,$deltaTprevious).'</br>';
    $time = $calculator->calculateTime($deltaTtest) .'</br>';






                    //    *****testing starts here -- 15 January*****
                    // *****continue second table testing functions******
        $qmj = $calculator->calculateQmj($r, $deltaP) .'</br>';
        $qmjArray [] = $qmj;
        $qj = $calculator->calculateQj($r, $deltaP) . '</br>';
        $qrk = $calculator->calculateQrk($af, $cd[$i], $u) .'</br>';
        $tQoutflow = $calculator->calculateTotalQoutflow($qj, $qrk) .'</br>';
        $tQoutflowArray [] = $tQoutflow;
        $qOutflow = $calculator->calculateCumulativeQoutflow($tQoutflow) .'</br>';
        $qSCFM = $calculator->calculateQscfm($qOutflow) .'</br>';







            //        *****third table on the spreadsheet calculation testing*****
        $radiusRatio = $calculator->calculateRadiusRatio() .'</br>';
        $alfa = $calculator->calculateAlfa($radiusRatio, $areaRatio) .'</br>';


         if ($qmjPrev != null){      //IMPORTANT hard-code the last value to be 0
            $rowE = $calculator->calculateRowE($qmj, $qmjPrev) . '</br>';
         }else{
             'do nothing';
         }
         $qmjPrev = $qmj;

         $rowF = $calculator->calculateRowF($deltaP, $pk) .'</br>';

//         $rowG = $calculator->calculateRowG($tQoutflowArray[--$i], $tQoutflowArray[++$i]) .'</br>';
         if ($tQoutflowPrev != null){      //IMPORTANT hard-code the last value to be 0
             $rowG = $calculator->calculateRowG($tQoutflow, $tQoutflowPrev) . '</br>';
         }else{
             'do nothing';
         }
         $tQoutflowPrev = $tQoutflow;


        $tmj = $calculator->calculateTmj($rowE, $alfa) .'</br>';  //partially working because of the $rowE
        $deltaTmj = $calculator->calculateDeltaTmj($tmj) .'</br>';  //partially working because of the $rowE

        $tp = $calculator->calculateTp($rowF, $alfa) .'</br>';
        $deltaTp = $calculator->calculateDeltaTp($tp) .'</br>';
        $tOutflow = $calculator->calculateToutflow($rowG, $alfa) .'</br>';
        $deltaToutflow = $calculator->calculateDeltaToutflow($tOutflow) .'</br>';  //not working??
        $rowN = $calculator->calculateRowN($qj, $alfa, $deltaTmj) .'</br>';
        $qac = $calculator->calculateQac($tQoutflow, $alfa, $tOutflow) .'</br>';
        $cumulativePressure = $calculator->calculateCumulativePressure($deltaP) .'</br>';
        $deltaPdecayP = $calculator->calculateDeltaPtimeDecayPressure($pk, $alfa, $tp) .'</br>';

}

