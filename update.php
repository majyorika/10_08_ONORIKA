<?php
include('functions.php');

//入力チェック(受信確認処理追加)
if (
    !isset($_POST['namae']) || $_POST['namae']=='' ||
    !isset($_POST['kana']) || $_POST['kana']==''// ||
    // !isset($_POST['gender']) || $_POST['gender']=='' ||
    // !isset($_POST['birth']) || $_POST['birth']=='' ||
    // !isset($_POST['add1']) || $_POST['add1']=='' ||
    // !isset($_POST['phone']) || $_POST['phone']=='' ||
    // !isset($_POST['mail']) || $_POST['mail']=='' ||
    // !isset($_POST['rank']) || $_POST['rank']==''
) {
    exit('ParamError');
}

//1. POSTデータ取得
$id = $_POST['id'];
$namae = $_POST['namae'];
$kana = $_POST['kana'];
$gender = $_POST['gender'];
$birth = $_POST['birth'];
$youfrom = $_POST['youfrom'];
$postcode = $_POST['postcode'];
$add1 = $_POST['add1'];
$add2 = $_POST['add2'];
$phone = $_POST['phone'];
$tels = $_POST['tels'];
$mail = $_POST['mail'];
$mail_flg = $_POST['mail_flg'];
$belong = $_POST['belong'];
$mynum = $_POST['mynum'];
$mem_flg = $_POST['mem_flg'];
$grp = $_POST['grp'];
$comment = $_POST['comment'];

// Fileアップロードチェック
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] ==0) {
    // ファイルをアップロードしたときの処理
    // ①送信ファイルの情報取得
    $file_name = $_FILES['upfile']['name']; //ファイル名
    $tmp_path = $_FILES['upfile']['tmp_name']; //tmpフォルダ
    $file_dir_path = 'upload/'; //アップロード先

    // ②File名の準備
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $uniq_name = date('YmdHis').md5(session_id()) . "." . $extension;
    $file_name = $file_dir_path.$uniq_name;
    
    // ③サーバの保存領域に移動&④表示
    if (is_uploaded_file($tmp_path)) {
    if (move_uploaded_file($tmp_path, $file_name)) {
        chmod($file_name, 0644);
    $img = '<img src="'. $file_name . '" >';
    } else {
        exit('Error:アップロードできませんでした.');
    } 
}
}else{
    exit('画像が送信されていません');
}                                                                                                                                                                                                                                                                                                                           

//2. DB接続します(エラー処理追加)
$pdo = db_conn();


//3．データ登録SQL作成
$sql = 'UPDATE staff_list SET namae=:a1, kana=:a2, gender=:a3, birth=:a4, youfrom=:a5, postcode=:a6, add1=:a7, add2=:a8, phone=:a9, tels=:a10, mail=:a11, mail_flg=:a12, belong=:a13, mynum=:a14, mem_flg=:a15, grp=:a16, comment=:a17, image=:image, indate= sysdate() WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $namae, PDO::PARAM_STR);
$stmt->bindValue(':a2', $kana, PDO::PARAM_STR);
$stmt->bindValue(':a3', $gender, PDO::PARAM_STR);
$stmt->bindValue(':a4', $birth, PDO::PARAM_STR);
$stmt->bindValue(':a5', $youfrom, PDO::PARAM_STR);
$stmt->bindValue(':a6', $postcode, PDO::PARAM_INT);
$stmt->bindValue(':a7', $add1, PDO::PARAM_STR);
$stmt->bindValue(':a8', $add2, PDO::PARAM_STR);
$stmt->bindValue(':a9', $phone, PDO::PARAM_STR);
$stmt->bindValue(':a10', $tels, PDO::PARAM_STR);
$stmt->bindValue(':a11', $mail, PDO::PARAM_STR);
$stmt->bindValue(':a12', $mail_flg, PDO::PARAM_STR);
$stmt->bindValue(':a13', $belong, PDO::PARAM_STR);
$stmt->bindValue(':a14', $mynum, PDO::PARAM_STR);
$stmt->bindValue(':a15', $mem_flg, PDO::PARAM_STR);
$stmt->bindValue(':a16', $grp, PDO::PARAM_STR);
$stmt->bindValue(':a17', $comment, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':image', $file_name, PDO::PARAM_STR);
$status = $stmt->execute();

//4．データ登録処理後
if ($status==false) {
    errorMsg($stmt);
} else {
    header('Location: select.php');
    exit;
}
