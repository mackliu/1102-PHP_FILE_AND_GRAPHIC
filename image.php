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

if(isset($_FILES['img']['tmp_name'])){
    move_uploaded_file($_FILES['img']['tmp_name'] , 'img/'.$_FILES['img']['name']);
    
    switch($_FILES['img']['type']){
        case "image/jpeg":
            $srcimg=imagecreatefromjpeg('img/'.$_FILES['img']['name']);
        break;
        case "image/png":
            $srcimg=imagecreatefrompng('img/'.$_FILES['img']['name']);
        break;
        case "image/gif":
            $srcimg=imagecreatefromgif('img/'.$_FILES['img']['name']);
        break;
        case "image/bmp":
            $srcimg=imagecreatefrombmp('img/'.$_FILES['img']['name']);
        break;
    }

    $info=getimagesize('img/'.$_FILES['img']['name']);
    $scaleRate=$_POST['rate'];
    
    //目標檔案大小
    $dwidth=$info[0]*$scaleRate;
    $dheight=$info[1]*$scaleRate;

    if(isset($_POST['border'])){
        $border=ceil(($dwidth*0.1)/2);
        
    }else{
        $border=0;

    }
    
    //內嵌圖片大小
    $inner_w=$dwidth-($border*2);
    $inner_h=$dheight-($border*2);

    $dstimg=imagecreatetruecolor($dwidth,$dheight);
    $white=imagecolorallocate($dstimg,200,200,150);
    imagefill($dstimg,0,0,$white);
    /* imagecopyresampled($dstimg,$srcimg,0,0,0,0,240,180,799,532);
    $filename='img/'.explode(".",$_FILES['img']['name'])[0]."_small.png";
    imagepng($dstimg,$filename); */
   
    imagecopyresampled($dstimg,$srcimg,$border,$border,0,0,$inner_w,$inner_h,$info[0],$info[1]);
    $filename='img/'.explode(".",$_FILES['img']['name'])[0]."_border.png";
    imagepng($dstimg,$filename);

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>文字檔案匯入</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1 class="header">圖形處理練習</h1>
<!---建立檔案上傳機制--->
<form action="?" method="post" enctype="multipart/form-data">
    <input type="checkbox" name="border" value="1">是否有邊框<br>
    <select name="rate" >
        <option value="0.25">縮小四分之一</option>
        <option value="0.5">縮小一半</option>
        <option value="2">放大2倍</option>
    </select>
     <p><input type="file" name="img" ></p>
     <p><input type="submit" value="上傳"></p>
</form>



<!----縮放圖形----->
<?php 
if(isset($_FILES['img']['name'])){
 ?>
 <div>你上傳的圖片為:</div>
 <img src='img/<?=$_FILES['img']['name'];?>'>
 <div>縮放後成為:</div>
 <img src='<?=$filename;?>'>

 <?php
}
?>

<!----圖形加邊框----->


<!----產生圖形驗證碼----->



</body>
</html>