
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.html" method="post">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $show_complete_tasks == 1 ? "checked" : "" ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
            <?php if ($show_complete_tasks == 1): ?>
                <?php foreach ($tasksData as $key => $item):?>
                <tr class="tasks__item task <?= $item["doneDate"] == !NULL ? "task--completed" : ""?> <?= checkTimeLeft($item["deadline"]) <= 24 ? "task--important" : "" ?>" >
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?= $item["doneDate"] == !NULL ? "checked" : "" ?>>
                            <span class="checkbox__text"><?= htmlspecialchars($item["title"]); ?></span>
                        </label>
                    </td>
                    <td class="task__file">
                        <?php if(!empty($item["file"])): ?>
                        <a class="download-link" href="<?= $item["file"]?>">File</a>
                        <? endif; ?>
                    </td>
                    <td class="task__date"><?= htmlspecialchars($item["deadline"]);?></td>
                </tr>
                <?php endforeach ?>
            <?php else: ?>
                <?php foreach ($tasksData as $key => $item):?>
                <?php if ($item["doneDate"] == NULL): ?>
                    <tr class="tasks__item task <?= $item["doneDate"] == !NULL ? "task--completed" : ""?> <?= checkTimeLeft($item["deadline"]) <= 24 ? "task--important" : "" ?>">
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?= $item["doneDate"] == !NULL ? "checked" : "" ?>>
                                <span class="checkbox__text"><?= htmlspecialchars($item["title"]);?></span>
                            </label>
                        </td>
                        <td class="task__file">
                            <?php if(!empty($item["file"])): ?>
                                <a class="download-link" href="<?= $item["file"]?>">File</a>
                            <? endif; ?>
                        </td>
                        <td class="task__date"><?= htmlspecialchars($item["deadline"]);?></td>
                    </tr>
                <?php endif; ?>
                <?php endforeach;?>
            <? endif; ?>
    </table>