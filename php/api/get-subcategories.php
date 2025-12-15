<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/SubCategory.php';

if (!isset($_GET['category_id'])) {
    echo json_encode([]);
    exit;
}

$categoryId = (int)$_GET['category_id'];

$pdo = Database::getInstance();
$subCategoryModel = new SubCategory($pdo);
$subcategories = $subCategoryModel->getByCategory($categoryId);

echo json_encode($subcategories);
?>
