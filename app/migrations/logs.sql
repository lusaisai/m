drop table if exists playlogs_old;

create table if not exists playlogs
(
	id integer not null AUTO_INCREMENT,
	user_id integer not null default -1,
	song_id integer not null,
	play_ts timestamp not null default current_timestamp,
	is_deleted bool not null default 0,
	primary key (id),
	index(song_id)
)
char set utf8
;

create table if not exists playlogs_new
(
	id integer not null AUTO_INCREMENT,
	user_id integer not null default -1,
	song_id integer not null,
	play_ts timestamp not null default current_timestamp,
	is_deleted bool not null default 0,
	primary key (id),
	index(song_id),
	index(user_id),
	index(play_ts)
)
char set utf8
;

insert into playlogs_new select * from playlogs;

rename table playlogs to playlogs_old;
rename table playlogs_new to playlogs;
