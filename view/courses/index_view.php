<?php
if (!defined('ROOT_PATH')) {
    die('Can not access');
}
$titlePage = "Btec - Courses";
$state =trim($_GET['state']??null);

?>
<?php require 'view/partials/header_view.php'; ?>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Courses
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <a href="index.php?c=courses&m=add" class="btn btn-primary btn-lg">Add new Courses</a>
        <div class="row mt-3">
            <div class="col-sm-12 col-md-6">
                <input type="text" id="keyword" value="<?=htmlentities($keyword); ?>">
                <button class="btn btn-primary btn-sm" id="btnSearchCourse">Search</button>
                <a href="index.php?c=courses" class="btn btn-info btn-sm">Back to lists</a>
            </div>
        </div>
        <table class="table table-bordered table-striped my-3">
            <thead class="table-primary">
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Status</th>

                <th colspan="2" class="text-center" width="10%">Action</th>
            </thead>
            <tbody>
                <?php foreach ($courses as $key => $item) : ?>
                    <tr>
                    <td><?= $item['course_id']; ?></td>
                        <td><?= $item['course_name']; ?></td>

                        <td><?= $item['name']; ?></td>

                        <td><?= $item['status'] == 1 ? 'Active' : 'Deactive'; ?></td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="index.php?c=courses&m=edit&id=<?= $item['course_id']; ?>">
                                Edit</a>
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm" href="index.php?c=courses&m=delete&id=<?= $item['course_id']; ?>">
                                Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $htmlPage; ?>
    </div>
</div>
<script>
    let btnSearch=document.getElementById('btnSearchCourse');
    btnSearch.addEventListener('click',function(){
        let txtKeyword =document.getElementById('keyword');
        let keyword =txtKeyword.value.trim();
        if(keyword!=''){
            window.location.href="index.php?c=courses&search="+keyword;
        }else{
            alert('Enter keyword,please');
        }
    });
</script>
<?php require 'view/partials/footer_view.php'; ?>
