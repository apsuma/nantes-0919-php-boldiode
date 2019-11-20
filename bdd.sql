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

create table reservation
(
    id int auto_increment primary key,
    id_room int null,
    date varchar(11) not null,
    name varchar(255) null,
    constraint reservation_room
    foreign key (id_room) references room (id)
);


create table reservation_search
(
    id int auto_increment primary key,
    id_room int null,
    constraint reservation_search_room_id_fk
    foreign key (id_room) references room (id)
);

INSERT INTO admin (login,pwd)
VALUES ('admin','$2y$10$RhIFv70zMDBMkzUPUKUH2u1xSryAb.ZwBm.SRgA/Z0XQOomE8ogrG');

INSERT INTO price (price_summer, price_winter, name)
VALUES
(40,60,'escapade'),
(50,70,'voyage'),
(70,90,'luxe');

INSERT INTO theme (name)
VALUES
('Apaisant'),
('Vivifiant'),
('Tonifiant');

INSERT INTO view (name)
VALUES
('plage'),
('rue'),
('horizon');

INSERT INTO room (name,description,nb_bed,surface,id_price,id_view,id_theme)
VALUES
('Ocean','Superbe chambre',2,40,1,1,1),
('Forest','Chambre ombragée et silencieuse',3,35,2,2,2),
('Mountain','Chambre avec horizon',1,30,3,3,3),
('Sand','Magnifique chambre chaleureuse',3,45,1,2,3),
('Grass','Petite chambre pour une escale',1,20,1,2,1),
('City','Chambre pour ceux à qui la ville manque',3,25,2,3,1);

INSERT INTO picture (image,id_room)
VALUES
('image5dce70777b4ec.jpg',1),
('image5dce70777df19.jpg',2),
('image5dce707780946.jpg',3),
('image5dce709493ea5.jpg',4),
('image5dce709497d6a.jpg',5),
('image5dce70b2cfaa1.jpg',6),
('image5dce70c93d05a.jpg',1),
('image5dce70c93f78d.jpg',2),
('image5dce70e58fd38.jpg',3),
('image5dce70e5925b1.jpg',4),
('image5dce71003a431.jpg',5),
('image5dce71003f20d.jpg',6),
('image5dce7100469c6.jpg',1),
('image5dce7114d41c7.jpg',2),
('image5dce7120c2ffe.jpg',3),
('image5dce713645975.jpg',4),
('image5dce713648075.jpg',5),
('image5dce7136497ba.jpg',6);
