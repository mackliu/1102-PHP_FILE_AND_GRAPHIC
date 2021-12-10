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
 $str="ggyy";
 $padding=0;
 $fontBox=imagettfbbox(30,0,'f:/file/font/arial.ttf',$str);
 $tw=$padding+($fontBox[2]-$fontBox[0]);
 $th=$padding+($fontBox[1]-$fontBox[7]);
$x=$tw;
$y=$th;
 $dstimg=imagecreatetruecolor($tw,$th);
 $white=imagecolorallocate($dstimg,200,200,180);
 $black=imagecolorallocate($dstimg,0,0,0);
 imagefill($dstimg,0,0,$white);

echo "<br>w=>".$tw."<br>";
echo "h=>".$th;
$start_x=$padding/2;
$start_y=($padding/2)+($fontBox[1]-$fontBox[7]);
imagettftext($dstimg,30,0,$start_x,$start_y,$black,'f:/file/font/arial.ttf',$str);
echo "<pre>";
print_r($fontBox);
echo "</pre>";




 imagepng($dstimg,'captcha.png');

?>

<img src="captcha.png" alt="" 
     >