<?php
$e_data1 = NULL;
$e_data2 = NULL;
$e_data3 = NULL;
date_default_timezone_set('Japan');
try{

$dsn = '�f�[�^�x�[�X��';
$user = '���[�U�[��';
$password = '�p�X���[�h';
$pdo = new PDO($dsn,$user,$password);

	if (isset($_POST['���M'])){
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

		                $status_send = '�R�����g���肪�Ƃ��������܂��B';
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
				$noname = '���O����͂��Ă��������B';
			}
			if($comment === ''){
				$nocomment = '�R�����g����͂��Ă��������B';
			}
			if ($sendpass === ''){
				$nopass = '�p�X���[�h��ݒ肵�Ă��������B';
			}
		}
	}
	elseif (isset($_POST['�폜'])){
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
					$delok = '���e���폜���܂����B';
				}
				else{
				$dlpassNG = '�p�X���[�h������������܂���B';
				}
			}
		
		else{
			if ($number === ''){
				$nodlnum = '�폜���������e��������Ă�������';
			}
			if ($delpass === ''){
				$nodelpass = '�p�X���[�h�����Ă��������B';
			}
		}
	}
	elseif (isset($_POST['�ҏW'])){
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

					$editOK = '���e��ҏW���܂����B';
				}
				else{
					if ($name === ''){
						$nameng = '���O����͂��Ă��������B';
					}
					if ($comment === ''){
						$comng = '�R�����g����͂��Ă��������B';
					}
					if ($password === ''){
						$passng = '�p�X���[�h����͂��Ă��������B';
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
						$editpassng = '�p�X���[�h���Ⴂ�܂��B';
						}
				}
				else{
					if ($number === ''){
						$no_number = '�ҏW�ԍ����L�����Ă��������B';
					}
					if ($password === ''){
						$no_pass = '�p�X���[�h���L�����Ă��������B';
					}
				}
		}
						
	}
// �f�[�^�x�[�X�̃f�[�^��z��$data�Ɋi�[
$statement = "SELECT * FROM userdata";
$result = $pdo -> query($statement);
foreach ($result as $val){
        echo $val['id'].' '.$val['name'].' '.$val['comment'].' '.$val['date'].'<br>';
}

			

}catch (PDOException $e) {

    // �G���[�����������ꍇ�́u500 Internal Server Error�v�Ńe�L�X�g�Ƃ��ĕ\�����ďI������
    // �����蔲���������Ȃ��ꍇ�͕��ʂ�HTML�̕\�����p������
    // �����ł̓G���[���e��\�����Ă��邪�C ���ۂ̏��p���ł̓��O�t�@�C���ɋL�^���āC Web�u���E�U�ɂ͏o���Ȃ��ق����]�܂���
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());

}
?>








<!DOCTYPE html>
<htmllng = "ja">
<meta charset="UTF-8">
 <body>
        <form action="mission_4-1.php" method="post">
        	���O:<br />
        	<input type="text" name="name" value ="<?php echo $e_data2; ?>"/><br />
        
        	�R�����g:<br />
        	<textarea name="comment" cols="50" rows="5"><?php echo $e_data3; ?></textarea><br />
        	<br />
<?php if (is_null($e_data1)): ?>
		<input type= "password" name="sendpass" placeholder="pass" /><br />
		<button type="submit" name="���M" value="b1">���M</button>
		<br />�ҏW�ԍ�:<br />
		<input type="text" name="number" value=""/><br />
		<input type="password" name="editpass" placeholder="editpass"/>
<?php else:?>
		<input type="text" name="edit" value="<?php echo $e_data1; ?>">
<?php endif; ?><br />
		<button type="submit" name="�ҏW" value="b3">�ҏW����</button><br />
	</form>
 </body>
</html>   
<!DOCTYPE html>
<htmllng = "ja">
	<body>
	<br/>
		�폜:�폜���������e�ԍ����L�����Ă�������<br/>
		<form action="mission_4-1.php" method="post">
		<input type="text" name="delete" value=""/>
		<br />
		<input type="password" name="delpass" placeholder="delpass"/>
		<button type="submit" name="�폜" value="b2">�폜</button>
		</form>
	</body>
</html>

