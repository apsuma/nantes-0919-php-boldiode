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
    id      int auto_increment
        primary key,
    id_room int          null,
    date    varchar(11)  not null,
    name    varchar(255) null,
    constraint reservation_room
        foreign key (id_room) references room (id)
);


create table reservation_search
(
    id      int auto_increment
        primary key,
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
('http://www.revedesable.com/wp2017/wp-content/uploads/chambre-vue-mer-5.jpg',1),
('https://st.hzcdn.com/simgs/759181be05463f02_3-6574/home-design.jpg',2),
('https://www.hubstairs.com/blog/wp-content/uploads/2018/10/new-300x200.jpg',3),
('https://www.hotel-les-costans.fr/wp-content/uploads/2018/11/chambre-terrasse-mer-1.jpg',4),
('https://moncoachdeco.leroymerlin.fr/library/images/planches/covers/chambre-borddemer3.jpg',5),
('https://www.meublesatlas.fr/wp-content/uploads/2015/09/Chambre.jpg',6),
('http://www.boutchambre.fr/wp-content/uploads/2017/05/deco-chambre-a-la-mer-4.jpg',1),
('http://www.kyriadsaintmaloplage.com/images/photos/hotel-saint-malo,_6_Fb9HCA.jpg',2),
('https://www.story.fr/wp-content/uploads/2018/09/nolte-400x300.jpg',3),
('https://www.turbulences-deco.fr/wp-content/uploads/2015/05/Chambre-blanche-et-rose-via-VTwonen.jpg',4),
('https://s2.lmcdn.fr/multimedia/aa1501407780/1302e25acc3742/chambre-pour-adulte-egayee.jpg',5),
('https://www.hubstairs.com/blog/wp-content/uploads/2017/08/chambre-bleue-canard.jpg',6),
('http://shakemyblog.fr/wp-content/uploads/2018/12/deco-chambre-parentale-moderne-1.jpg',1),
('https://www.for-interieur.fr/wp-content/uploads/2016/05/chambre-en-rotin.jpg',2),
('https://q-xx.bstatic.com/images/hotel/max1024x768/219/219023308.jpg',3),
('https://www.hotel-les-costans.fr/wp-content/uploads/2018/11/chambre-superieure-mer1.jpg',4),
('http://www.gazettedunet.fr/medias/chambre-mer.jpg',5),
('https://media-cdn.tripadvisor.com/media/photo-s/0f/d3/3d/e7/chambre-standard-agapa.jpg',6);
