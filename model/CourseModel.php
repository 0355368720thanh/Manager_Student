<?php
function getAllDataCourse($keyword = null)
{
    $sql = "SELECT c.*, d.name FROM `courses` AS c INNER JOIN `departments` AS d ON d.id = c.department_id WHERE c.`deleted_at` IS NULL";
    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $data = [];
    if ($stmt) {
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }


    disconnectDb($db);
    return $data;
}
function getDetailCourseById($id = 0)
{
    $sql = "SELECT c.*, d.name FROM `courses` AS c INNER JOIN `departments` AS d ON d.id = c.department_id WHERE c.`course_id` = :id AND c.`deleted_at` IS NULL";
    $db = connectionDb();
    $data = [];
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db);
    return $data;
}

function insertCourse($name, $slug, $department_id, $status, $createdAt)
{
    $sqlInsert = "INSERT INTO `courses`( `course_name`, `slug`, `department_id`, `status`, `created_at`) VALUES (:courseName, :slug, :departmentId, :statusCourse, :createdAt)";
    $checkInsert = false;
    $db = connectionDb();
    $stmt = $db->prepare($sqlInsert);
    $createdAt = date('Y-m-d H:i:s');

    if ($stmt) {
        $stmt->bindParam(':courseName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':departmentId', $department_id, PDO::PARAM_INT);

        $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);

        $stmt->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $checkInsert = true;
        }
    }
    disconnectDb($db); // ngat ket noi toi database
    // tra ve true insert thanh cong va nguoc lai
    return $checkInsert;
}
function deleteCourseById($id = 0)
{
    $sql = "UPDATE `courses` SET `deleted_at` = :deleted_at WHERE `course_id` = :id";
    $db = connectionDb();
    $checkDelete = false;
    $deleteTime = date("Y-m-d H:i:s");
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':deleted_at', $deleteTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkDelete = true;
        }
    }
    disconnectDb($db);
    return $checkDelete;
}

function updateCourseById($name, $slug, $department_id, $status, $id)
{
    $checkedUpdate = false;
    $db = connectionDb();
    $updatedAt = date('Y-m-d H:i:s');
    $sqlUpdate = "UPDATE `courses` SET `course_name`= :courseName,`slug`=:slug,`department_id`=:departmentId,`status`=:statusCourse,`updated_at`= :updatedAt WHERE `course_id`=:id AND `deleted_at` IS NULL";
    $stmt = $db->prepare($sqlUpdate);
    if ($stmt) {
        $stmt->bindParam(':courseName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':departmentId', $department_id, PDO::PARAM_INT);

        $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);

        $stmt->bindParam(':updatedAt', $updatedAt, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $checkedUpdate = true;
        }
    }
    disconnectDb($db);

    return $checkedUpdate;
}

function getAllDataCourseByPage($keyword = null, $start = 0, $limit = 10)
{
    $key = "%{$keyword}%";
    $sql = "SELECT c.*, d.name FROM `courses` AS c INNER JOIN `departments` AS d ON d.id = c.department_id WHERE (`course_name` LIKE :nameCourse OR d.name LIKE :nameDepartment ) AND c.`deleted_at` IS NULL LIMIT :startData, :limitData";
    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $data = [];
    if ($stmt) {
        $stmt->bindParam(':nameCourse', $key, PDO::PARAM_STR);
        $stmt->bindParam(':nameDepartment', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db);
    return $data;
}