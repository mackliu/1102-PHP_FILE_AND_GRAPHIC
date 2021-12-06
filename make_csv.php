<?php
$dsn="mysql:host=localhost;charset=utf8;dbname=file_uploade";
$pdo=new PDO($dsn,'root','');

if(isset($_GET['do'])){
    switch($_GET['do']){
        case 1:
            $rows=$pdo->query("select * from users where status='2'")->fetchAll();
            
        break;
        case 2:
            $rows=$pdo->query("select * from users where status='1'")->fetchAll();
        break;
        case 3:
            $rows=$pdo->query("select * from users where status='0'")->fetchAll();
        break;
    }
    $file=fopen('result.csv','w+');
}else{

    $rows=$pdo->query("select * from users")->fetchAll();
}


echo "<ul>";
foreach($rows as $key => $row){
    echo "<li>";    
    echo $row[0].",".$row[1].",".$row[2].",".$row[3];
    echo "</li>";
    fwrite($file,$row[0].",".$row[1].",".$row[2].",".$row[3]."\r\n");

}
echo "</ul>";

fclose($file);
?>

<a href="?do=1">下載己施打2劑的名單</a>&nbsp;&nbsp;
<a href="?do=2">下載己施打1劑的名單</a>&nbsp;&nbsp;
<a href="?do=3">未施打的名單</a>
<?php

if(file_exists('result.csv')){
    echo "<a href='result.csv' download>下載檔案</a>";
}

?>