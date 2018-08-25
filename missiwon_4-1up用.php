<?php
$e_data1 = NULL;
$e_data2 = NULL;
$e_data3 = NULL;
date_default_timezone_set('Japan');
try{

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

	if (isset($_POST['送信'])){
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$date = date("Y-m-d H:i:s");
		$password = $_POST['sendpass'];
		if ($name !== '' && $comment !== '' && $password !== ''){
			$statement = $pdo -> prepare("INSERT INTO userdata (name,comment,date,password) VALUES (:name,:comment,:date,:password)");
            			$statement -> bindParam(':name', $name, PDO::PARAM_STR);
            			$statement -> bindParam(':comment', $comment, PDO::PARAM_STR);
            			$statement -> bindParam(':date', $date, PDO::PARAM_STR);
            			$statement -> bindParam(':password', $password, PDO::PARAM_STR);
            			$statement -> execute();

		                $status_send = 'コメントありがとうございます。';
            			$number = $pdo -> lastInsertId();
            			$row_add = array(
                			"id" => $number,
               				 "name" => $name,
                			"comment" => $comment,
                			"date" => $date,
                			"password" => $password
						);
		}
		else {
			if($name === ''){
				$noname = '名前を入力してください。';
			}
			if($comment === ''){
				$nocomment = 'コメントを入力してください。';
			}
			if ($sendpass === ''){
				$nopass = 'パスワードを設定してください。';
			}
		}
	}
	elseif (isset($_POST['削除'])){
		$number = $_POST['delete'];
		$delpass = $_POST['delpass'];
		if ($number !== '' && $delpass !== ''){
			$statement = "SELECT password FROM userdata WHERE id = $number";
			$results = $pdo -> query($statement);
			foreach ($results as $row){
				}
				if ($delpass === $row['password']){
					$delsql = "DELETE FROM userdata WHERE id = $number";
					$result = $pdo -> query($delsql);
					$delok = '投稿を削除しました。';
				}
				else{
				$dlpassNG = 'パスワードが正しくありません。';
				}
			}
		
		else{
			if ($number === ''){
				$nodlnum = '削除したい投稿№をいれてください';
			}
			if ($delpass === ''){
				$nodelpass = 'パスワードを入れてください。';
			}
		}
	}
	elseif (isset($_POST['編集'])){
		if (! empty($_POST['edit'])){
			$number = $_POST['edit'];
			$name = $_POST['name'];
			$comment = $_POST['comment'];
			$date = date("Y-m=d H:i:s");
			$password = $_POST['editpass'];
				if ($name !== '' && $comment !== '' && $password !== ''){
					$statement = $pdo -> prepare("UPDATE userdata SET name=:name, comment=:comment, date=:date WHERE id = $number");
					$statement -> bindParam(':name', $name, PDO::PARAM_STR);
					$statement -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$statement -> bindParam(':date', $date, PDO::PARAM_STR);
					$statement -> execute();

					$editOK = '投稿を編集しました。';
				}
				else{
					if ($name === ''){
						$nameng = '名前を入力してください。';
					}
					if ($comment === ''){
						$comng = 'コメントを入力してください。';
					}
					if ($password === ''){
						$passng = 'パスワードを入力してください。';
					}
				}
		}
		else{
			$number = $_POST['number'];
			$password = $_POST['editpass'];
				if ($number !== '' && $password !== ''){
					$statement = "SELECT name, comment, password FROM userdata WHERE id = $number";
					$result = $pdo -> query($statement);
					foreach($result as $row){
						}
					if ($password === $row["password"]){
						$e_data1 = $number;
						$e_data2 = $row["name"];
						$e_data3 = $row["password"];
					}
					else{
						$editpassng = 'パスワードが違います。';
						}
				}
				else{
					if ($number === ''){
						$no_number = '編集番号を記入してください。';
					}
					if ($password === ''){
						$no_pass = 'パスワードを記入してください。';
					}
				}
		}
						
	}
// データベースのデータを配列$dataに格納
$statement = "SELECT * FROM userdata";
$result = $pdo -> query($statement);
foreach ($result as $val){
        echo $val['id'].' '.$val['name'].' '.$val['comment'].' '.$val['date'].'<br>';
}

			

}catch (PDOException $e) {

    // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    // もし手抜きしたくない場合は普通にHTMLの表示を継続する
    // ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());

}
?>








<!DOCTYPE html>
<htmllng = "ja">
<meta charset="UTF-8">
 <body>
        <form action="mission_4-1.php" method="post">
        	名前:<br />
        	<input type="text" name="name" value ="<?php echo $e_data2; ?>"/><br />
        
        	コメント:<br />
        	<textarea name="comment" cols="50" rows="5"><?php echo $e_data3; ?></textarea><br />
        	<br />
<?php if (is_null($e_data1)): ?>
		<input type= "password" name="sendpass" placeholder="pass" /><br />
		<button type="submit" name="送信" value="b1">送信</button>
		<br />編集番号:<br />
		<input type="text" name="number" value=""/><br />
		<input type="password" name="editpass" placeholder="editpass"/>
<?php else:?>
		<input type="text" name="edit" value="<?php echo $e_data1; ?>">
<?php endif; ?><br />
		<button type="submit" name="編集" value="b3">編集する</button><br />
	</form>
 </body>
</html>   
<!DOCTYPE html>
<htmllng = "ja">
	<body>
	<br/>
		削除:削除したい投稿番号を記入してください<br/>
		<form action="mission_4-1.php" method="post">
		<input type="text" name="delete" value=""/>
		<br />
		<input type="password" name="delpass" placeholder="delpass"/>
		<button type="submit" name="削除" value="b2">削除</button>
		</form>
	</body>
</html>

