<h2>Добавить условие для правила #<?= htmlspecialchars($ruleId) ?></h2>
<form method="post">
    <label>Поле:
        <select name="field" id="field-select" onchange="toggleValueInput()">
            <?php foreach ($availableFields as $key => $label): ?>
                <option value="<?= $key ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Оператор:
        <select name="operator" id="operator-select">
            <?php foreach ($availableOperators as $key => $label): ?>
                <option value="<?= $key ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Значение:
        <span id="text-value">
            <input type="text" name="value">
        </span>
        <span id="binary-value" style="display:none">
            <select name="value_binary">
                <option value="1">Да</option>
                <option value="0">Нет</option>
            </select>
        </span>
        <span id="select-value" style="display:none">
            <select name="value_select" id="value-select-dropdown"></select>
        </span>
    </label><br><br>

    <button type="submit" onclick="return setFinalValue()">Добавить условие</button>
</form>

<p><a href="rules.php?action=edit&id=<?= htmlspecialchars($ruleId) ?>">← Назад к правилу</a></p>

<script>
    const binaryFields = <?= json_encode($binaryFields) ?>;
    const selectFields = <?= json_encode($selectFields) ?>;
    const selectOptions = <?= json_encode($selectOptions) ?>;

    function toggleValueInput() {
        const field = document.getElementById('field-select').value;
        const isBinary = binaryFields.includes(field);
        const isSelect = selectFields.includes(field);

        document.getElementById('text-value').style.display = (!isBinary && !isSelect) ? 'inline' : 'none';
        document.getElementById('binary-value').style.display = isBinary ? 'inline' : 'none';
        document.getElementById('select-value').style.display = isSelect ? 'inline' : 'none';

        const operatorSelect = document.getElementById('operator-select');
        const validOps = ['=', '!='];
        if (isSelect || isBinary) {
            for (const opt of operatorSelect.options) {
                opt.disabled = !validOps.includes(opt.value);
            }
        } else {
            for (const opt of operatorSelect.options) {
                opt.disabled = false;
            }
        }

        if (isSelect) {
            const dropdown = document.getElementById('value-select-dropdown');
            dropdown.innerHTML = '';
            for (const [val, name] of Object.entries(selectOptions[field])) {
                const option = document.createElement('option');
                option.value = val;
                option.textContent = name;
                dropdown.appendChild(option);
            }
        }
    }

    function setFinalValue() {
        const field = document.getElementById('field-select').value;
        const isBinary = binaryFields.includes(field);
        const isSelect = selectFields.includes(field);
        let selected;

        if (isBinary) {
            selected = document.querySelector('select[name=\"value_binary\"]').value;
        } else if (isSelect) {
            selected = document.querySelector('select[name=\"value_select\"]').value;
        } else {
            return true;
        }

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'value';
        hidden.value = selected;
        document.forms[0].appendChild(hidden);
        return true;
    }

    window.onload = toggleValueInput;
</script>
