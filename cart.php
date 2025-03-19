<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT products.*, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Главная</a>
            <a href="shop.php">Магазин</a>
            <a href="cart.php">Корзина</a>
            <a href="logout.php">Выйти</a>
        </nav>
    </header>
    <main>
        <h1>Ваша корзина</h1>
        <?php if (empty($cart_items)): ?>
            <p>Ваша корзина пуста.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <h2><?= $item['name'] ?></h2>
                        <p>Количество: <?= $item['quantity'] ?></p>
                        <p>Цена: <?= $item['price'] * $item['quantity'] ?> руб.</p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2023 Магазин. Все права защищены.</p>
    </footer>
</body>

</html>