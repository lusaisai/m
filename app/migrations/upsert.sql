insert into artist_new
select t.id, w.name, t.name_pinyin, t.region, t.info, t.insert_ts, t.update_ts
from artist_w w
left join artist t
on   w.name = t.name
;

insert into album_new
select t.id, w.name, t.name_pinyin, t.language, t.info, a.id, t.insert_ts, t.update_ts
from album_w w
left join artist_new a
on   w.artist_name = a.name
left join album t
on   w.name = t.name
and  a.id = t.artist_id
;

insert into image_new
select t.id, w.name, a.id, t.album_id, t.insert_ts, t.update_ts
from image_w w
left join artist_new a
on   w.artist_name = a.name
left join image t
on   w.name = t.name
and  a.id = t.artist_id
and  t.album_id is null
where w.album_name is null
;

insert into song_new
select t.id, w.name, t.name_pinyin, w.file_name, t.lyric, t.lrc_lyric, al.id, t.insert_ts, t.update_ts
from song_w w
left join artist_new ar
on   w.artist_name = ar.name
left join album_new al
on   w.album_name = al.name
and  ar.id = al.artist_id
left join (
select s.*, a.artist_id
from song s
join album a
on   s.album_id = a.id
) t
on   w.file_name = t.file_name
and  ar.id = t.artist_id
and  al.id = t.album_id
;


insert into image_new
select t.id, w.name, ar.id, al.id, t.insert_ts, t.update_ts
from image_w w
left join artist_new ar
on   w.artist_name = ar.name
left join album_new al
on   w.album_name = al.name

left join image t
on   w.name = t.name
and  ar.id = t.artist_id
and  al.id = t.album_id

where w.album_name is not null
;
