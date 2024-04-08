<?php
require 'model/DepartmentModel.php';
//m = ten cua ham nam trong thu muc controller
$m = trim($_GET['m'] ?? 'index'); //ham mac dinh trong controller la index
$m = strtolower($m); //viet thuong tat ca ten ham

switch ($m) {
    case 'index':
        index();
        break;
    case 'add':
        Add();
        break;
    case 'handle-add':
        handleAdd();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'edit':
        edit();
        break;
    case 'handle-edit':
        handleEdit();
        break;

    default:
        index();
        break;
}
function edit()
{
    if (!isLoginUser()) {
        header("location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $info = getDetailDepartmentByID($id);
    if (!empty($info)) {
        require 'view/department/edit_view.php';

    } else {
        require 'view/error_view.php';
    }
}
function handleEdit()
{
    if (isset($_POST['btnSave'])) {
        $id = trim($_GET['id']);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailDepartmentById($id);

        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $beginningDate = trim($_POST['date_beginning'] ?? null);
        $beginningDate = date('Y-m-d', strtotime($beginningDate));
        $_SESSION['error_update_department'] = [];
        if (empty($name)) {
            $_SESSION['error_update_department']['name'] = 'Enter name of department,please';

        } else {
            $_SESSION['error_update_department']['name'] = null;
        }
        if (empty($leader)) {
            $_SESSION['error_update_department']['leader'] = 'Enter name of leader,please';

        } else {
            $_SESSION['error_update_department']['leader'] = null;
        }
        //xu ly upload logo
        $logo = $info['logo'] ?? null;
        $_SESSION['error_update_department']['logo'] = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile($_FILES['logo'], 'public/upload/images/', ['image/png', 'image/jpg', 'image/jpeg'], 5 * 1024 * 1024);


            if (empty($logo)) {
                $_SESSION['error_update_department']['logo'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_update_department']['logo'] = null;
            }
        }
        $flagCheckingError = false;
        foreach ($_SESSION['error_update_department'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        if (!$flagCheckingError) {
            //k co loi-insert du lieu db
            if (isset($_SESSION['error_update_department'])) {
                unset($_SESSION['error_update_department']);
            }
            $slug = slug_string($name);
            $update = updateDepartmentById($name, $slug, $leader, $status, $beginningDate, $logo, $id);
            if ($update) {
                //update thanh cong
                header("location:index.php?c=department&state=success");
            } else {
                header("location:index.php?c=department&m=edit&id={$id}&tate=error");
            }
        } else {
            //co loi quay lai form
            header("location:index.php?c=department&m=edit&id={$id}&tate=failure");
        }

    }
}

function index()
{
    if (!isLoginUser()) {
        header("location:index.php");
        exit();
    }
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $departments = getAllDataDepartments($keyword);
    $page = trim($_GET['page'] ?? null);
    $page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'department',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);
    $totalItems = getAllDataDepartments($keyword);
    $totalItems = count($totalItems);
    $panigate = pagigate($linkPage, $totalItems, $page, $keyword,   10);
    $start = $panigate['start'] ?? 0;
    $departments = getAllDataDepartmentsByPage($keyword, $start, 10);
    $htmlPage = $panigate['pagination'] ?? null;
    require 'view/department/index_view.php';



}
function handleDelete()
{
    if (!isLoginUser()) {
        header("location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $delete = deleteDepartmentById($id);///goi ten ham trong model
    if ($delete) {
        //xoa thanh cong
        header("location:index.php?c=department&state_del=success");
    } else {
        //xoa that bai
        header("location:index.php?c=department&state_del=failure");

    }
}
function Add()
{
    require 'view/department/add_view.php';
}

function handleAdd()
{
    if (isset($_POST['btnSave'])) {
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $beginningDate = trim($_POST['date_beginning'] ?? null);
        $beginningDate = date('Y-m-d', strtotime($beginningDate));
        //kiem tra thong tin
        $_SESSION['error_add_department'] = [];
        if (empty($name)) {
            $_SESSION['error_add_department']['name'] = 'Enter name of department,please';

        } else {
            $_SESSION['error_add_department']['name'] = null;
        }
        if (empty($leader)) {
            $_SESSION['error_add_department']['leader'] = 'Enter name of leader,please';

        } else {
            $_SESSION['error_add_department']['leader'] = null;
        }
        //xu ly upload logo
        $logo = null;
        $_SESSION['error_add_department']['logo'] = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile($_FILES['logo'], 'public/upload/images/', ['image/png', 'image/jpg', 'image/jpeg'], 5 * 1024 * 1024);


            if (empty($logo)) {
                $_SESSION['error_add_department']['logo'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_add_department']['logo'] = null;
            }
        }
        $flagCheckingError = false;
        foreach ($_SESSION['error_add_department'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }

        //tien hanh check lai
        if (!$flagCheckingError) {
            //tien hanh insert vao database
            $slug = slug_string($name);
            $insert = insertDepartment($name, $slug, $leader, $status, $logo, $beginningDate);
            if ($insert) {
                header("location:index.php?c=department&state=success");
            } else {
                header("location:index.php?c=department&m=add&state=error");
            }
        } else {
            //thong bao loi cho nguoi dung biet 
            header("location:index.php?c=department&m=add&state=fail");
        }
    }
}
