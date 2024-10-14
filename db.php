<?php

error_reporting(1);


$db = new SQLite3('data.db');

/**
 * Chuyển đổi bảng MySQL Table
 * sang mảng PHP Array.
 * Mỗi phần tử mảng là bản ghi Record,
 * mỗi ô trong bản ghi là một string.
 */
function 
csdl_chạy($sql)
{
     global $db;
    

    $q = $db->exec($sql);

    if(!$q) 
        print "\n\n Lỗi truy vấn cơ sở dữ liệu. Nguyên nhân: ";
  
}

function 
csdl_bảng($sql)
{
    global $db;

    $table = array();

    //$sql = "SELECT * FROM tên_bảng";
	$query = $db->query($sql);

	while($row = $query->fetchArray(SQLITE3_ASSOC))
    {
        $table[] = $row;
    }

    return $table;
}

/**
 * Chuyển đổi dòng MySQL Row
 * sang bản ghi PHP, ví dụ:
 * $bg = [
 *   'id' => '1',
 *   'tên' => 'Nobita',
 *    'năm_sinh' => '1995'
 * ]
 * Mỗi ô trong bản ghi là một string.
 * 
 */
function 
csdl_dòng($sql)
{


    $table = csdl_bảng($sql);

    if(!is_array($table) || empty($table))
        return null;

    // Trả về dòng đầu tiên trong bảng kết quả truy vấn
    return $table[0];
}

// Trả về string
// phù hợp với các hàm: min, max, avg, count
function 
csdl_ô($sql)
{    
    $row = csdl_dòng($sql);

    if(!is_array($row) || empty($row))
        return null;

    // Trả về ô, cột đầu tiên trong dòng đầu tiên của kết quả truy vấn 
    return $row[0];
}

/**
 * @update 2024.02.01 10h04
 * Chuyển đổi dòng MySQL Row
 * sang bản ghi PHP Record, ví dụ:
 * $bg = [
 *   'id' => 1,
 *    'tên' => 'Nobita',
 *     'năm_sinh' => 1995
 * ]
 * Mỗi ô trong bản ghi là một string.
 */
function 
csdl_bản_ghi($tên_bảng, $mã)
{
    return csdl_xem($tên_bảng, $mã);
}

/**
 * @update 2024.02.01 10h04
 * Chuyển đổi dòng MySQL Row
 * sang đối tượng PHP Object, ví dụ:
 * $obj->id = '1';
 * $obj->tên = 'Nobita';
 * $obj->năm_sinh = '1995';
 * 
 * Mỗi trường trong object là một string.
 */
function 
csdl_đối_tượng($tên_bảng, $mã)
{
    return (object)csdl_bản_ghi($tên_bảng, $mã);
}

function 
csdl_kí_tự_đặc_biệt($str)
{
    global $db;
    return $db->escapeString($str);
}

// Lấy ra id mới chèn vào
function 
csdl_id_cuối()
{
    global $db;
    return $db->lastInsertRowID();
}

/*
Ví dụ về thêm mới:
INPUT
$cầu_thủ_mới = [
    'ten' => 'Elena',
    'can_nang' => 70,
    'chieu_cao' => 1.72,
    'nhom_mau' => 'A',
    'gioi' => true,
    'ngay_sinh' => '1992-12-17'
];
OUTPUT
// INSERT INTO cau_thu (ten,can_nang,chieu_cao,nhom_mau,gioi,ngay_sinh) 
// VALUES 
// ('Natalia','70','1.72','A','','1992-12-17')
*/
function csdl_thêm($tên_bảng, $data=array())
{
    
    //Bước 1: lấy giá trị của key cho vào 1 mảng
    $keys = array_keys($data);

    //Bước 2: Xử lý chuỗi với mảng (biến mảng thành 1 chuỗi)
    $các_cột = implode(",", $keys);

    //Bước 3: xử lý giá trị 
    $các_giá_trị = '';
    foreach ($data as $key => $value) 
    {
        if($value===false || $value==='false') 
            $value = '0';
        if($value===true || $value==='true') 
            $value = '1';

        $các_giá_trị .= "'$value',";

    }
    //Bước 4: Xóa dấu phẩy ở cuối
    $các_giá_trị = trim($các_giá_trị, ",");

    //Bước 5: Viết câu lệnh SQL
    $sql = "INSERT INTO $tên_bảng ($các_cột) VALUES ($các_giá_trị)";
    
    // print "\n câu sql bị lỗi: \n";
    // print($sql); // in thử
    // print "\n";
    // die;

    csdl_chạy($sql);
    
}


