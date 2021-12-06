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
    echo "準備進行資料處理作業.....<br>";

    $dsn="mysql:host=localhost;charset=utf8;dbname=file_uploade";
    $pdo=new PDO($dsn,'root','');

    $resource=fopen($file,'r+');
    //fwrite($resource,"0,candy,女,1\r\n");
    $count=0;
    $success=0;
    while(!feof($resource)){
        $str=explode(",",fgets($resource));
        echo "<pre>";
        print_r($str);
        echo "</pre>";
        if($count>0 && count($str)==4){
            $sql="INSERT INTO `users` (`num`, `name`, `gender`, `status`) 
                       VALUES ('{$str[0]}', '{$str[1]}', '{$str[2]}', '{$str[3]}')";
                //INSERT INTO `users` (`num`, `name`, `gender`, `status`) VALUES (NULL, '', '', '')
            $pdo->exec($sql);
            echo "<br>己經寫入了".implode(",",$str)."到資料表<br>";
            $success++;
        }
        $count++;
    }

    fclose($resource);

    echo "<br>一共處理了".($count)."筆資料<br>";
    echo "<br>總共成功寫入了了".($success)."筆資料<br>";
}

?>