USE doingsdone;

INSERT INTO users
SET name = "Вася", email = "vasia@gmail.com", password = "1111";
INSERT INTO users
SET name = "Катя", email = "katia@gmail.com", password = "2222";

INSERT INTO projects
SET title = "Все", users_id = 1;
INSERT INTO projects
SET title = "Входящие", users_id = 1;
INSERT INTO projects
SET title = "Учеба", users_id = 1;
INSERT INTO projects
SET title = "Работа", users_id = 2;
INSERT INTO projects
SET title = "Домашние дела", users_id = 2;
INSERT INTO projects
SET title = "Авто", users_id = 2;

INSERT INTO tasks
SET title = "Собеседование в IT компании", deadline = STR_TO_DATE("01-06-2018", "%d-%m-%Y"), projects_id = 4, users_id = 1;
INSERT INTO tasks
SET title = "Выполнить тестовое задание", deadline = STR_TO_DATE("25-05-2018", "%d-%m-%Y"), projects_id = 4, users_id = 1;
INSERT INTO tasks
SET title = "Сделать задание первого раздела", deadline = STR_TO_DATE("21-04-2018", "%d-%m-%Y"), projects_id = 3, users_id = 1;
INSERT INTO tasks
SET title = "Встреча с другом", deadline = STR_TO_DATE("22-04-2018", "%d-%m-%Y"), projects_id = 2, users_id = 2;
INSERT INTO tasks
SET title = "Купить корм для кота", projects_id = 5, users_id = 2;
INSERT INTO tasks
SET title = "Заказать пиццу", projects_id = 5, users_id = 2;

/*получить список из всех проектов для одного пользователя*/
SELECT * FROM projects
WHERE users_id = 1;

/*получить список из всех задач для одного проекта*/
SELECT * FROM tasks
WHERE projects_id = 2;

/*пометить задачу как выполненную*/
UPDATE tasks SET doneDate = NOW()
WHERE id = 5;

/*получить все задачи для завтрашнего дня*/
SELECT deadline FROM tasks
WHERE DAYOFMONTH(NOW()) + 1 = DAYOFMONTH(deadline);

/*обновить название задачи по её идентификатору*/
UPDATE tasks SET title = "новое название"
WHERE id = 3;
