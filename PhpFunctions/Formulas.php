<?php

//class Formulas
//{
    $R = 75;
    $T = 35;
    $M = 0.0195;
    $Pi = 20;
    $deltaP = null;
    $maxDeltaP= 205;
    $X = 0;

//    public function densityOfFluid(){
//        $result =(($deltaP * pow(10, 5) * $M)/(8.3142 * (273 + $T)));
//        echo $p;
//    }
//    if(is_numeric($x)){
//        $x = $R/5 + $x;
//    }else{
//        $x = 0;
//    }
for ($counter=0; $counter<11; $counter++) {
        $X = ($R / 5) + $X;
        $D = ($R - (($R * 2 - $X) / 2)) / 360;
        $E = acos($D) * 180 / Pi(); // to the power of -1
        $C = Pi() * pow($R, 2) * 2;
        $G = $R - ((2 * $R - $X) / 2);
        $H = sqrt(pow($R, 2) - pow($R - (2 * $R - $X) / 2, 2));
        $Af = ($E * $C) - ($G * $H);

        if (!is_numeric($deltaP)) {
           // echo "Delta P max is:     ".
            $deltaP = $maxDeltaP;
        } else {
//            echo "Delta P is:    ".
            $deltaP = $deltaP * 0.677;
        }
        $Pk =  $Pi + $deltaP;

        echo "Density:   ".$p = ($deltaP * pow(10, 5) * $M)/(8.3142 * (273 + $T * $Pi/$Pk)).'</br>';
//        echo "Density:   ".$p = ($deltaP * pow(10, 5) * $M)/(8.3142 * (273 + $T)).'</br>';


//        echo "Area Pipe ". $Apipe = (pi() * 2 * pow($R, 2)/4) . '</br>';
//        $Qmj =

    }
//        $Q = $Af * $X * $u;
//        $u = $Q/($Af * pow(10, -6));


//}