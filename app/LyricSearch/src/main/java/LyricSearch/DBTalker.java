package LyricSearch;
import java.sql.*;
import java.util.LinkedList;
import java.util.List;
import java.util.ListIterator;

/**
 * Created with IntelliJ IDEA.
 * User: lusaisai
 * Date: 13-9-29
 */
public class DBTalker {
    private static final String USERNAME = "mav";
    private static final String PASSWORD = "mav";
    private static final String DATABASE = "mav";
    private static final String URL = "jdbc:mysql://localhost:3306/" + DATABASE + "?characterEncoding=UTF-8";
    private Connection con;
    private List<Song> songs = new LinkedList<Song>();

    private static class Song {
        final int id;
        final String artist;
        final String name;

        Song(int id, String artist, String name) {
            this.id = id;
            this.artist = artist;
            this.name = name;
        }

        @Override
        public String toString() {
            return "Song{" +
                    "id=" + id +
                    ", artist='" + artist + '\'' +
                    ", name='" + name + '\'' +
                    "}<br/>";
        }
    }

    public DBTalker() throws SQLException {
        con = DriverManager.getConnection(URL,USERNAME,PASSWORD);
    }

    private void setSongs() {
        String query = "select\n" +
                "s.id, s.name as song_name, ar.name as artist_name\n" +
                "from song s\n" +
                "join album al\n" +
                "on   s.album_id = al.id\n" +
                "join artist ar\n" +
                "on   al.artist_id = ar.id\n" +
//                "limit 30"
                "where s.lrc_lyric is null\n"
                ;
        songs.clear();
        try {
            Statement st = con.createStatement();
            ResultSet rs = st.executeQuery(query);
            while (rs.next()) {
                songs.add( new Song( rs.getInt("id"), rs.getString("artist_name"), nameCleanUp(rs.getString("song_name")) ) );
            }
        } catch (SQLException e) {
            e.printStackTrace();  //To change body of catch statement use File | Settings | File Templates.
        }
    }

    private static class SearchLyrics implements Runnable {
        private List<Song> songs;
        private Connection conn;

        private SearchLyrics(List<Song> songs, Connection conn) {
            this.songs = songs;
            this.conn = conn;
        }

        @Override
        public void run() {
            try {
                Lyricer bLrc = new BaiduLyricer();
                Lyricer lLrc = new Lrc123Lyricer();
                PreparedStatement ps = conn.prepareStatement("update song set lrc_lyric = ? where id = ?");
                conn.setAutoCommit(false);

                for(Song song: songs) {
                    String lrcLyric = bLrc.findLrcLyric(song.artist, song.name);
                    if( lrcLyric.equals("") ) {
                        lrcLyric = lLrc.findLrcLyric(song.artist, song.name);
                    }
                    ps.setString(1,lrcLyric);
                    ps.setInt(2,song.id);
                    ps.executeUpdate();
                }
                conn.commit();
            } catch (Exception e) {
                e.printStackTrace();
            }

        }
    }

    public void lyricUpdate() throws SQLException {
        setSongs();
        int threadCount = 10;
        int songsPerThread = this.songs.size() / threadCount + 1;

        ListIterator li = this.songs.listIterator();

        while ( li.hasNext() ) {
            List<Song> ls = new LinkedList<Song>();
            for( int i = 0; i < songsPerThread; i++ ) {
                if ( li.hasNext() ) ls.add((Song)li.next());
            }
            Thread t = new Thread(new SearchLyrics(ls, this.con));
            t.start();
        }

    }

    public static String nameCleanUp(String input) {
        return input.replaceAll("^.*-","").trim()
                    .replaceAll("[0-9]+\\.*", "").trim()
                    .replaceAll("\\[.*\\]", "").trim()
                    .replaceAll("【.*】", "").trim()
                    .replaceAll("\\(.*\\)", "").trim()
                    .replaceAll("（.*）", "").trim()
                    .replaceAll("\\..*$", "").trim();
    }

}
