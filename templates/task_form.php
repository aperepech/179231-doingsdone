<div class="modal">
    <a class="modal__close" href="index.php">Закрыть</a>

    <h2 class="modal__heading">Добавление задачи</h2>

    <?php if(count($projects)>1) { ?>
        <form class="form" action="index.php" method="post" enctype="multipart/form-data">
            <div class="form__row">
                <label class="form__label" for="name">Название <sup>*</sup></label>

                <input class="form__input <?php if($task['name']['error']!=='') {?> form__input--error <?php }?>" type="text" name="name" required id="name" value="<?php echo $task['name']['value'];?>" placeholder="Введите название">
                <span class="form__error"><?php echo $task['name']['error']; ?></span>
            </div>

            <div class="form__row">
                <label class="form__label" for="project">Проект <sup>*</sup></label>

                <select class="form__input form__input--select" name="project" id="project">
                    <?php foreach($projects as $i=>$p)
                    {
                        if($p['id']!=0) { ?>
                            <option value="<?php echo $p['id']; ?>"  <?php if($p['id'] == $task['project_index']['value']) echo 'selected'; ?>  ><?php echo $p['name']; ?></option>
                        <?php
                        }
                    }
                    ?>

                </select>
            </div>

            <div class="form__row">
                <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

                <input class="form__input form__input--date <?php if($task['date_of_perfomans']['error']!=='') {?> form__input--error <?php }?>" required type="text" name="date" id="date" value="<?php echo $task['date_of_perfomans']['value'];?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
                <span class="form__error"><?php echo $task['date_of_perfomans']['error']; ?></span>

            </div>

            <div class="form__row">
                <label class="form__label">Файл</label>

                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                    <label class="button button--transparent" for="preview">
                        <span>Выберите файл</span>
                    </label>
                </div>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    <?php } else { ?>
        <p>Для добавления задачи должен существовать хотя бы один проект</p>
    <?php } ?>
</div>