drop table if exists playlogs_old;

create table if not exists playlogs
(
	user_id integer not null default -1,
	song_id integer not null,
	play_ts timestamp not null default current_timestamp,
	index(song_id),
	index(user_id),
	index(play_ts)
)
char set utf8
;

create table if not exists playlogs_new
(
	user_id integer not null default -1,
	song_id integer not null,
	play_ts timestamp not null default current_timestamp,
	index(song_id),
	index(user_id),
	index(play_ts)
)
char set utf8
;

insert into playlogs_new select * from playlogs;

rename table playlogs to playlogs_old;
rename table playlogs_new to playlogs;

drop table if exists topsongs_old;

create table if not exists topsongs
(
	id integer not null,
	primary key (id)
)
char set utf8
;

create table if not exists topsongs_new
(
	id integer not null,
	primary key (id)
)
char set utf8
;

insert into topsongs_new select * from topsongs;

rename table topsongs to topsongs_old;
rename table topsongs_new to topsongs;
