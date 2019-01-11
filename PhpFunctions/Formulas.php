<?php
error_reporting(0);

    $R = 75;
    $T = 35;
    $M = 0.0195;
    $Pi = 20;
    $deltaP = null;
    $maxDeltaP= 205;
    $X = 0;
    $Cd = array(1, 0.9, 0.75, 0.58, 0.4, 0.3, 0.2, 0.13, 0.08, 0.04, 0);
//    $Cd = 1;
    $deltaT = null;

for ($counter=0; $counter<11; $counter++) {
        echo '</br>';
        $D = ($R - (($R * 2 - $X) / 2)) / 360;
        $E = acos($D) * 180 / Pi(); // to the power of -1
        $C = Pi() * pow($R, 2) * 2;
        $G = $R - ((2 * $R - $X) / 2);
        $H = sqrt(pow($R, 2) - pow($R - (2 * $R - $X) / 2, 2));
        echo "Af: " . $Af = ($E * $C) - ($G * $H).'</br>';   //function for this is written
//        echo '</br>';

        if (!is_numeric($deltaP)) {  //function for this value is written
            echo 'Delta P max is: '. $deltaP = $maxDeltaP;
            echo '</br>';
        }else{
            echo 'Delta P is: '. $deltaP = $deltaP * 0.677;
            echo '</br>';
        }


        $Pk =  $Pi + $deltaP;   //function for this is written

//        echo "Density:   " .
            echo "p:" . $p = ($deltaP * pow(10, 5) * $M)/(8.3142 * (273 + $T * $Pi/$Pk)).'</br>';   //function for this is written
//        echo "Density:   ".$p = ($deltaP * pow(10, 5) * $M)/(8.3142 * (273 + $T)).'</br>';


        echo "Area Pipe: ". $Apipe = (pi()*pow(2*$R, 2))/4 ."<br>";  //function for this is written

//            echo $a = $Pk * $p * 1.29;
//            echo 'Qmj:'. $Qmj = $Apipe * pow(10, -6) * 0.98 * (sqrt(($Pk * $p * 1.29))) . '</br>';
//            $a = $Pk * $p * 1.29;
//            echo sqrt($a);
//                              =(D29*10^-6*0.98*                   SQRT(H29*C29*1.29))

//            echo "Qj is: ". $Qj = $Qmj / $p . '</br>';

            //Q is volumetric flow across valve
            foreach ($Cd as $value) {
                $Q = $value * $Af * pow(10, -6) * (sqrt((2* $deltaP)/$p));
                echo "Q: " . (round($Q, 5)) .'</br>';
            }

//            echo "Q: " . $Q = $Cd * $Af * pow(10, -6) * (sqrt((2* $deltaP)/$p)).'</br>';   //function for this written


            // u is fluid velocity
                $u = $Q /( $Af * pow(10, -6) ). '</br>';    //function for this written
                echo "u:" . (round($u, 5)) . '</br>';


            //c is for celerity
            if($u != 0) {    //function for this is written
                echo "celerity: " .$c = $deltaP / ($p * $u) . '</br>';
            }else{
                echo "celerity: " .$c = 0;
            }

            //time of closure deltaT
            if($c == 0 || $u == 0) {    //function for this written
                echo "Delta t:". $deltaT = $deltaT/2 .'</br>';
            } else if(is_numeric($deltaP)) {
                    echo "t:".$deltaT = (($c*$p*$u)/$deltaP) .'</br>';
                } else {
                echo "Delta T:".$deltaT = 0 * ($R / 75) . '</br>';
                echo '</br>';
            }


            $X = ($R / 5) + $X;
}