<h2><?= $rule['id'] ? 'Редактировать правило' : 'Создать новое правило' ?></h2>

<form method="post">
    <input type="hidden" name="action" value="<?= $rule['id'] ? 'edit' : 'add' ?>">
    <?php if ($rule['id']): ?>
        <input type="hidden" name="id" value="<?= $rule['id'] ?>">
    <?php endif; ?>

    <label>Агентство:
        <select name="agency_id" required>
            <option value="">Выберите агенство</option>
            <?php foreach ($agencies as $agency): ?>
                <option value="<?= $agency['id'] ?>" <?= $agency['id'] == $rule['agency_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($agency['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Название правила:
        <input type="text" name="name" value="<?= htmlspecialchars($rule['name']) ?>" required>
    </label><br><br>

    <label>Текст для менеджера:
        <input type="text" name="message" value="<?= htmlspecialchars($rule['message']) ?>" required>
    </label><br><br>

    <label>
        <input type="checkbox" name="is_active" <?= $rule['is_active'] ? 'checked' : '' ?>>
        Активно
    </label><br><br>

    <button type="submit">Сохранить</button>
</form>

<hr>
<?php if (!empty($rule['id'])): ?>
    <h3>Условия</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Поле</th>
            <th>Оператор</th>
            <th>Значение</th>
            <th>Действие</th>
        </tr>
        <?php
        $stmt = $this->db->prepare("SELECT * FROM rule_conditions WHERE rule_id = ?");
        $stmt->execute([$rule['id']]);
        $conditions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($conditions as $cond): ?>
            <tr>
                <td><?= htmlspecialchars($cond['field']) ?></td>
                <td><?= htmlspecialchars($cond['operator']) ?></td>
                <td><?= htmlspecialchars($cond['value']) ?></td>
                <td>
                    <a href="rules.php?action=delete_condition&cid=<?= $cond['id'] ?>&rule_id=<?= $rule['id'] ?>"
                       onclick="return confirm('Удалить условие?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="add_condition.php?rule_id=<?= $rule['id'] ?>"><button type="button">+ Добавить условие</button></a></p>
<?php endif; ?>


<p><a href="rules.php"><- Назад к списку правил</a></p>