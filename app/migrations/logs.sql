create table if not exists playlogs
(
	id integer not null AUTO_INCREMENT,
	user_id integer not null default -1,
	song_id integer not null,
	play_ts timestamp not null default current_timestamp,
	is_deleted bool not null default 0,
	primary key (id)
)
char set utf8
;
