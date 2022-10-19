<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>m5-1</title>
    </head>
    
    <body>
        <?php
        // DB接続設定
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
        // テーブル作成 
            $sql = "CREATE TABLE IF NOT EXISTS tb5_1"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32) NOT NULL,"
            . "comment TEXT NOT NULL,"
            . "create_at TEXT NOT NULL,"
            . "pass char(32) NOT NULL"
            .");";
            $stmt = $pdo->query($sql);
            
            if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){   
                if(!empty($_POST["now_edit_num"])){
                // 編集投稿
                    $id = $_POST["now_edit_num"];
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $create_at = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];
                    
                    $sql = 'UPDATE tb5_1 SET name=:name,comment=:comment,create_at=:create_at,pass=:pass WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':create_at', $create_at, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                }else{
                // 通常投稿
                    $sql = $pdo -> prepare("INSERT INTO tb5_1 (name, comment, create_at, pass) VALUES (:name, :comment, :create_at, :pass)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':create_at', $create_at, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $create_at = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];
                    $sql -> execute();
                }
            }else if(!empty($_POST["edit_num"])){
                // 編集選択
                $edit_num = $_POST["edit_num"];
                $edit_pass = $_POST["edit_pass"];
                
                $sql = 'SELECT * FROM tb5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    $id =  $row['id'];
                    $pass = $row["pass"];
                    
                    if ($id == $edit_num && $pass == $edit_pass){
                        $name_get = $row["name"];
                        $comment_get = $row["comment"];
                    }      
                }
            }else if(!empty($_POST["delete_num"])){
                // 投稿削除
                $delete_num = $_POST["delete_num"];
                $delete_pass = $_POST["delete_pass"];
                
                $sql = 'SELECT * FROM tb5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    $id =  $row['id'];
                    $pass = $row["pass"];
                    
                    if ($id == $delete_num && $pass == $delete_pass){
                        $sql = 'delete from tb5_1 where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                    }      
                }
            }
        ?>
        
        <p>この掲示板のテーマ「直近に食べたもの」</p>
        <form action = "" method = "post">
            <input type = "text" name = "name" placeholder = "名前" value="<?php if(isset($name_get)){echo $name_get;} ?>"><br/>
            <input type = "text" name = "comment" placeholder = "コメント" value="<?php if(isset($comment_get)){echo $comment_get;} ?>"><br/>
            <input type = "text" name = "pass" placeholder = "パスワード"><br/>
            <input type = "submit" name = "submit" value = "送信"><br/>
            <input type = "text" name = "delete_num" placeholder = "削除対象番号"><br/>
            <input type = "text" name = "delete_pass" placeholder = "パスワード"><br/>
            <input type = "submit" name = "delete_btn" value ="削除"><br/>
            <input type = "text" name = "edit_num" placeholder = "編集対象番号"><br/>
            <input type = "text" name = "edit_pass" placeholder = "パスワード"><br/>
            <input type = "submit" name = "edit_btn" value ="編集"><br/>
            <input type = "hidden" name="now_edit_num" placeholder = "編集中番号" value="<?php if(isset($edit_num)){echo $edit_num;} ?>"><br/>
        </form>
            
        <?php
        // データベースの内容を表示
            $sql = 'SELECT * FROM tb5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['create_at'].',';
                echo $row['pass'].'<br>';
                echo "<hr>";
            }
        
        ?>
    </body>
</html>