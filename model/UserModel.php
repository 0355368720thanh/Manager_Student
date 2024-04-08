<?php
require_once 'database/database.php';



function updateUserById($extra_code, $role_id, $full_name, $email, $phone, $avatar, $address, $birthday, $gender, $status, $id)
{
    // Biến kiểm tra việc cập nhật
    $checkUpdate = false;
    
    // Kết nối đến cơ sở dữ liệu
    $db = connectionDb();
    
    // Câu lệnh SQL để cập nhật thông tin của người dùng
    $sql = "UPDATE `users` 
            SET `extra_code` = :extra_code,
                `role_id` = :role_id,
                `full_name` = :full_name,
                `email` = :email,
                `phone` = :phone,
                `avatar` = :avatar,
                `address` = :address,
                `birthday` = :birthday,
                `gender` = :gender,
                `status` = :status,
                `updated_at` = :updated_at
            WHERE `id` = :id";
    
    // Thời gian cập nhật
    $updateTime = date('Y-m-d H:i:s');
    
    // Chuẩn bị và thực thi câu lệnh SQL
    $stmt = $db->prepare($sql);
    
    if ($stmt) {
        $stmt->bindParam(':extra_code', $extra_code, PDO::PARAM_STR);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $full_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':updated_at', $updateTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $checkUpdate = true;
        }
    }
    
    // Ngắt kết nối đến cơ sở dữ liệu
    disconnectDb($db);
    
    // Trả về kết quả việc cập nhật
    return $checkUpdate;
}

function getDetailUserById($id=0){
    $sql = "SELECT * FROM `users` WHERE `id` = :id AND `deleted_at` IS NULL";
    $db = connectionDb();
    $data=[];
    $stmt = $db->prepare($sql);
    if($stmt){
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            $data = $stmt->fetch(PDO::FETCH_ASSOC); 
        }
    }
    disconnectDb($db);
    return $data;
}

function deleteUsersById($id = 0)
{
    // Câu lệnh SQL để cập nhật trường `deleted_at` của bảng users thành thời gian hiện tại
    $sql = "UPDATE `users` SET `deleted_at` = :deleted_at WHERE `id` = :id";
    
    // Kết nối đến cơ sở dữ liệu
    $db = connectionDb();
    // Biến kiểm tra việc xóa
    $checkDelete = false;
    
    // Thời gian xóa
    $deleteTime = date("Y-m-d H:i:s");}