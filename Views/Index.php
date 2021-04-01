<html>

<body>
    <?php if ($state->stage == 0) : ?>
        <div>
            <h3>Загадайте число</h3>
        </div>
        <form method="post">
            <input type="hidden" name="go" value="1" />
            <button type="submit" value="go"> Я загадал </button>
        </form>
        <div>Результаты</div>
        <table>
            <tr>
                <th>Участники</th>
                <th>Числа</th>
                <th>Достоверность</th>
            </tr>
            <?php foreach ($state->numbers as $key => $value) : ?>
                <tr>
                    <td><?= $key ?>
                    </td>
                    <td>
                        <?php foreach ($value as $num) : ?>
                            <?= $num . ' ' ?>
                        <?php endforeach ?>
                    </td>

                    <td><b><?= $state->ef[$key] ?? '' ?></b></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
    <?php if ($state->stage == 1) : ?>
        <div>
            <h3>Введите загаданное число</h3>
        </div>
        <form method="post">
            <input type="number" required name="number" min="10" max="99" />
            <button type="submit" value="go"> Отправить </button>
        </form>
        <div>Числа экстрасенсов:
            <?php foreach ($state->lastNumbers as $key => $value) : ?>
                <p>
                    <strong><?= $key ?>:</strong><?= $value ?>
                </p>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <div>
    <form method="post">
            <input type="hidden" name="clear" value="1" />
            <button type="submit" value="go"> Сбросить результаты </button>
        </form>
    </div>
</body>

</html>