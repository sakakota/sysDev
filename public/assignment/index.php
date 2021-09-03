<?php
$dbh = new PDO('mysql:host=mysql;dbname=assignment', 'root', '');

if (isset($_POST['body'])) {

  $image = null;
  if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
  	if(exif_imagetype($_FILES['image']['tmp_name']) == false){  

  	  if (preg_match('/^image\//', $_FILES['image']['type']) !== 1) {
    	  // 例外
  	    header("HTTP/1.1 302 Found");
	      header("Location: ./bbs.php");
    	}
		}

    // 拡張子を取得
    $path = pathinfo($_FILES['image']['name']);
    $ex = $path['extension'];

    $image = bin2hex(random_bytes(24)) . '.' . $ex;
    $filepath =  '/var/www/public/image/' . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
  }

  // 実行
	$sql = 'INSERT INTO details (body, img) VALUES (:body, :img)';
  $ins_stmt = $dbh->prepare($sql);
	$ins_stmt->bindValue(':body', $_POST['body']);
  $ins_stmt->bindValue(':img', $image);
  $ins_stmt->execute();

  header("HTTP/1.1 302 Found");
  header("Location: ./index.php");
  return;
}

// データ取得
$sel_stmt = $dbh->prepare('SELECT * FROM details ORDER BY created_at DESC');
$sel_stmt->execute();
?>

<!-- フォームのPOST先はこのファイル自身にする -->
<form method="POST" action="./index.php" enctype="multipart/form-data">
  <textarea name="body"></textarea>
  <div style="margin: 10px 0;">
    <input type="file" accept="image/*" name="image" id="image">
  </div>
  <button type="submit">送信</button>
</form>

<hr>

<?php foreach($sel_stmt as $val): ?>
  <dl style="margin-bottom: 10px; border-bottom: 1px solid black;">
		<dt>Number</dt>
		<dl><?= $val['id'] ?></dl>
    <dt>日時</dt>
    <dd><?= $val['created_at'] ?></dd>
    <dt>内容</dt>
    <dd>
      <?= nl2br(htmlspecialchars($val['body'])) ?>
      <?php if(isset($val['img'])): ?>
      <div>
        <img src="/image/<?= $val['img'] ?>" style="max-height: 400px;">
      </div>
      <?php endif; ?>
    </dd>
  </dl>
<?php endforeach ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const image = document.getElementById("image");
  image.addEventListener("change", () => {
    if (image.files.length < 1) {
      return;
    }
    if (image.files[0].size > 5 * 1024 * 1024) {
      alert("サイズオーバー。5MB以下にしてください。");;
      image.value = "";
    }
  });
});


</script>
