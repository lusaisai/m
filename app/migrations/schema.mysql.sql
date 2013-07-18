/* The tables */
create table if not exists artist
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
region varchar(255),
info text,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id)
)
char set utf8
;

create table if not exists album
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
language varchar(255),
info text,
artist_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
constraint foreign key (artist_id) references artist(id) ON DELETE CASCADE
)
char set utf8
;

create table if not exists song
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
lyric text,
album_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
constraint foreign key (album_id) references album(id) ON DELETE CASCADE
)
char set utf8
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
constraint foreign key (artist_id) references artist(id) ON DELETE CASCADE,
constraint foreign key (album_id) references album(id) ON DELETE CASCADE
)
char set utf8
;


/* The new tables for rebuild */
create table if not exists artist_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
region varchar(255),
info text,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id)
)
char set utf8
;

create table if not exists album_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
language varchar(255),
info text,
artist_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
constraint foreign key (artist_id) references artist_new(id) ON DELETE CASCADE
)
char set utf8
;

create table if not exists song_new
(
id integer not null AUTO_INCREMENT,
name varchar(255) not null,
lyric text,
album_id integer,
insert_ts timestamp not null default current_timestamp,
update_ts timestamp,
primary key (id),
constraint foreign key (album_id) references album_new(id) ON DELETE CASCADE
)
char set utf8
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
constraint foreign key (artist_id) references artist_new(id) ON DELETE CASCADE,
constraint foreign key (album_id) references album_new(id) ON DELETE CASCADE
)
char set utf8
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

