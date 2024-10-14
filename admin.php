<?php
# Gọi thư viện thao tác cơ sở dữ liệu
include "db.php";

// Đọc dữ liệu thô bên trong kho ra
$ds = csdl_duyệt('giay_dep');

// Định dạng dữ liệu phái sinh
foreach($ds as &$dl)
{
    // định dạnh giới tính
    $dl['gioi_tinh_text'] = ($dl['gioi_tinh']==1) ? "Nam" : "Nữ";
    $dl['ngay_nhap_vi'] = date("d/m/Y", strtotime($dl['ngay_nhap']));
}

# hiện giao diện
include "filephtml/admin.phtml";
die;

print_r($ds);
