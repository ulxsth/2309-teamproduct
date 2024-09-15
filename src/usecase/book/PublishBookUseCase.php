<?php
require_once dirname(__FILE__, 3) . "/repository/BookRepository.php";

$repository = new BookRepository();
$book = $repository->findById($_POST['id'], true);
if (is_null($book)) {
    header("Location: /seiran/view/error/404.php");
    exit;
}

$thumbnail = $_FILES['thumbnail'];
if (!is_uploaded_file($thumbnail['tmp_name'])) {
    $_SESSION['error_message'] = 'アイコン:ファイルがアップロードされていません。';
    header("Location: /seiran/view/book/input_detail.php?id=" . $book->getId());
    exit;
}

$image_info = getimagesize($thumbnail['tmp_name']);
if ($image_info === false) {
    $_SESSION['error_message'] = 'アイコン:ファイルが画像ではありません。';
    header("Location: /seiran/view/book/input_detail.php?id=" . $book->getId());
    exit;
}

$allowed_image_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP);
$detected_image_type = $image_info[2];
if (!in_array($detected_image_type, $allowed_image_types)) {
    $_SESSION['error_message'] = "アイコン:許容されるファイル形式はJPG、JPEG、PNG、GIF、BMPのみです。";
    header("Location: /seiran/view/book/input_detail.php?id=" . $book->getId());
    exit;
}

$max_file_size = 5000000; // 5MB
if ($image_info[0] > 2000 || $image_info[1] > 2000 || ($image_info[0] / 3) != ($image_info[1] / 4)) {
    $_SESSION['error_message'] = "アイコン:画像の幅と高さは2000px以下で、アスペクト比は3:4である必要があります。";
    header("Location: /seiran/view/book/input_detail.php?id=" . $book->getId());
    exit;
}
if ($thumbnail["size"] > $max_file_size) {
    $_SESSION['error_message'] = "アイコン:ファイルサイズが大きすぎます。5MB以下のファイルをアップロードしてください。";
    header("Location: /seiran/view/book/input_detail.php?id=" . $book->getId());
    exit;
}

if (!empty($thumbnail)) {
    $filename = uniqid() . '.' . pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
    $thumbnailPath = dirname(__DIR__, 3) . '/assets/img/book/' . $filename;

    $book->setThumbnailPath($filename);
    move_uploaded_file($thumbnail['tmp_name'], $thumbnailPath);
}

$book->setName($_POST['title']);
$book->setDescription($_POST['description']);
$book->setPrice($_POST['price']);
$book->setCategoryId($_POST['category']);
$book->setIsPublic(true);
$repository->update($book);

header("Location: /seiran/view/book/show.php?id=" . $book->getId());
?>
