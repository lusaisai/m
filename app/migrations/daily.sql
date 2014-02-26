CREATE DEFINER=`mav`@`localhost` EVENT `Top Songs` ON SCHEDULE EVERY 1 DAY STARTS '2014-02-26 22:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
delete from topsongs;
insert into topsongs
select song_id
from playlogs
group by 1
order by count(*) desc
limit 200
;

END
