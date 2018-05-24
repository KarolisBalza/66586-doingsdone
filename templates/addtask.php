<div class="modal" <?= $errors["errors"] ? "" : "hidden" ?> id="task_add">
    <button class="modal__close" type="button" name="button" href="/">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" action="index.php" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <p class="form__message"><?= $errors["titleError"] ? "Заполните это поле" : "" ?></p>
            <input class="form__input <?= $errors["titleError"] ? "form__input--error" : "" ?>" type="text" name="name"
                   id="name" value="<?= $postedName ?>" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <?php foreach ($projectsTypes as $item): ?>
                    <?php if ($item["id"] != 1): ?>
                        <option value="<?= $item["id"] ?>"><?= $item["title"] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Срок выполнения</label>
            <p class="form__message"><?= $errors["dateError"] ? "Некорректная дата" : "" ?></p>
            <input class="form__input form__input--date <?= $errors["dateError"] ? "form__input--error" : "" ?>"
                   type="text" name="date" id="date"
                   placeholder="Введите дату и время">
        </div>

        <div class="form__row">
            <label class="form__label" for="preview">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="task_add" value="Добавить">
        </div>
    </form>
</div>
