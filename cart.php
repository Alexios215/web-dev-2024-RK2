<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Получаем товары в корзине пользователя
$stmt = $conn->prepare("
    SELECT products.id, products.name, products.price, products.image, cart.quantity 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = :user_id
");
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
            <table>
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Итого</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" width="50">
                                <?= $item['name'] ?>
                            </td>
                            <td><?= $item['price'] ?> руб.</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $item['price'] * $item['quantity'] ?> руб.</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2023 Магазин. Все права защищены.</p>
    </footer>
</body>

</html>