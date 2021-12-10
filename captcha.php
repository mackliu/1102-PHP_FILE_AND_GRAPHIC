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

 $padding=20;  //設定圖形驗證碼周圍的內距寬度

 $fontBox=[];  //宣告一個空陣列來存放每一個字元的資訊
 $font_w=0; //宣告一個變數來紀錄文字圖片的寬度
 $font_h=0; //宣告一個變數來紀錄文字圖片的高度

 $angle=0;  //宣告一個角度的變數

//使用迴圈及imagettfbbox來取得每一個字串的四個點坐標資訊
for($i=0;$i<$length;$i++){
   
   //取出單一字元
   $s=mb_substr($str,$i,1);

   //利用亂數來取得一個角度值
   $angle=rand(-45,45);

   //取得單一字元的四角坐標資訊
   $tmp=imagettfbbox(30,$angle,realpath('./font/arial.ttf'),$s);

   //把四個x點的坐標及四個y點的坐標分別放入兩個陣列中
   $x=[$tmp[0],$tmp[2],$tmp[4],$tmp[6]];
   $y=[$tmp[1],$tmp[3],$tmp[5],$tmp[7]];

   //利用一個陣列來存放每個字元的字型資訊,包括長寬及最大最小x,y值
   //1.計算單一字元的長寬並存入該字元代表的陣列中
   $fontBox[$s]['width']=max($x)-min($x);
   $fontBox[$s]['height']=max($y)-min($y);

   //將計算出來的寬隨著迴圈累加上去
   $font_w+=$fontBox[$s]['width'];
   
   //2.將x,y的最大最小值也存入陣列中,用來計算最後要畫的位置
   $fontBox[$s]['max_x']=max($x);
   $fontBox[$s]['min_x']=min($x);
   $fontBox[$s]['max_y']=max($y);
   $fontBox[$s]['min_y']=min($y);
   //3.將該字元的角度資訊也寫入陣列中,實際寫入圖形時需要使用一樣的角度設定
   $fontBox[$s]['angle']=$angle;

   //將每一個字元的最大和最小y坐標放入一個暫存的陣列中
   $tmp_h[]=max($y);
   $tmp_h[]=min($y);
}

   //圖片至少需要多少高度的算法是把所有字元的高度坐標放進暫存陣列中,
   //然後取最大和最小值來進行計算
   $font_h=max($tmp_h)-min($tmp_h);
 
 //把字型的寬高加上內距形成完整的整張圖片需要的寬和高
 $width=$font_w+$padding;
 $height=$font_h+$padding;

 //建立一個全彩的圖形資源
 $dstimg=imagecreatetruecolor($width,$height);

 //建立彩色圖形資源，這裏建立的是淺灰色和黑色
 $white=imagecolorallocate($dstimg,230,230,230);
 $black=imagecolorallocate($dstimg,0,0,0);
 
 //將底色先填入底圖中
 imagefill($dstimg,0,0,$white);

 //起始寫入的點坐標先以內距的一半為起始點
 $start_x=$padding/2; 
 $start_y=$padding/2;

 //利用迴圈和前面計算及準備的各項資訊，實際寫入到圖形資源中
for($i=0;$i<$length;$i++){
   //取出單一字元
   $s=mb_substr($str,$i,1);

   //計算每個字完實際寫入的坐標點，依據每個字元取得最大最小值來判斷
   $start_x=$start_x-$fontBox[$s]['min_x'];

   /*由於字形的整體高度是由所有字元的個別高度最大最小值來計算的
    *因此實際上單一字元可能剛好或小於整體高度,如果統一由最高點
    *來計算y坐標，會形成每個字元的y點位置差不多的狀況
    *因此可以計算單一字元的高度和整體高的差，做一些高度上的變化*/
   $y_diff=$font_h-$fontBox[$s]['height'];

   //利用亂數得到一個高度差的值
   $y_gap=rand(0,$y_diff);

   //在預定繪製的y坐標加上高度差
   $start_y=$start_y-$fontBox[$s]['min_y']+$y_gap;

   //依照上面計算的結果將參數放入imagettftext函式中
   imagettftext($dstimg,30,$fontBox[$s]['angle'],$start_x,$start_y,$black,realpath('./font/arial.ttf'),$s);

   //將start_x的最大x值累加上去,做為下一個字元計算的起點
   $start_x=$start_x+$fontBox[$s]['max_x'];

   //將start_x的高度還原為預設的值;
   $start_y=$padding/2;

}



//將圖形資源輸出成為驗證碼圖片
imagepng($dstimg,'captcha.png');
?>
<p><img src="captcha.png" alt=""></p>
<?php
echo "字元數:".$length."<br>";
echo "字型加總寬度:".$font_w."<br>";
echo "字型高度:".$font_h."<br>";
echo "圖片寬度:".$width."<br>";
echo "圖片高度:".$height."<br>";
echo "圖片內距:".($padding/2)."<br>";
echo "字型轉角:正負45度";
echo "<pre>";
print_r($fontBox);
echo "</pre>";
?>
