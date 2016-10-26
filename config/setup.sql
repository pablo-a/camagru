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
	name VARCHAR(30) NOT NULL,                   -- champ facultatif
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

INSERT INTO User (pseudo, password, mail, creation_time, active, admin)
	VALUES ('pablo', 'pablo', 'pablo@abril.fr', 20160927111111, 1, 1);
