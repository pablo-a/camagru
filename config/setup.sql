CREATE DATABASE IF NOT EXISTS camagru;

USE camagru;

SET NAMES utf8;

CREATE TABLE IF NOT EXISTS user (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	pseudo VARCHAR(50) NOT NULL,
	password VARCHAR(300) NOT NULL,
	mail VARCHAR(100) NOT NULL,
	creation_time DATETIME NOT NULL,
	active SMALLINT NOT NULL DEFAULT 0,
	admin SMALLINT NOT NULL DEFAULT 0,
	PRIMARY KEY (id),                       -- cle primaire sur l'id use
	UNIQUE INDEX ind_uni_pseudo (pseudo),   -- unicite des pseudos
	UNIQUE INDEX ind_uni_mail (mail)        -- unicite des mails
) ENGINE = INNODB;



CREATE TABLE IF NOT EXISTS image (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	location VARCHAR(100) NOT NULL,              -- "path/to/file"
	owner INT UNSIGNED NOT NULL,
	creation_time DATETIME NOT NULL,
	comments_nb INT NOT NULL DEFAULT 0,
	likes_nb INT NOT NULL DEFAULT 0,
	name VARCHAR(100) NOT NULL,
	description TEXT,                            -- champ facultatif
	PRIMARY KEY (id),
	FOREIGN KEY (owner) REFERENCES user (id)     -- le champs owner refere a un id dans la table user.
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS comment (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	content TEXT NOT NULL,								-- corps du commentaire
	author INT UNSIGNED NOT NULL,         -- auteur du commentaire
	creation_time DATETIME NOT NULL,
	image_origin INT UNSIGNED NOT NULL,   -- image liée au commentaire
	comment_origin INT UNSIGNED,         -- nested comments (facultatif)
	PRIMARY KEY (id),
	CONSTRAINT fk_comment_author FOREIGN KEY (author) REFERENCES user(id),
	CONSTRAINT fk_comment_image FOREIGN KEY (image_origin) REFERENCES image(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reset (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_user INT UNSIGNED NOT NULL,
	num VARCHAR(200) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id_user) REFERENCES user (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS filtre (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	location VARCHAR(100) NOT NULL,
	description TEXT,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS likes (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	owner INT UNSIGNED NOT NULL,
	image INT UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (owner) REFERENCES user(id),
	FOREIGN KEY (image) REFERENCES image(id)
) ENGINE=InnoDB;


/* PERMET LES RECHERCHES */
ALTER TABLE image ADD FULLTEXT ind_full_description (description);

INSERT INTO User (pseudo, password, mail, creation_time, active, admin)
	VALUES ('pablo', 'adca72a008cfd65ceb47148b9be37d2c0f6da0b1aaa89a4743f5f6fba3950649a01c9ed0e4b7203f85a785a6f552b6d321a1894cdd1673756a3d6f3945a94713', 'pablo@abril.fr', 20160927111111, 1, 1),
			('qwer', '4a31abe164349501e9954650f743251065dd6a7402565ddac12ecccf44fc328e78b08259e51b3ec15d5136fedb27f4db00dbc232b25507637b9aa99f7dfc39ea', 'pabloabril75@gmail.com', 20161111111111, 1 , 1);

INSERT INTO image (location, owner, creation_time, name)
	VALUES ('../orange.jpg', 1, 20160909000000, 'orange'),
			('img/qwer/photo20161109104955.png', 2, 20161111111211, "pablo le lutin"),
			('img/qwer/photo20161110113548.png', 2, 20160101111111, "pablo mange un chat"),
			('img/qwer/photo20161110115900.png', 2, 20161111000000, "pablo mange encore un chat");

INSERT INTO filtre (location, description)
	VALUES  ("filtre/hatvert.png", "joli chapeau vert"),
			("filtre/clementine.png", "petite clementine"),
			("filtre/banana.png", "une sacre banane"),
			("filtre/icecream.png", "une bonne glace ca fait du bien"),
			("filtre/cat.png", "un joli chat"),
			("filtre/tulipe.png", "des fleurs a offrir"),
			("filtre/pipe.png", "hmmmm du bon tabac");
