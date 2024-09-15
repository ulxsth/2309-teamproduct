<?php
session_start();
require_once __DIR__ . '/../../src/usecase/book/FindBookByIdUseCase.php';
require_once __DIR__ . '/../../src/usecase/category/FetchAllCategoryUseCase.php';
$categories = FetchAllCategoryUseCase::execute();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<?php require_once '../component/head.php'; ?>
<title>小説概要入力 | Seiran</title>
<?php require_once '../component/header.php'; ?>
<link rel="stylesheet" href="/seiran/css/app.css">
</head>

<body>
    <?php
    require_once __DIR__ . '/../../src/usecase/book/FindBookByIdUseCase.php';
    $book = findBookById($_GET['id'], true);
    ?>

    <?php require_once '../component/header.php'; ?>
    <main>
        <section class="has-text-centered">
            <h1>小説を作成する</h1>
        </section>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="is-flex is-justify-content-center">' .'<p class=has-text-danger>'. $_SESSION['error_message'] .'</p>'. '</div>';
            unset($_SESSION['error_message']);
        }          
        ?>

        <form action="/seiran/src/usecase/book/PublishBookUseCase.php" method="POST" enctype="multipart/form-data">
            <div class="container mb-6">
                <div class="field">
                    <label for="title">タイトル</label>
                    <input class="input" type="text" required name="title" value="<?php echo $book->getName() ?>"><br>
                </div>
                <div class="field">
                    <label for="description">概要</label>
                    <textarea name="description" class="textarea" value="<?php echo $book->getDescription() ?>"></textarea>
                </div>
                <div class="field is-flex">
                    <section class="mr-6">
                        <label for="price">価格</label>
                        <input class="input" type="number" required min="0" step="1" name="price" value="<?php echo $book->getPrice() ?>">
                    </section>
                    <section>
                        <label for="category">カテゴリー</label>
                        <div class="select">
                            <select name="category">
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category->getId() ?>"><?php echo $category->getName() ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </section>
                </div>
                <div class="field">
                    <label for="thumbnail">表紙画像</label>
                    <input type="file" name="thumbnail" accept="image/*">
                </div>
            </div>

            <div class=" field flex-center">
                <button type="submit" class="button is-primary px-6">作成</button>
            </div>
            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
        </form>
        <div>
        </div>
    </main>

</body>

</html>
