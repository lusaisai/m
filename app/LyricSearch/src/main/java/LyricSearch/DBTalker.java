package LyricSearch;
import java.io.PrintStream;
import java.io.UnsupportedEncodingException;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

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
    private List<Song> songs = new ArrayList<Song>();

    private static class Song {
        int id;
        String artist;
        String name;

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
                    '}';
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
                "where s.lyric is null\n"
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

    public void lyricUpdate() throws SQLException {
        setSongs();
        PreparedStatement ps = con.prepareStatement("update song set lyric = ? where id = ?");
        con.setAutoCommit(false);
        PrintStream out = null;
        try {
            out = new PrintStream( System.out, true, "UTF-8");
        } catch (UnsupportedEncodingException e) {
//            Silent skip
        }
        for ( Song s : songs ) {
            out.println("Updated: \n" + s.toString());
            String songUrl = Search.findSongUrl( s.artist, s.name );
            String songLyric = Search.findLyric(songUrl);
            ps.setString(1,songLyric);
            ps.setInt(2,s.id);
            ps.executeUpdate();
        }
        con.commit();
    }

    public static String nameCleanUp(String input) {
        return input.replaceAll("^.*-","").trim()
                    .replaceAll("[0-9]+\\.*", "").trim()
                    .replaceAll("\\[.*\\]", "").trim()
                    .replaceAll("\\(.*\\)", "").trim()
                    .replaceAll("\\..*$", "").trim();
    }

}
