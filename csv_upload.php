<?php
//$_FILES['file']['error']==0
if(!empty($_FILES['csv']['tmp_name'])){
    $filename=md5(time());
    /* $subname=$_FILES['file']['name'];
    $subname=explode(".",$subname); */

    $subname=explode(".",$_FILES['csv']['name'])[1];

    $newFileName=$filename.".".$subname;

    echo "new=>".$newFileName."<br>";
    echo "tmp_name=>".$_FILES['csv']['tmp_name']."<br>";
    echo "fileOrignName=>".$_FILES['csv']['name']."<br>";
    
    move_uploaded_file($_FILES['csv']['tmp_name'],"file/".$newFileName);

    //echo "<a href='file/{$newFileName}'>{$_FILES['csv']['name']}</a>";
    if($subname=='txt' || $subname=="csv"){
        saveToDB("file/".$newFileName);
    }
}



function saveToDB($file){
    echo "得到檔案".$file."<br>";
    echo "準備進行資料處理作業.....";

    $resource=fopen($file,'a+');
    while(!feof($resource)){
        echo fgets($resource)."<br>";
    
    }

    fclose($resource);
}

?>