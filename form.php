<html>
  <body>

  <?php 
    $filename = "mission_3-5.txt";
    $fp = fopen($filename, "a");
    $comments = array();
    $password = "techbase";
  ?>

  <p style="height:50px; background-color:#fff;"></p>

  <form method="POST">
    <p> 編集番号: <input type="text" value="" name="edit"> </p>
    <p> パスワード: <input type="text" value="" name="edit_pass"> <input type="submit" value="編集"> </p>

    <?php 
      $name = "";
      $comm = "";
      $edit_number = 0;
      if(!empty($_POST["edit"])){
        $comments = file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
        foreach($comments as $comme){
          $split = explode("<>",$comme);
          if($_POST["edit"] == $split[0] && strcmp($password, $_POST["edit_pass"]) == 0){
            $edit_number = $split[0];
            $name = $split[1];
            $comm = $split[2];
          }
        }
      }
    ?>

    <p> <input type="hidden" value="<?php if($edit_number != 0) echo $edit_number; ?>" name = "judge"> </p>
    <p> 名前: <input type="text" value="<?php echo $name; ?>" name="name"> </p>
    <p> コメント: <input type="text" value="<?php echo $comm; ?>" name="comment"> </p>
    <p> パスワード: <input type="text" value="" name="comme_pass"> <input type="submit" value="送信"> </p>

    <p> 削除番号: <input type="text" value="" name="delete"> </p>
    <p> パスワード: <input type="text" name="delete_pass"> <input type="submit" value="削除"> </p>
  
  </form>  

  <?php 
      
      if(!empty($_POST["judge"]) && strcmp($password, $_POST["comme_pass"]) == 0){
      $comments = $comments = file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
      foreach($comments as $comme){
        $split = explode("<>",$comme);
        if($_POST["judge"] == $split[0]){
          $order = ( $_POST["judge"] - 1 );
          $when = date('Y年m月d日 H時i分s秒')."にコメントを受け付けました<br/>";
          $comments[$order] = $_POST["judge"]. "<>". $_POST["name"]. "<>". $_POST["comment"]. "<>". $when; 
        }
      }

      ftruncate($fp,0);
      fseek($fp, 0, SEEK_SET);

      foreach($comments as $comme){
          fwrite($fp,  $comme.PHP_EOL);  
      }
      
    }
    elseif(!empty($_POST["delete"]) && strcmp($password, $_POST["delete_pass"]) == 0){
      $comments = $comments = file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 

      ftruncate($fp, 0);
      fseek($fp, 0, SEEK_SET); 
      
      foreach($comments as $comme){
        $split = explode("<>",$comme);
        $cnt = $comme[0];
        if($_POST["delete"] != $cnt)
          fwrite($fp,  $comme.PHP_EOL);  
      }
        
    }
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["judge"]) && strcmp($password,$_POST["comme_pass"]) == 0){
      $cnt = count( file( $filename ) ) + 1;
      $na = $_POST["name"];
      $com = $_POST["comment"];
      $when = date('Y年m月d日 H時i分s秒')."にコメントを受け付けました<br/>";
      $comment = $cnt. "<>". $na. "<>". $com. "<>". $when; 
      
      fwrite($fp,  $comment.PHP_EOL);
    }
  ?>

  <!--ここから一番上に間違いメッセージを表示する-->

  <?php if(!empty($_POST["edit_pass"]) && strcmp($password,$_POST["edit_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">パスワードが違います</h2>

  <?php elseif(!empty($_POST["comme_pass"]) && strcmp($password,$_POST["comme_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">パスワードが違います</h2>

  <?php elseif(!empty($_POST["delete_pass"]) && strcmp($password,$_POST["delete_pass"]) > 0 ): ?>
    <h2 style="position:absolute; top:0px; color: red;">パスワードが違います</h2>

  <?php else: ?>
    <h2 style="position:absolute; top:0px; color: red;">入力されていない箇所があります</h2>

  <?php endif; ?>

  <!--ここまで-->

  <?php
    $comments = file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
       
    fclose($fp);

    foreach($comments as $comme){
      $split = explode("<>",$comme);
      for($i = 0; $i < count($split); $i++)
        echo $split[$i]." ";
    }

  ?>

  </body>
</html>
