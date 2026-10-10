SET NAMES utf8mb4;
USE `calc`;

START TRANSACTION;

INSERT INTO `user` (`login`, `name`, `pwd_hash`)
VALUES ('KH', 'Халиулин Кирилл Владимирович', '$2b$12$9jT6.n7r2LEJH7R57r0HwepLIl8px1bCZ0kPa7abrLVlwkFhge6gG');

INSERT INTO `operation` (`login`, `operation`, `x`, `y`, `z`)
VALUES
    ('KH', '+', 2, 3, 5),
    ('KH', '-', 10, 4, 6),
    ('KH', '*', 6, 7, 42);

COMMIT;
