<h1>Правила</h1>
<p><a href="rules.php?action=add">+ Добавить правило</a></p>

<table border="1" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Агентство</th>
        <th>Название</th>
        <th>Сообщение</th>
        <th>Активно</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($rules as $rule): ?>
        <tr>
            <td><?= $rule['id'] ?></td>
            <td><?= $rule['agency_name'] ?></td>
            <td><?= htmlspecialchars($rule['name']) ?></td>
            <td><?= htmlspecialchars($rule['message']) ?></td>
            <td><?= $rule['is_active'] ? 'Да' : 'Нет' ?></td>
            <td>
                <a href="rules.php?action=edit&id=<?= $rule['id'] ?>">Редактировать</a>
                <a href="rules.php?action=delete&id=<?= $rule['id'] ?>" onclick="return confirm('Удалить правило?')">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="index.php"><- Назад к списку отелей</a></p>