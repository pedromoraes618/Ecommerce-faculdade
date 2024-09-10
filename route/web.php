<?php
$route = isset($_GET['page']) ? $_GET['page'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['q']) ? $_GET['q'] : '';
$breadcrumb = '';
if ($route == "inicial" || empty($route)) { //pagina inicial

    $breadcrumb = "
     <li class='breadcrumb-item'><a href='#' class='text-decoration-none'>Home</a></li>
        <li class='breadcrumb-item active' aria-current='page'>Library</li>
        ";
    include "page/inicial.php";
}
if ($route == "details-product") {

    $breadcrumb = "
    <li class='breadcrumb-item'><a href='./' class='text-decoration-none'>Home</a></li>
       <li class='breadcrumb-item active' aria-current='page'>Produto</li>
       ";
    include "page/produto_tetalhe.php";
}

if ($route == "filter-product") {
    $breadcrumb = " 
    <li class='breadcrumb-item'><a href='./' class='text-decoration-none'>Home</a></li>
       <li class='breadcrumb-item active' aria-current='page'>$filter</li>
       ";
    include "page/produto_filter.php";
}

// if ($route == "search") {
//     $breadcrumb = " 
//     <li class='breadcrumb-item'><a href='./' class='text-decoration-none'>Home</a></li>
//        <li class='breadcrumb-item active' aria-current='page'>$search</li>
//        ";
//     include "page/produto_search.php";
// }
