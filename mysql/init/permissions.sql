CREATE DATABASE IF NOT EXISTS pokehunt_db;

CREATE USER IF NOT EXISTS 'pokehunt_admin'@'%' IDENTIFIED BY 'hdcsnSD682K';

GRANT ALL PRIVILEGES ON pokehunt_db.* TO 'pokehunt_admin'@'%';

FLUSH PRIVILEGES;
