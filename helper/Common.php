<?php

if (!function_exists('slug_string')) {
    function slug_string($title)
    {
        $replacement = '-';
        $map = array();
        $quotedReplacement = preg_quote($replacement, '/');
        $default = array(
            '/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|å/' => 'a',
            '/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|ë/' => 'e',
            '/ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|î/' => 'i',
            '/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ø/' => 'o',
            '/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|ů|û/' => 'u',
            '/ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'y',
            '/đ|Đ/' => 'd',
            '/ç/' => 'c',
            '/ñ/' => 'n',
            '/ä|æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/\\s+/' => $replacement,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        );
        //Some URL was encode, decode first
        $title = urldecode($title);
        $map = array_merge($map, $default);
        return strtolower(preg_replace(array_keys($map), array_values($map), $title));
    }
}

function getSessionUsername()
{
    $username = $_SESSION['username'] ?? null;
    return $username;
}
function getSessionEmail()
{
    $email = $_SESSION['email'] ?? null;
    return $email;
}
function getSessionIdUser()
{
    $id = $_SESSION['idUser'] ?? null;
    return $id;
}
function getSessionRoleIdUser()
{
    $roleId = $_SESSION['roleId'] ?? null;
    return $roleId;
}
function getSessionIdAccount()
{
    $accountId = $_SESSION['idAccount'] ?? null;
    return $accountId;
}
if(!function_exists('createLink')){
    function createLink($data = []){
        /*
            giai thich cho mang data
            [
                'c' => 'department',
                'm' => 'index',
                'page' => '{page}',
                'search' => '{keyword}'
            ]
            // tao link phan trang
            // index.php?c=department&m=index&page=1&search=
        */
        $strLinkPage = '';
        foreach($data as $key => $value){
            $strLinkPage .= empty($strLinkPage) ? "?{$key}={$value}" : "&{$key}={$value}";
        }
        return ROOT_PATH . $strLinkPage;
        // index.php?c=department&m=index&page=1&search=
    }
}

if (!function_exists('pagigate')) {
    function pagigate($link, $totalItem, $page = 1, $keyword = '', $limit = 2)
    {
        // Tính tổng số trang
        $totalPage = ceil($totalItem / $limit);
        // Xác định trang hiện tại không được nhỏ hơn 1 và không lớn hơn tổng số trang
        $page = max(min($page, $totalPage), 1);
        // Tính toán offset
        $start = ($page - 1) * $limit;

        // Xây dựng template HTML phân trang bằng bootstrap
        $htmlPage = '<nav><ul class="pagination">';
        // Nút Previous
        if ($page > 1) {
            $htmlPage .= '<li class="page-item"><a href="' . str_replace('{page}', $page - 1, $link) . '" class="page-link">Previous</a></li>';
        }
        // Các trang từ 1 đến 3 và trang hiện tại
        $startPage = max(1, min($page - 1, $totalPage - 2));
        for ($i = $startPage; $i <= min($totalPage, $startPage + 2); $i++) {
            if ($i == $page) {
                $htmlPage .= '<li class="page-item active" aria-current="page"><a class="page-link">' . $page . '</a></li>';
            } else {
                $htmlPage .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $i, $link) . '">' . $i . '</a></li>';
            }
        }
        // Nút Next
        if ($page < $totalPage) {
            $htmlPage .= '<li class="page-item"><a href="' . str_replace('{page}', $page + 1, $link) . '" class="page-link">Next</a></li>';
        }
        $htmlPage .= '</ul></nav>';

        return [
            'start' => $start,
            'pagination' => $htmlPage
        ];
    }
}