create table if not exists pinyin_map
(
		id integer not null AUTO_INCREMENT,
		chinese_word char(1) not null,
		all_pinyin varchar(255) not null,
		pinyin1 varchar(255) not null,
		pinyin2 varchar(255),
		pinyin3 varchar(255),
		pinyin4 varchar(255),
		pinyin5 varchar(255),
		pinyin6 varchar(255),
		primary key (id)
)
char set utf8
;
