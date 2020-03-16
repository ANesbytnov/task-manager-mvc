<?
    define('TASK_DONE', 1);
    define('TASK_DONE_TITLE', 'выполнено');
    define('ADMIN_EDITED', 1);
    define('ADMIN_EDITED_TITLE', 'отредактировано администратором');


    define('ADMIN', 'admin');

    function isAdmin() {
        return isset($_SESSION['login']) && $_SESSION['login'] == ADMIN;
    }

    function renderTh($fieldName, $fieldTitle) { ?>
        <th scope="col">
            <span class="sort" data-sort-field="<?= $fieldName ?>"<?
                if (isset($_COOKIE['sortField']) && $_COOKIE['sortField'] == $fieldName) {
                    echo ' data-sort-type="';
                    if (
                        isset($_COOKIE['sortType']) && 
                        in_array($_COOKIE['sortType'], ['asc', 'desc'])
                    ) {
                        echo $_COOKIE['sortType'];
                    } else {
                        echo 'asc';
                    }
                    echo '"';
                } ?>>
                <?= $fieldTitle ?>
            </span>
        </th><?
    }

    if (isset($_SESSION['notice'])) { // Flash-message ?>
        <div class="row">
            <div class="alert alert-primary" role="alert">
                <?= $_SESSION['notice'] ?>
            </div>
        </div><?
        unset($_SESSION['notice']);
    }

    if (isAdmin()) { ?>
        <div class="row">
            <div class="alert alert-primary" role="alert">
                <span>Вы вошли как admin.</span> <a href="/admin/logout">Выйти</a>
            </div>
        </div><?
    } else { ?>
        <div class="row">
            <a href="/admin/login">Авторизация</a>
        </div><?
    }

    if (isset($title) && !empty($title)) { ?>
        <div class="row">
            <h1><?= $title ?></h1>
        </div><?
	} ?>

    <div class="row"><?
        if (is_array($tasks)) {
            if (count($tasks) > 0) { ?>
                <table class="table">
                    <thead>
                        <tr><?
                            renderTh('username', 'Имя пользователя');
                            renderTh('email', 'E-mail');
                            renderTh('description', 'Текст задачи'); ?>
                            <th scope="col">Статус</th><?
                            
                            if (isAdmin()) { ?>
                                <th scope="col">Действие</th><?
                            } ?>
                        </tr>
                    </thead>
                    <tbody><?
                        foreach ($tasks as $task) { ?>
                            <tr>
                                <td><?= isset($task['username']) ? $task['username'] : '' ?></td>
                                <td><?= isset($task['email']) ? $task['email'] : '' ?></td>
                                <td><?
                                    if (isAdmin()) { ?>
                                        <textarea class="form-control task-description" rows="3"><?= isset($task['description']) ? $task['description'] : '' ?></textarea><?
                                    } else { 
                                        echo isset($task['description']) ? $task['description'] : '';
                                    } ?>
                                </td>
                                <td><?
                                    if (isset($task['task_status'])) {
                                        if ($task['task_status'] == TASK_DONE) {
                                            echo TASK_DONE_TITLE;
                                        } else {

                                            if (isAdmin()) { ?>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input task-done" type="checkbox" value="1">
                                                        <?= TASK_DONE_TITLE ?>
                                                    </label>
                                                </div><?
                                            }                              
                                        }
                                    } 

                                    if (isset($task['admin_status'])) {
                                        if ($task['admin_status'] == ADMIN_EDITED) {
                                            echo '<br>' . ADMIN_EDITED_TITLE;
                                        }
                                    } ?>
                                </td><?

                                if (isAdmin()) { ?>
                                    <td><button type="button" class="btn btn-primary task-apply" <?= isset($task['id']) ? 'data-id="' . $task['id'] . '"' : ''?>>Применить</button></td><?
                                } ?>

                            </tr><?
                        } ?>
                    </tbody>
                </table><?
            } else { ?>
                <p>Задач пока нет</p><?
            }
        } else { ?>
            <p>Возникла ошибка при получении списка задач</p><?
        } ?>
    </div><?

    if (isset($page) && isset($pages)) { ?>
        <div class="row pagination">
            <nav aria-label="Page navigation example">
                <ul class="pagination"><?
                    if ($page > 2 && $pages > 2) { ?>
                        <li class="page-item"><a class="page-link" href="/">В начало</a></li><?
                    }

                    if ($page > 1) { ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>"><?= $page - 1 ?></a></li><?
                    } ?>

                    <li class="page-item active">
                        <span class="page-link">
                            <?= $page ?>
                            <span class="sr-only">(текущая)</span>
                        </span>
                    </li><?

                    if ($page < $pages) { ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>"><?= $page + 1 ?></a></li><?
                        
                        if ($page + 1 < $pages) { ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $pages ?>">В конец</a></li><?
                        }
                    } ?>
                </ul>
            </nav>
        </div><?
    } ?>
    
    <hr>
    <div class="row">
        <h2>Добавить задачу</h2>
    </div>
    <form action="/main/addtask" method="post">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Имя пользователя</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="username" name="username" placeholder="Имя пользователя">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">E-mail</label>
            <div class="col-sm-4">
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Укажите e-mail">
            </div>
        </div>
        <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label">Описание задачи</label>
            <div class="col-sm-4">
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Создать задачу</button>
    </form>
</div>