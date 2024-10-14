<?php

include "db.php";

if($_SERVER['REQUEST_METHOD']=='POST') {

    $data = $_POST;

    unset($data['id']); // thêm mới, nên không cần, nó được tạo tự động
    // unset($data['mo_ta']); KHÔNG ĐƯỢC SET, NẾU KO CHƯƠNG TRÌNH DIE

    csdl_thêm('giay_dep', $data);

    header("location: admin.php");
    die; 
}
# Gửi dữ liệu sang giao diện và hiển thị'
$action = "them.php";
$form_header = "Form Thêm Mới";

include "filephtml/form.phtml";
