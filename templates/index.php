<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <div class="radio-button-group">
        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio" <?php if($filter == 0) echo 'checked';?>>
            <span class="radio-button__text"><a class="task_filter" href="index.php<?php if($id!=0) echo '?id='.$id; ?>">Все задачи</a></span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio" <?php if($filter == 1) echo 'checked';?>>
            <span class="radio-button__text"><a class="task_filter" href="index.php?<?php if($id!=0) echo 'id='.$id.'&'; ?>filter=1">Повестка дня</a></span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio" <?php if($filter == 2) echo 'checked';?>>
            <span class="radio-button__text"><a class="task_filter" href="index.php?<?php if($id!=0) echo 'id='.$id.'&'; ?>filter=2">Завтра</a></span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio" <?php if($filter == 3) echo 'checked';?>>
            <span class="radio-button__text"><a class="task_filter" href="index.php?<?php if($id!=0) echo 'id='.$id.'&'; ?>filter=3">Просроченные</a></span>
        </label>
    </div>

    <label class="checkbox">
        <input id="show-complete-tasks"
            <?php if ($show_complete_tasks === 1) echo "checked";?>
               class="checkbox__input visually-hidden" type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>
<table class="tasks">

    <?php
    foreach ($tasks as $task) 
    {
        if($id === 0 || $id == $task['project_id']) {
            ?>
            <?php if (!($task['date_done'] != null && $show_complete_tasks === 0)) { ?>
                    <tr class="tasks__item task <?php if ($task['date_done'] != null) {
                        echo 'task--completed';
                    } elseif (dateFromBase($task['date_perfomance']) == date("d.m.Y")) {
                        echo 'task--important';
                    } ?>">
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden" type="checkbox">
                                <span class="checkbox__text"><?php echo htmlspecialchars($task['name']); ?></span>
                            </label>
                        </td>

                        <td class="task__date">
                            <?php echo htmlspecialchars(dateFromBase($task['date_perfomance'])); ?>
                        </td>

                        <td class="task__controls">
                            <?php if ($task['date_done'] == null) { ?>

                                <button class="expand-control" type="button" name="button">Выполнить первое задание</button>

                                <ul class="expand-list hidden">
                                    <li class="expand-list__item">
                                        <a href="index.php?<?php if($id!=0) echo 'id='.$id.'&'; ?><?php if($filter!=0) echo 'filter='.$filter.'&'; ?>done=<?php echo $task['id']; ?>">Выполнить</a>
                                    </li>

                                    <li class="expand-list__item">
                                        <a href="index.php?<?php if($id!=0) echo 'id='.$id.'&'; ?><?php if($filter!=0) echo 'filter='.$filter.'&'; ?>del=<?php echo $task['id']; ?>">Удалить</a>
                                    </li>
                                </ul>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php
        }
    }
    ?>

</table>