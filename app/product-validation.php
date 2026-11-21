<?php
// Check product input before inserting or updating database records.
$name = trim((string)($_POST['name'] ?? ''));
$description = trim((string)($_POST['description'] ?? ''));
$category = (string)($_POST['category'] ?? '');
$unit = (string)($_POST['unit'] ?? '');
$price = (string)($_POST['price'] ?? '');
$quantity = (string)($_POST['quantity'] ?? '');
$status = (string)($_POST['status'] ?? '');
$delivery = isset($_POST['delivery_available']) ? 1 : 0;
$pickup = isset($_POST['pickup_available']) ? 1 : 0;
$errors = [];
if ($name === '' || mb_strlen($name) > 150) $errors[] = 'Product name is required (maximum 150 characters).';
if (mb_strlen($description) > 3000) $errors[] = 'Description is too long.';
if (!in_array($category, ['Vegetables', 'Fruits', 'Ground Provisions'], true)) $errors[] = 'Choose a valid category.';
if (!in_array($unit, ['lb', 'kg', 'each', 'dozen', 'bundle', 'bag'], true)) $errors[] = 'Choose a valid unit.';
if (!is_numeric($price) || (float)$price <= 0 || (float)$price > 99999999.99) $errors[] = 'Enter a valid price greater than zero.';
if (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0 || (int)$quantity > 9999999) $errors[] = 'Enter a valid nonnegative quantity.';
if (!in_array($status, ['available', 'sold_out'], true)) $errors[] = 'Choose a valid product status.';
if (!$delivery && !$pickup) $errors[] = 'Select pickup or delivery.';
