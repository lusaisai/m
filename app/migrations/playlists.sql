drop table if exists playlists_old;

create table if not exists playlists
(
	id integer not null AUTO_INCREMENT,
	name varchar(255) not null,
	user_id integer not null default -1,
	song_ids varchar(255) not null,
	insert_ts timestamp not null default current_timestamp,
	update_ts timestamp,
	primary key (id),
	index(user_id)
)
char set utf8
;

create table if not exists playlists_new
(
	id integer not null AUTO_INCREMENT,
	name varchar(255) not null,
	user_id integer not null default -1,
	song_ids varchar(255) not null,
	insert_ts timestamp not null default current_timestamp,
	update_ts timestamp,
	primary key (id),
	index(user_id)
)
char set utf8
;

insert into playlists_new select * from playlists;

rename table playlists to playlists_old;
rename table playlists_new to playlists;
