<?php
session_start();
require_once 'FindBookByIdUseCase.php';
require_once dirname(__DIR__, 3) . '/src/repository/BookRepository.php';

$book = findBookById($_GET['id']);
if (is_null($book)) {
  header('Location: /seiran/view/error/404.php');
  exit;
}
if ($book->getUserId() != $_SESSION["user"]["id"]) {
  header('Location: /seiran/view/error/403.php');
  exit;
}

$thumbnail = $_FILES['thumbnail'];
if (!is_uploaded_file($thumbnail['tmp_name'])) {
  $_SESSION['error_message'] = 'アイコン:ファイルがアップロードされていません。';
  header('Location: /seiran/view/book/edit_detail.php?id=' . $_GET['id'] . '#thumbnail');
  exit;
}

// アップロードされたファイルが画像であることを確認する
$image_info = getimagesize($thumbnail['tmp_name']);
if ($image_info === false) {
  $_SESSION['error_message'] = 'アイコン:ファイルが画像ではありません。';
  header('Location: /seiran/view/book/edit_detail.php?id=' . $_GET['id'] . '#thumbnail');
  exit;
}

// アップロードされたファイルが許容されるファイル形式であることを確認する
$allowed_image_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP);
$detected_image_type = $image_info[2];
if (!in_array($detected_image_type, $allowed_image_types)) {
    $_SESSION['error_message'] = "アイコン:許容されるファイル形式はJPG、JPEG、PNG、GIF、BMPのみです。";
    header('Location: /seiran/view/book/edit_detail.php?id=' . $_GET['id'] . '#thumbnail');
    exit;
}

// アップロードされたファイルが許容されるサイズであることを確認する
$max_file_size = 5000000; // 5MB
if ($image_info[0] > 2000 || $image_info[1] > 2000 || ($image_info[0] / 3) != ($image_info[1] / 4)) {
    $_SESSION['error_message'] = "アイコン:画像の幅と高さは2000px以下で、アスペクト比は3:4である必要があります。";
    header('Location: /seiran/view/book/edit_detail.php?id=' . $_GET['id'] . '#thumbnail');
    exit;
}
if ($thumbnail["size"] > $max_file_size) {
  $_SESSION['error_message'] = "アイコン:ファイルサイズが大きすぎます。5MB以下のファイルをアップロードしてください。";
  header('Location: /seiran/view/book/edit_detail.php?id=' . $_GET['id'] . '#thumbnail');
    exit;
}

if (!empty($thumbnail)) {
  $filename = uniqid() . '.' . pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
  $thumbnailPath = dirname(__DIR__, 3) . '/assets/img/book/' . $filename;

  $book->setThumbnailPath($filename);
  move_uploaded_file($thumbnail['tmp_name'], $thumbnailPath);
}

$bookRepository = new BookRepository();
$book->setName($_POST['title']);
$book->setDescription($_POST['description']);
$book->setPrice($_POST['price']);
$book->setCategoryId($_POST['category']);
$bookRepository->update($book);

header('Location: /seiran/view/book/show.php?id=' . $_GET['id']);
?>