<?php
include "db.php";

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $data = $_POST;

    // Loại bỏ các thành phần ko cần xem
    unset($data['mo_ta']);

    csdl_sửa('giay_dep', $data);

    header("location: admin.php");
    die;
}

$dl = csdl_xem('giay_dep', $_GET['id']);
$dl['ngay_nhap_vi'] = date("Y-m-d", strtotime($dl['ngay_nhap']));

include "filephtml/xem.phtml";