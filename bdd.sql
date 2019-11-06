DROP DATABASE IF EXISTS boldiode;
CREATE DATABASE boldiode;
USE boldiode;
CREATE TABLE price (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    price_summer INT NULL,
    price_winter INT NULL,
    name VARCHAR(255) NULL
);
CREATE TABLE view (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(255) NULL
);
CREATE TABLE theme (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(255) NULL
);
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    login VARCHAR(255) NOT NULL,
    pwd VARCHAR(255) NOT NULL
);
CREATE TABLE article (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);
CREATE TABLE room (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(255) NULL,
    description TEXT NULL,
    nb_bed INT NULL,
    surface INT NULL,
    front_page BOOL NULL,
    id_price INT NULL,
    id_view INT NULL,
    id_theme INT NULL,
    KEY fk_room_price (id_price),
    CONSTRAINT fk_room_price FOREIGN KEY (id_price) REFERENCES price(id),
    KEY fk_room_view (id_view),
    CONSTRAINT fk_room_view FOREIGN KEY (id_view) REFERENCES view(id),
    KEY fk_room_theme (id_theme),
    CONSTRAINT fk_room_theme FOREIGN KEY (id_theme) REFERENCES theme(id)
);
CREATE TABLE picture (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    description VARCHAR(255) NULL,
    image VARCHAR(255) NULL,
    id_room INT NULL,
    KEY fk_picture_room (id_room),
    CONSTRAINT fk_picture_room FOREIGN KEY (id_room) REFERENCES room(id)
);

INSERT INTO admin (login,pwd)
VALUES ('admin','$argon2i$v=19$m=65536,t=4,p=1$WlM4SDE4bVhNRXByblBSQQ$5QkPz90V8CFZbR/MlQaLVJslnAxcZi6jNpg98jZK3Hw');