/**
 * Sửa dòng trong bảng MySQL
 * theo mã định danh và dữ liệu truyền vào.
 * ví dụ:
 * UPDATE thú_cưng SET id = '4',
 *                     tên = 'Sakura',
 *                     tuổi = '29',
 *                     cân_nặng = '29.2',
 *                     nhóm_máu = 'A',
 *                     giới_tính = '1',ảnh= 'http://anh.com/sakura.jpg' 
 * WHERE id=4
 */
function csdl_sửa($tên_bảng, $dữ_liệu=array()){
    //$value_str = '';
    $chuỗi_giá_trị = "";
    foreach ($dữ_liệu as $khóa => $giá_trị) {
        $chuỗi_giá_trị .="$khóa = '$giá_trị',";
    }

    //Xóa dấu phẩy ở cuối
    $chuỗi_giá_trị = trim($chuỗi_giá_trị, ",");

    $mã = $dữ_liệu['id'];
    $sql = "UPDATE `{$tên_bảng}` SET $chuỗi_giá_trị WHERE rowid={$mã}";
    
    // print $sql;

    csdl_chạy($sql);
    
}

// function sửa($table, $data=array(), $condition=array()){
function csdl_sửa_có_điều_kiện($tên_bảng, $dữ_liệu=array(), $điều_kiện=array()){
    //$value_str = '';
    $chuỗi_giá_trị = "";
    foreach ($dữ_liệu as $khóa => $giá_trị) {
        $chuỗi_giá_trị .="$khóa = '$giá_trị',";
    }

    //Xóa dấu phẩy ở cuối
    $chuỗi_giá_trị = trim($chuỗi_giá_trị, ",");

    $sql = "UPDATE `{$tên_bảng}` SET $chuỗi_giá_trị WHERE ";
    foreach ($$điều_kiện as $khóa => $giá_trị) {
            $sql.="$khóa = '$giá_trị' AND";
    }
    //Xóa chữ AND ở cuối
    $sql = trim($sql, 'AND');
    csdl_chạy($sql);
    
}

/**
 * Xóa đi 1 dòng bản ghi MySQL theo mã định danh của nó
 */
function csdl_xóa_id($tên_bảng, $mã)
{

    $sql = "
        DELETE FROM `{$tên_bảng}` WHERE rowid = '{$mã}'
   ";

    csdl_chạy($sql);
}

/**
 * Xóa 1 dòng bản ghi trong bảng MySQL
 * theo dữ liệu mà nó truyền vào (trong đó có id)
 */
// function csdl_xóa($tên_bảng, $mã)
function csdl_xóa($tên_bảng, $dữ_liệu)
{

    $mã = $dữ_liệu['id'];

    csdl_xóa_id($tên_bảng, $mã);
}


/**
 * Xem 1 dòng bản ghi trong bảng MySQL
 * theo mã định danh khóa chính của nó.
 * Chuyển đổi dòng MySQL Row
 * sang bản ghi PHP Record, ví dụ:
 * $bg = [
 *   'id'=>2,
 *    'tên'=>'Nobita',
 *     'năm_sinh'=>1995
 * ]
 */
function// hàm
csdl_xem($bảng, $mã)
{

    $sql = "
        SELECT * FROM `{$bảng}` WHERE rowid = '{$mã}'
    ";

    return csdl_dòng($sql);
}

/**
 * Duyệt bảng dữ liệu MySQL nhiều dòng.
 * Chuyển đổi bảng MySQL Table sang
 * mảng PHP Array
 * @param string $bảng tên của bảng cần duyệt dữ liệu
 */
function// hàm
csdl_duyệt($bảng)
{
    $sql = "SELECT * FROM `{$bảng}` ";
    
    return csdl_bảng($sql);
}