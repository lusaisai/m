create table if not exists users
	(
		id integer not null AUTO_INCREMENT,
		username varchar(255) not null,
		password varchar(255) not null,
		email varchar(255) not null,
		role varchar(255) not null,
		insert_ts timestamp not null default current_timestamp,
		update_ts timestamp,
		primary key (id)
	)
	char set utf8
	;
