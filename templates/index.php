<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <div class="radio-button-group">
        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio" checked="">
            <span class="radio-button__text">Все задачи</span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio">
            <span class="radio-button__text">Повестка дня</span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio">
            <span class="radio-button__text">Завтра</span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio">
            <span class="radio-button__text">Просроченные</span>
        </label>
    </div>

    <label class="checkbox">
        <input id="show-complete-tasks"
            <?php if ($show_complete_tasks == 1) echo "checked";?>
               class="checkbox__input visually-hidden" type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>
<table class="tasks">

    <?php
    foreach ($tasks as $task) 
    {
        if($project == "Все" || $project == $task['category']) {
            ?>
                <tr class="tasks__item task <?php if ($task['readiness'] === 'Да') {
                    echo 'task--completed';
                } elseif ($task['date_of_perfomans'] == date("d.m.Y")) {
                    echo 'task--important';
                } ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox">
                            <span class="checkbox__text"><?php echo htmlspecialchars($task['task']); ?></span>
                        </label>
                    </td>

                    <td class="task__date">
                        <?php echo htmlspecialchars($task['date_of_perfomans']); ?>
                    </td>
                </tr>
            <?php
        }
    }
    ?>

</table>