
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
<form method='POST' action=''>
    掲示板のテーマ：好きな食べ物<br>
    お名前：<input  id="n" type='text' name='su'>
    コメント：<input id="k" type='text' name='ko'>
    パスワード1：<input id="pass" type='text' name='psw'>
    <input id="a" type='hidden' name='b'><br>
    <input type='submit' name='submit' value='送信'>
</form>

<form method='POST' action=''>
    削除：<input type='text' name='bye'>
    パスワード2：<input id="pass2" type='text' name='psw2'><br>
    <input type="submit" name="submit" value="削除" />
</form>

<form method='POST' action=''>
    編集番号：<input type='text' name='h'>
    パスワード3：<input id="pass3" type='text' name='psw3'><br>
    <input type="submit" name="submit" value="編集" />    
    </form>
    
<?php error_reporting(E_ALL&~E_NOTICE);?>

<?php

$na=$_POST["su"];
$com=$_POST["ko"];
$str=$_POST["psw"];
$str5=$_POST["b"];

$bye=$_POST["bye"];
$str2=$_POST["psw2"];

$he=$_POST["h"];
$str3=$_POST["psw3"];
$S=date('Y-m-d H:i:s');

// DB接続設定
$dsn = 'mysql:dbname=***;host=localhost';
$user = '***';
$password = '***';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS form"." (". "id INT AUTO_INCREMENT PRIMARY KEY,". "name char(32),". "comment TEXT,". "date TEXT,". "pass TEXT". ");";
$stmt = $pdo->query($sql);
$sql = $pdo -> prepare("INSERT INTO form (name,comment,date,pass) VALUES (:name, :comment,:date,:pass)");

#######################################################################################################
    if (!function_exists('is_nullorempty')) {
        function is_nullorempty($obj){
            if($obj === 0 || $obj === "0"){
                return false;
            }
            return empty($obj);
        }
    }

    if (!function_exists('is_nullorwhitespace')) {
        function is_nullorwhitespace($obj){
            if(is_nullorempty($obj) === true){
                return true;
            }

            if(is_string($obj) && mb_ereg_match("^(\s|　)+$", $obj)){
                return true;
            }
            return false;
        }
    }

    function strbool($value){
        return $value ? 'true' : 'false';
    }
//https://qiita.com/hirossyi73/items/6e6b9b3ff155a8b05075

//ナンバーいらない  
//入力フォーム##################################################################
//書き込むのは番号，名前，コメント，日付，編集番号


if (strbool(is_nullorempty($na))) {}
elseif(strbool(is_nullorempty($com))) {}
elseif(strbool(is_nullorempty($str))){echo "パスワードを入れて再入力してください"."<br>";}
else {
    if(strbool(is_nullorempty($str5))){
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

        $name = $na;
        $comment =$com; 
        $date=$S;
        $pass=$str;
        $sql -> execute();   //クエリの実行　値評価？
    }
    else{
        $id = $str5; //変更する投稿番号
        $name = $na;
        $comment =$com; 
        $date=$S;
        $pass=$str;
        
        $sql = 'UPDATE form SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
        $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
}}
//削除フォーム###################################################################

if (!isset($bye)) {}
else {
    if(!isset($str2)) {
        echo "パスワードを入れて再入力してください"."<br>";
    }
    else{
        $sql = 'SELECT * FROM form';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            $id = $bye;
            if($row['pass']==$str2){

            $sql = 'delete from form where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();}}
          
    }}
//編集フォーム##################################################################  
if (!isset($he) ) {}
else{
    if(!isset($str3)) {
        echo "パスワードを入れて再入力してください"."<br>";
    }
    else{ 
        
        $id = $he; //変更する投稿番号
        $sql = 'SELECT * FROM form';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['id']==$id){
                if($str3==$row['pass']){
                $h=$id;
                $n=$row['name'];
                $c=$row['comment'];
                $p=$row['pass'];
                ?>
                
                 <script>
                //https://mebee.info/2020/08/11/post-16443/
                document.getElementById("n").value = "<?php echo $n; ?>"
                document.getElementById("k").value = "<?php echo $c; ?>"
                document.getElementById("pass").value = "<?php echo $p; ?>"
                document.getElementById("a").value = "<?php echo $h; ?>"
                </script>

<?php }}}}}
//##############################################################################
//表示

$sql = 'SELECT * FROM form';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].' ';
    echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['date'].'<br>';
echo "<hr>";
}

//#############################################################################

?>

</body>
</html>