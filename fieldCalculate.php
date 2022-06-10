<?php



class FieldCalculate {
    function calculate($equation)
    {
        $result = 0;
        // sanitize imput
        $equation = preg_replace("/[^a-z0-9+\-.*\/()%]/","",$equation);
        // convert alphabet to $variabel
        $equation = preg_replace("/([a-z])+/i", "\$$0", $equation);
        // convert percentages to decimal
        $equation = preg_replace("/([+-])([0-9]{1})(%)/","*(1\$1.0\$2)",$equation);
        $equation = preg_replace("/([+-])([0-9]+)(%)/","*(1\$1.\$2)",$equation);
        $equation = preg_replace("/([0-9]{1})(%)/",".0\$1",$equation);
        $equation = preg_replace("/([0-9]+)(%)/",".\$1",$equation);
        if ( $equation != "" ){
            $result = @eval("return " . $equation . ";" );
        }
        if ($result == null) {
            throw new Exception("Unable to calculate equation");
        }
        echo $result;
        // return $equation;
    }


$a = 2;
$b = 3;
$c = 5;
$f1 = "a*b+c";

$f1 = str_replace("a", $a, $f1);
$f1 = str_replace("b", $b, $f1);
$f1 = str_replace("c", $c, $f1);
}