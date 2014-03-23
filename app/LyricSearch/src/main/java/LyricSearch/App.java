package LyricSearch;

import java.io.UnsupportedEncodingException;
import java.sql.SQLException;

/**
 * Hello world!
 *
 */
public class App 
{
    public static void main( String[] args ) throws UnsupportedEncodingException, SQLException {
        DBTalker db = new DBTalker();
        db.lyricUpdate();
    }
}
