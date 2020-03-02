<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>mission_5-1</title>
  <script>
    function show1(e){ //編集パスワードの可視化
      var a = document.getElementById("edit_pass");
      if(e.checked){
        a.setAttribute("type","text");
      }
      else
        a.setAttribute("type","password");
    }

    function show2(e){ //投稿パスワードの可視化
      var a = document.getElementById("post_pass");
      if(e.checked){
        a.setAttribute("type","text");
      }
      else
        a.setAttribute("type","password");
    }

    function show3(e){ //削除パスワードの可視化
      var a = document.getElementById("delete_pass");
      if(e.checked){
        a.setAttribute("type","text");
      }
      else
        a.setAttribute("type","password");
    }
  </script>
</head>
<body>
  
  <?php
    $flag = 0; //正しく情報を発信できたのか判定をするための変数
    $pass = "";
    $dsn = 'mysql:dbname=******db;host=localhost'; // ***** の部分は自分のIDを入力してください
    $user = '******'; //***** の部分は自分のIDを入力してください
    $password = '*******'; // ******の部分は自分のパスワードを入力してください
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
	  . "id INT AUTO_INCREMENT PRIMARY KEY,"
	  . "name char(32),"
    . "comment TEXT"
	  .");";
    $stmt = $pdo->query($sql);  //queryメソッドは引数に指定したSQL文をデータベースに対して実行する

  ?>

  <p style="height:50px; width: 350px;"></p>

  <form method="POST">
    <p style = "color: blue;">【編集フォーム】</p>
    <p> 　　編集番号: 　<input type="text" value="" name="edit"> </p>
    <p> 　　パスワード: <input id = "edit_pass" type="password" value="" name="edit_pass"> <input type="submit" value="編集"> </p>
    <p> <input type="checkbox" onclick="show1(this)">パスワードを可視化する</p>

    <?php 
      $name = "";
      $comm = "";
      $edit_number = 0;
      if(!empty($_POST["edit"]) && strcmp($pass,$_POST["edit_pass"]) == 0){
        $flag = 1;
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        //$edit_number = $_POST["edit"];
        foreach ($results as $row){
          if($row['id'] == $_POST["edit"]){
            $edit_number = $_POST["edit"];
            $name = $row['name'];
            $comm = $row['comment'];
          }
        }    
      }
    ?>

    <?php if( $flag == 1): ?>
      <h2 style="position:absolute; top:0px;">正しく送信されました</h2>
    <?php endif; ?>

    <p style = "color: blue;">【投稿フォーム】</p>
    <p> <input type="hidden" value="<?php if($edit_number != 0) echo $edit_number; ?>" name = "judge"> </p>
    <p> 　　名前: 　　　<input type="text" value="<?php echo $name; ?>" name="name"> </p>
    <p> 　　コメント:　 <input type="text" value="<?php echo $comm; ?>" name="comment"> </p>
    <p> 　　パスワード: <input  id = "post_pass" type = "password" value="" name="comme_pass"> <input type="submit" value="送信"> </p>
    <p> <input type="checkbox" onclick="show2(this)">パスワードを可視化する</p>

    <p style = "color: blue;">【削除フォーム】</p>
    <p> 　　削除番号:　 <input type="text" value="" name="delete"> </p>
    <p> 　　パスワード: <input id = "delete_pass" type="password" name="delete_pass"> <input type="submit" value="削除"> </p>
    <p> <input type="checkbox" onclick="show3(this)">パスワードを可視化する</p>
    
  </form>  

  <p style = "color: blue; ">【投稿一覧】</p>
  <p><hr></p>

  <?php

    $flag = 0;
    if(!empty($_POST["judge"]) && strcmp($pass, $_POST["comme_pass"]) == 0){
      $flag = 1;
      $id = $_POST["judge"];
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $sql = 'update tbtest set id=:id,name=:name,comment=:comment where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }
    elseif(!empty($_POST["delete"]) && strcmp($pass, $_POST["delete_pass"]) == 0){
      $flag = 1;
      $id = $_POST["delete"];
      $sql = 'delete from tbtest where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }
    
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["judge"]) && strcmp($pass,$_POST["comme_pass"]) == 0){
      $flag = 1;
      $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $name = $_POST["name"];
      $comment = $_POST["comment"]; 
      $sql -> execute();
    }
  ?>

  <?php if(!empty($_POST["edit_pass"]) && strcmp($pass,$_POST["edit_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">編集パスワードが違います</h2>

  <?php elseif(!empty($_POST["comme_pass"]) && strcmp($pass,$_POST["comme_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">コメントパスワードが違います</h2>

  <?php elseif(!empty($_POST["delete_pass"]) && strcmp($pass,$_POST["delete_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">削除パスワードが違います</h2>
  
  <?php elseif( $flag == 1): ?>
    <h2 style="position:absolute; top:0px;">正しく送信されました</h2>

  <?php endif; ?>

  <?php

    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row)
      echo "{$row['id']}, {$row['name']}, {$row['comment']}.<br>";

  ?>
</body>
</html>
