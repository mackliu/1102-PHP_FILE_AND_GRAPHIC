<?php
/****
 * 1.建立資料庫及資料表
 * 2.建立上傳圖案機制
 * 3.取得圖檔資源
 * 4.進行圖形處理
 *   ->圖形縮放
 *   ->圖形加邊框
 *   ->圖形驗證碼
 * 5.輸出檔案
 */


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>驗證碼處理練習</title>
    <style>

    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!----產生圖形驗證碼----->
<h2>圖形驗證碼</h2>
<hr>
<?php
$str=captcha(4);
$img=cpatchapic($str);
session_start();
if(isset($_POST['ans'])){
    echo "你輸入的驗證碼為:".$_POST['ans'];
    echo "<br>";
    echo "要比對的驗證碼內容為:".$str;
    echo "<br>";
    echo "原始的驗證碼內容為:".$_POST['src'];
    if($_POST['ans']==$_SESSION['ans']){
        echo "你輸入的驗證碼正確";
    }else{        
        echo "你輸入的驗證碼錯誤";
    }
}

$_SESSION['ans']=$str;

?>
<form action="?" method="post">
    <?="<img src='$img'>";?>
    <input type="text" name="ans" >
    <input type="hidden" name="src" value="<?=$str;?>">
    <input type="submit" value="送出">
</form>





<?php
function captcha($n){
    $str="";
    for($i=0;$i<$n;$i++){
        $type=rand(1,3);
        switch($type){
            case 1:
                $str=$str.chr(rand(48,57));
            break;
            case 2:
                $str=$str.chr(rand(97,122));
            break;
            case 3:
                $str=$str.chr(rand(65,90));
            break;
        }  
    }
    return $str;
}






//產生底圖
function cpatchapic($str){
$fontsize=24;
$padding=10;
$fontlist=['arial.ttf','arialbd.ttf','arialbi.ttf'];
$fontpath=realpath("./font/{$fontlist[0]}");
$ttbox=imagettfbbox($fontsize,0,$fontpath,$str);
/* print_r($ttbox); */
$w=$ttbox[2]-$ttbox[0]+($padding*2)+(mb_strlen($str)*10);
$h=$ttbox[1]-$ttbox[7]+($padding*2)+10;

$base_img=imagecreatetruecolor($w,$h);
$color=imagecolorallocate($base_img,rand(200,255),rand(200,255),rand(200,255));
imagefill($base_img,0,0,$color);

/* echo $fontpath; */




$x=15;
$y=15;
for($i=0;$i<strlen($str);$i++){

    $fontcolor=imagecolorallocate($base_img,rand(0,150),rand(0,150),rand(0,150));
    $char=mb_substr($str,$i,1);
    $angle=rand(30,-30);
    $ttbox=imagettfbbox($fontsize,$angle,$fontpath,$char);
    //print_r($ttbox);
    $tw=$ttbox[2]-$ttbox[0];
    $th=$ttbox[1]-$ttbox[7];
    $yz=$y+$th+rand(5,-5);
    imagettftext($base_img,$fontsize,$angle,$x,$yz,$fontcolor,$fontpath,$char);
    $x=$x+$tw+10;
    //imagestring($base_img,1,$x,20,$char,$fontcolor);
}

//產生隨機亂數線條
$lines=rand(3,7);

for($i=0;$i<$lines;$i++){
    $linecolor=imagecolorallocate($base_img,rand(50,200),rand(50,200),rand(50,200));
    imageline($base_img,rand(0,0+$padding),rand(0,$h),rand($w,$w-$padding),rand(0,$h),$linecolor);
}


$dst_path="./captcha/base_img.png";
imagepng($base_img,$dst_path);
return $dst_path;
}

?>
</body>
</html>