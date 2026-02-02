<?php
session_start();
require_once __DIR__ . "/../config/database.php";

header('Content-Type: application/json');

$productId   = (int)($_POST['product_id'] ?? 0);
$productName = trim($_POST['product_name'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$message     = trim($_POST['message'] ?? '');
$userId      = $_SESSION['user_id'] ?? null;

if (!$productId || !$productName || !$phone) {
  echo json_encode(['success' => false]);
  exit;
}

$stmt = $conn->prepare("
  INSERT INTO notify_requests
    (product_id, product_name, phone, message, user_id)
  VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "isssi",
  $productId,
  $productName,
  $phone,
  $message,
  $userId
);

$stmt->execute();

echo json_encode(['success' => true]);
