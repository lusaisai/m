/* The tables */
create table if not exists artist
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
name_pinyin varchar(255),
region varchar(255),
info text,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id)
)
char set utf8,
ENGINE = INNODB
;

create table if not exists album
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
name_pinyin varchar(255),
language varchar(255),
info text,
artist_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index(artist_id)
)
char set utf8,
ENGINE = INNODB
;

create table if not exists song
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
name_pinyin varchar(255),
file_name varchar(255) not null,
lyric text,
lrc_lyric text,
album_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index(album_id)
)
char set utf8,
ENGINE = INNODB
;

create table if not exists image
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
artist_id integer,
album_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index (artist_id),
index (album_id)
)
char set utf8,
ENGINE = INNODB
;


/* The new tables for rebuild */
create table if not exists artist_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
name_pinyin varchar(255),
region varchar(255),
info text,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index(name),
index(name_pinyin)
)
char set utf8,
ENGINE = INNODB
;

create table if not exists album_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
name_pinyin varchar(255),
language varchar(255),
info text,
artist_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index(artist_id),
index(name),
index(name_pinyin)
)
char set utf8,
ENGINE = INNODB
;

create table if not exists song_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
name_pinyin varchar(255),
file_name varchar(255) not null,
lyric text,
lrc_lyric text,
album_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index(album_id),
index(name),
index(name_pinyin)
)
char set utf8,
ENGINE = INNODB
;

create table if not exists image_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
artist_id integer,
album_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
index(artist_id),
index(album_id)
)
char set utf8,
ENGINE = INNODB
;


/* The stage tables for data loading */
create table if not exists artist_w
(
name varchar(255) not null
)
char set utf8
;

create table if not exists album_w
(
name varchar(255) not null,
artist_name varchar(255) not null
)
char set utf8
;

create table if not exists song_w
(
name varchar(255) not null,
file_name varchar(255) not null,
artist_name varchar(255) not null,
album_name varchar(255) not null
)
char set utf8
;

create table if not exists image_w
(
name varchar(255) not null,
artist_name varchar(255),
album_name varchar(255)
)
char set utf8
;

/* remove old tables */
drop table if exists image_old;
drop table if exists song_old;
drop table if exists album_old;
drop table if exists artist_old;

