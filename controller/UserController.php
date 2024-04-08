<?php

require 'model/DepartmentModel.php';
require 'model/CourseModel.php';
require 'model/UserModel.php';

//m = ten cua ham nam trong thu muc controller
$m = trim($_GET['m'] ?? 'index'); //ham mac dinh trong controller la index
$m = strtolower($m); //viet thuong tat ca ten ham

switch ($m) 
{
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
function handleEdit(){
    if(isset($_POST['btnSave'])){
        $id = trim($_GET['id'] ?? null);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailUserById($id);

        $extra_code = trim($_POST['extra_code']??null);
        $extra_code = strip_tags($extra_code);

        $full_name = trim($_POST['full_name']??null);
        $full_name = strip_tags($full_name);

        $email = trim($_POST['email']??null);
        $email = strip_tags($email);

        $phone = trim($_POST['phone']??null);
        $phone = strip_tags($phone);

        $role_id = trim($_POST['role_id']??null);
        

        $status = trim($_POST['status']??null);
        $status = $status === '0' || $status === '1' ? $status :0;

        $gender = trim($_POST['gender']??null);
        $gender = $gender === '0' || $gender === '1' ? $gender :0;
        
        $address = trim($_POST['address']??null);
        $address = strip_tags($address);

        $birthday = trim($_POST['birthday'] ?? null);
        $birthday = date ('Y-m-d', strtotime($birthday));

        //kiem tra thong tin
        $_SESSION['error_update_user'] = [];
        
        if(empty($extra_code)){
            $_SESSION['error_update_user']['extra_code'] = 'Enter extra code of user, please';
        } else {
            $_SESSION['error_update_user']['extra_code'] = null;
        }
        if(empty($full_name)){
            $_SESSION['error_update_user']['full_name'] = 'Enter name of full name, please';
        } else {
            $_SESSION['error_update_user']['full_name'] = null;
        }

        if(empty($email)){
            $_SESSION['error_update_user']['email'] = 'Enter email of user, please';
        } else {
            $_SESSION['error_update_user']['email'] = null;
        }
        if(empty($phone)){
            $_SESSION['error_update_user']['phone'] = 'Enter name of phone, please';
        } else {
            $_SESSION['error_update_user']['phone'] = null;
        }
        if(empty($address)){
            $_SESSION['error_update_user']['address'] = 'Enter name of address, please';
        } else {
            $_SESSION['error_update_user']['address'] = null;
        }
        //xu li up load logo
        $avatar = $info['avatar'] ?? null;
        $_SESSION['error_update_user']['avatar'] = null;
if(!empty($_FILES['avatar']['tmp_name'])){
            $avatar = uploadFile($_FILES['avatar'], 'public/upload/images/', ['image/png', 'image/jpg', 'image/jpeg','image/gif'], 5*1024*1024);
            if(empty($avatar)){
                $_SESSION['error_update_user']['avatar'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_update_user']['avatar'] = null;
            }
        }

        $flagCheckingError = false;
        foreach($_SESSION['error_update_user'] as $error){
            if(!empty($error)){
                $flagCheckingError=true;
                break;

            }
        }

        if(!$flagCheckingError){
            //khong co loi-insert du lieu vao dtb
            if(isset($_SESSION['error_update_user'])){
                unset($_SESSION['error_update_user']);
            }

            $update =  updateUserById($extra_code, $role_id, $full_name, $email, $phone, $avatar, $address, $birthday, $gender, $status, $id);
            if($update){
                //update thanh cong
                header("Location:index.php?c=user&state=success");
            } else{
                header("Location:index.php?c=user&m=edit&id={$id}&state=error");
            }
        } else {
            //co loi, cho quay lai form
            header("Location:index.php?c=user&m=edit&id={$id}&state=failure");
        }
    }
}
function edit(){
     // phai dang nhap moi duoc su dung chuc nang nay.
     if(!isLoginUser()){
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0; // is_numeric : kiem tra co phai la so hay ko ?
    $info = getDetailUserById($id); // goi ham trong model
    $role = getDataRole();
    if(!empty($info)){
        // co du lieu trong database
        // hien thi giao dien - thong tin chi tiet du lieu
 
        require 'view/user/edit_view.php';
    } else {
        // khong co du lieu trong database
        // thong bao 1 giao dien loi
        require 'view/error_view.php';
    }

}
function handleAdd(){
    if(isset($_POST['btnSave'])){
        $extra_code = trim($_POST['extra_code']??null);
        $extra_code = strip_tags($extra_code);

        $full_name = trim($_POST['full_name']??null);
        $full_name = strip_tags($full_name);

        $email = trim($_POST['email']??null);
        $email = strip_tags($email);

        $phone = trim($_POST['phone']??null);
        $phone = strip_tags($phone);

        $role_id = trim($_POST['role']??null);
        

        $status = trim($_POST['status']??null);
        $status = $status === '0' || $status === '1' ? $status :0;

        $gender = trim($_POST['gender']??null);
        $gender = $gender === '0' || $gender === '1' ? $gender :0;
        
        $address = trim($_POST['address']??null);
        $address = strip_tags($address);

        $birthday = trim($_POST['birthday'] ?? null);
$birthday = date ('Y-m-d', strtotime($birthday));

        //kiem tra thong tin
        $_SESSION['error_add_user'] = [];
        
        if(empty($extra_code)){
            $_SESSION['error_add_user']['extra_code'] = 'Enter extra code of user, please';
        } else {
            $_SESSION['error_add_user']['extra_code'] = null;
        }
        if(empty($full_name)){
            $_SESSION['error_add_user']['full_name'] = 'Enter name of full name, please';
        } else {
            $_SESSION['error_add_user']['full_name'] = null;
        }

        $_SESSION['error_add_user'] = [];
        if(empty($email)){
            $_SESSION['error_add_user']['email'] = 'Enter email of user, please';
        } else {
            $_SESSION['error_add_user']['email'] = null;
        }
        if(empty($phone)){
            $_SESSION['error_add_user']['phone'] = 'Enter name of phone, please';
        } else {
            $_SESSION['error_add_user']['phone'] = null;
        }
        if(empty($address)){
            $_SESSION['error_add_user']['address'] = 'Enter name of address, please';
        } else {
            $_SESSION['error_add_user']['address'] = null;
        }
        //xu li up load logo
        $avatar = null;
        $_SESSION['error_add_user']['avatar'] = null;
        if(!empty($_FILES['avatar']['tmp_name'])){
            $avatar = uploadFile($_FILES['avatar'], 'public/upload/images/', ['image/png', 'image/jpg', 'image/jpeg','image/gif'], 5*1024*1024);
            if(empty($avatar)){
                $_SESSION['error_add_user']['avatar'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_add_user']['avatar'] = null;
            }
        }

        $flagCheckingError = false;
        foreach($_SESSION['error_add_user'] as $error){
            if(!empty($error)){
                $flagCheckingError=true;
                break;

            }
        }

        //tien hanh check lai
        if(!$flagCheckingError){
            //tien hanh insert vao dtb
            $insert = insertUser($extra_code, $role_id, $full_name, $email, $phone, $avatar, $address, $birthday, $gender, $status);
            if($insert){
                header("Location:index.php?c=user&state=success");
            }
            else{
                header("Location:index.php?c=user&m=add&state=error");

            }
        }
        else{
            //thong bao loi cho ng dung biet
            header("Location:index.php?c=user&m=add&state=fail");
        }
    }

}
function Add(){
        $role = getDataRole();
        require 'view/user/add_view.php';
   

}
function index()
{
    //phai dang nhap moi su dung duoc chuc nang nay
    if(!isLoginUser())
    {
        header ("Location:index.php");
        exit();
    }
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page = trim($_GET['page'] ?? null);
$page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'user',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);

    $roleName =[];
    $role = getDataRole();
    foreach($role as $roles){
        $roleName[$roles['id']]=$roles['name'];
    }


    $totalItems = getAllDataUsers($keyword); // goi ten ham trong model
    $totalItems = count($totalItems);
    // departments
    $panigate = pagigate($linkPage, $totalItems, $page, $keyword, 2);
    $start = $panigate['start'] ?? 0;
    $users = getAllDataUsersByPage($keyword, $start, 2);
    $htmlPage = $panigate['pagination'] ?? null;
    require ('view/user/index_view.php');
}