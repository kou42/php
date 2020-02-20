<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>db_form</title>
</head>
<body>
  
  <?php
    $pass = "********";
    $dsn = 'mysql:dbname=********db;host=localhost';
    $user = '********';
    $password = '********';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT"
    .");";
    $stmt = $pdo->query($sql);  //queryメソッドは引数に指定したSQL文をデータベースに対して実行する

  ?>

  <p style="height:50px; background-color:#fff;"></p>

  <form method="POST">
    <p style = "color: blue;">［編集フォーム］</p>
    <p> 　編集番号: 　<input type="text" value="" name="edit"> </p>
    <p> 　パスワード: <input type="password" value="" name="edit_pass"> <input type="submit" value="編集"> </p>

    <?php 
      $name = "";
      $comm = "";
      $edit_number = 0;
      if(!empty($_POST["edit"])){
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
          if($row['id'] == $_POST["edit"]){
            $edit_number = $_POST["edit"];
            $name = $row['name'];
            $comm = $row['comment'];
          }
        }
          
      }
    ?>

    <p style = "color: blue;">［投稿フォーム］</p>
    <p> <input type="hidden" value="<?php if($edit_number != 0) echo $edit_number; ?>" name = "judge"> </p>
    <p> 　名前: 　　　<input type="text" value="<?php echo $name; ?>" name="name"> </p>
    <p> 　コメント:　 <input type="text" value="<?php echo $comm; ?>" name="comment"> </p>
    <p> 　パスワード: <input type="password" value="" name="comme_pass"> <input type="submit" value="送信"> </p>

    <p style = "color: blue;">［削除フォーム］</p>
    <p> 　削除番号:　 <input type="text" value="" name="delete"> </p>
    <p> 　パスワード: <input type="password" name="delete_pass"> <input type="submit" value="削除"> </p>
    
  </form>  

  <?php

    if(!empty($_POST["judge"]) && strcmp($pass, $_POST["comme_pass"]) == 0){
      //echo "編集in<br/>";
      $id = $_POST["judge"];
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      //$when = date('Y年m月d日 H時i分s秒')."にコメントを受け付けました<br/>";
      $sql = 'update tbtest set name=:name,comment=:comment where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      //$stmt->bindParam(':when', $when, PDO::PARAM_STR);
      $stmt->execute();
    }
    elseif(!empty($_POST["delete"]) && strcmp($pass, $_POST["delete_pass"]) == 0){
      //echo "削除in<br/>";
      $id = $_POST["delete"];
      $sql = 'delete from tbtest where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }
    
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["judge"]) && strcmp($pass,$_POST["comme_pass"]) == 0){
      //echo "新規投稿in<br/>";
      $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      //$sql -> bindParam(':when', $when, PDO::PARAM_STR);
      $name = $_POST["name"];
      $comment = $_POST["comment"]; 
      //$when = "date('Y年m月d日 H時i分s秒')"."にコメントを受け付けました<br/>";
      $sql -> execute();
    }
  ?>

  <?php if(!empty($_POST["edit_pass"]) && strcmp($pass,$_POST["edit_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">編集パスワードが違います</h2>

  <?php elseif(!empty($_POST["comme_pass"]) && strcmp($pass,$_POST["comme_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">コメントパスワードが違います</h2>

  <?php elseif(!empty($_POST["delete_pass"]) && strcmp($pass,$_POST["delete_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">削除パスワードが違います</h2>

  <?php elseif(empty($_POST["edit_pass"]) || empty($_POST["comme_pass"]) || empty($_POST["delete_pass"])): ?>
    <h2 style="position:absolute; top:0px; color: red;">入力されていない箇所があります</h2>

  <?php endif; ?>

  <?php

    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
      echo "{$row['id']}, {$row['name']}, {$row['comment']}.<br>";
      echo "<hr>";
    }

  ?>

</body>
</html>
