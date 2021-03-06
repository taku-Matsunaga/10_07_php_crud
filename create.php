<?php
// var_dump($_POST);
// exit();

if(
  !isset($_POST['video']) || $_POST['video'] == '' ||
  !isset($_POST['thumb']) || $_POST['thumb'] == '' ||
  !isset($_POST['title']) || $_POST['title'] == ''
){
  exit('ParamError');
}

$video = $_POST['video'];
$thumb = $_POST['thumb'];
$title = $_POST['title'];

// DB接続情報
$dbn = 'mysql:dbname=gsacf_f04_07;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = 'root';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// SQL作成&実行
$sql = 'INSERT INTO video_table(id, video, thumb, title, created_at, updated_at) VALUES(NULL, :video, :thumb, :title,  sysdate(), sysdate())';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':video', $video, PDO::PARAM_STR);
$stmt->bindValue(':thumb', $thumb, PDO::PARAM_STR);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$status = $stmt->execute(); // SQLを実行

// 失敗時にエラーを出力し，成功時は登録画面に戻る
if ($status==false) {
  $error = $stmt->errorInfo();
  // データ登録失敗次にエラーを表示
  exit('sqlError:'.$error[2]);
  } else {
  // 登録ページへ移動
  header('Location:index.php');
  }