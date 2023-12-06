<?php
session_start();
require_once '../src/usecase/book/FuzzyFetchBookUseCase.php';

$keyword = $_POST['keyword'];
$books = FuzzyFetchBookUseCase::execute($keyword);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>検索 | Seiran</title>
	<?php require_once 'component/head.php'; ?>
	<link rel="stylesheet" href="/seiran/css/app.css">
	<link rel="stylesheet" href="/seiran/css/search.css">
</head>

<body>
	<?php require_once 'component/header.php'; ?>
	<main>
		<div class="searched-user">
			<div class="mb-2">
				<h1>ユーザー</h1>
			</div>
			<?php for ($i = 0; $i < 2; $i++) : ?>
				<div class="columns">
					<?php for ($j = 0; $j < 2; $j++) : ?>
						<div class="column is-flex is-align-items-center">
							<figure class="image is-64x64 mr-2">
								<img class="is-rounded" src="../assets/img/user/anonimous.svg" alt="user icon">
							</figure>
							<div class="mt-4">
								<p>user_id</p>
								<p>introduce</p>
							</div>
							<button class="button is-primary px-6 ml-auto">フォロー</button>
						</div>
					<?php endfor; ?>
				</div>
			<?php endfor; ?>
		</div>

		<div class="searched-books">
			<div class="mt-4">
				<h1>小説</h1>
			</div>
			<?php for ($i = 0; $i < count($books); $i++) : ?>
				<?php if ($i % 4 === 0) : ?>
					<div class="columns is-flex is-flex-direction-row">
					<?php endif; ?>

					<article class="searched-book column is-one-fifth is-flex is-flex-direction-column">
						<div class="searched-book__display">
							<figure class="image searched-book__thumbnail is-3by4">
								<img src="../assets/img/book/sample.png" alt="book image">
							</figure>
							<div class="searched-book__favorite-display">
								<i class="fa-solid fa-heart has-text-danger"></i>
								<span>100</span>
							</div>
						</div>
						<p class="is-size-5 is-italic"><?php echo $books[$i]->getName(); ?></p>
						<div class="mb-4 is-flex is-flex-direction-row">
							<figure class="image is-32x32">
								<img class="is-rounded" src="../assets/img/user/anonimous.svg" alt="user icon">
							</figure>
							<p class="mt-1"><?php echo $books[$i]->getUserId(); ?></p>
						</div>
					</article>

					<?php if (($i + 1) % 4 === 0 || $i === count($books) - 1) : ?>
					</div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</main>
</body>

</html>
