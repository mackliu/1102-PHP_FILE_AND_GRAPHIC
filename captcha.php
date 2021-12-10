<?php 


/* $c=rand(65,90);
echo chr($c)."<BR>"
122,97;48,57
echo "A=>".ord('A'); */


/**
 * 1. 英文大小寫及數字的組合
 * 2. 每次產生的字串在4~8字元之間
 * 3. 每次產生的排列順序，不固定
 */

$str="";
$length=rand(4,8);
 for($i=0;$i<$length;$i++){
 $type=rand(1,3);
 //echo "type=>".$type."<br>";
 switch($type){
    case 1:
    //大寫英文
    $str.=chr(rand(65,90));
    break;
    case 2:
    //小寫寫英文
    $str.=chr(rand(97,122));
    break;
    case 3:
    //數字
    $str.=chr(rand(48,57));
    break;
 }
}
 echo $str;

 $padding=10;

 $fontBox=imagettfbbox(30,30,realpath('./font/arial.ttf'),$str);

 $x_array=[$fontBox[0],$fontBox[2],$fontBox[4],$fontBox[6]];
 $y_array=[$fontBox[1],$fontBox[3],$fontBox[5],$fontBox[7]];

 $fw=(max($x_array)-min($x_array));
 $fh=(max($y_array)-min($y_array));

 $w=$fw+$padding;
 $h=$fh+$padding;

 $dstimg=imagecreatetruecolor($w,$h);

 $white=imagecolorallocate($dstimg,200,200,180);
 $black=imagecolorallocate($dstimg,0,0,0);
 
 imagefill($dstimg,0,0,$white);


$start_x=$padding/2+(0-min($x_array));
$start_y=($padding/2)+$fh-max($y_array);
imagettftext($dstimg,30,30,$start_x,$start_y,$black,realpath('./font/arial.ttf'),$str);


imagepng($dstimg,'captcha.png');
?>
<p><img src="captcha.png" alt=""></p>
<?php
echo "<br>w=>".$w."<br>";
echo "h=>".$h;
echo "<pre>";
print_r($fontBox);
echo "</pre>";
?>
