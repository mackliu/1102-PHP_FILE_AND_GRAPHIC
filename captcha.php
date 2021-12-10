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

 //指定一個圖形大小
 $dstimg=imagecreatetruecolor(200,50);
 $white=imagecolorallocate($dstimg,200,200,180);
 $black=imagecolorallocate($dstimg,0,0,0);
 imagefill($dstimg,0,0,$white);

 //使用迴圈的方把字串中的每一個字元獨立寫入圖形資源中
 for($i=0;$i<$length;$i++){
     $c=mb_substr($str,$i,1);
     
     //使用GD內建的字形來寫入圖形資源,字形最大到5點，寫入坐標使用亂數方式來產生
     imagestring($dstimg,5,(10+$i*rand(15,20)),(10+rand(0,10)),$c,$black);

 }


 imagepng($dstimg,'captcha.png');

?>

<img src="captcha.png" alt="">