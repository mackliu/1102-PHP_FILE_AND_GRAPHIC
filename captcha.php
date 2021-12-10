<?php 


/* $c=rand(65,90);
 * echo chr($c)."<BR>" 使用chr()來把數字轉換為ASCII對應的符號
 * echo "A=>".ord('A'); 使用ord()來把符號轉換為ASCII對應的數字
 */

/**
 * 驗證碼的內容原則
 * 1. 英文大小寫及數字的組合
 * 2. 每次產生的字串在4~8字元之間
 * 3. 每次產生的排列順序，不固定
 */

$str="";   //先宣告一個空字串
$length=rand(4,8);  //使用亂數產生一個4~8的數字

//使用迴圈來產生對應$length長度的驗證碼
 for($i=0;$i<$length;$i++){
 
//使用亂數來決定要產生大小寫英文字母還是數字
 $type=rand(1,3);
 
 //使用switch來切換要產生的字串,每次迴圈都會把字串接續串起來
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
 //echo $str;

 $padding=10;  //設定圖形驗證碼周圍的內距寬度

 //使用imagettfbbox來取得整個字串的四個點坐標資訊
 $fontBox=imagettfbbox(30,30,realpath('./font/arial.ttf'),$str);

 //把四個x點的坐標及四個y點的坐標分別放入兩個陣列中
 $x_array=[$fontBox[0],$fontBox[2],$fontBox[4],$fontBox[6]];
 $y_array=[$fontBox[1],$fontBox[3],$fontBox[5],$fontBox[7]];

 //使用坐標值的最大值及最小值來計算字型的寬和高
 $fw=(max($x_array)-min($x_array));
 $fh=(max($y_array)-min($y_array));
 
 //把字型的寬高加上內距形成完整的整張圖片需要的寬和高
 $w=$fw+$padding;
 $h=$fh+$padding;

 //建立一個全彩的圖形資源
 $dstimg=imagecreatetruecolor($w,$h);

 //建立色彩資源這裏建立的是土黃和黑色
 $white=imagecolorallocate($dstimg,200,200,180);
 $black=imagecolorallocate($dstimg,0,0,0);
 
 //將底色先填入底圖中
 imagefill($dstimg,0,0,$white);

//計算整個字型要放在目標圖形資源的位置
$start_x=$padding/2+(0-min($x_array));
$start_y=($padding/2)+$fh-max($y_array);

//依照上面計算的結果將參數放入imagettftext函式中
imagettftext($dstimg,30,30,$start_x,$start_y,$black,realpath('./font/arial.ttf'),$str);

//將圖形資源輸出成為驗證碼圖片
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
