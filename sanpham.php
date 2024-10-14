<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include "top.htm";?>
<body>
  
<?php
include "db.php";

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $data = $_POST;
    unset($data['mo_ta']);

    csdl_sửa('giay_dep', $data);

    header("location: admin.php");
    die; // exit;
}

$ds = csdl_duyệt('giay_dep');

?>


<div class="ui grid">

<?php foreach ($ds as &$dl): ?>
<tr class="dongsp" data-row-id="<?= $dl['id'] ?>">

  <div class="four wide column">
    <div class="ui card">
      <div class="image">
        <img src="<?= $dl['anh_sp']?>">
      </div>
      <div class="content">
        <a class="header"><?= $dl['ten_sp']?></a>
        <div class="content">
          <a class="header"><?= $dl['thuong_hieu']?></a>
        </div>
        <div class="gtsp">
        <?= $dl['mo_ta']?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

</div>

</body>
<footer>
<div>""</div>
</footer>
</html>




