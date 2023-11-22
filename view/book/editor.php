<?php session_start(); ?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require_once '../component/head.php'; ?>
  <title>編集中 | Seiran</title>
  <link rel="stylesheet" href="/seiran/css/book/editor.css">
  <script src="/seiran/js/NoInputListener.js"></script>
</head>

<body>
  <div class="columns">
    <div class="sidebar column is-one-fifth">
      <aside class="menu pl-6 pt-2">
        <div class="menu-list">
          <i class="fa-solid fa-house"></i>
          <h1>ホーム</h1>
        </div>
        <div class="menu-list">
          <h2>エディタ</h2>
        </div>
        <div class="menu-list">
          <h2>下書き一覧</h2>
        </div>
      </aside>
    </div>
    <div class="column">
      <div class="content">
        <form action="#" method="POST">
          <div class="content-top pr-3">
            <input class="input-title input" type="text" placeholder="タイトルを入力">
            <button class="button is-primary">公開設定</button>
          </div>
          <textarea class="input-content textarea" placeholder="本文を入力..." onclick="resetTimer();"></textarea>
          <div class="toolbar">

          </div>
        </form>
      </div>
    </div>
  </div>

</body>

</html>
