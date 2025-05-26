<h2>Список отелей</h2>
<table border="1"  cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Город</th>
        <th>Страна</th>
        <th>Звёзды</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($hotels as $hotel): ?>
        <tr>
            <td><?= $hotel['id'] ?></td>
            <td><?= $hotel['name'] ?></td>
            <td><?= $hotel['city_name'] ?></td>
            <td><?= $hotel['country_name'] ?></td>
            <td><?= $hotel['stars'] ?></td>
            <td><a href="?hotel_id=<?= $hotel['id'] ?>">Проверить правила</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php else: ?>
    <h2>Результаты проверки отеля #<?= $hotelId ?></h2>
    <?php foreach ($results as $msg): ?>
        <p><strong><?= $msg ?></strong></p>
    <?php endforeach; ?>

<?php endif; ?>

<p><a href="rules.php"><button>Управление правилами</button></a></p>
