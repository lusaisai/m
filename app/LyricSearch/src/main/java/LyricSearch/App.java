package LyricSearch;

import java.io.PrintStream;
import java.io.UnsupportedEncodingException;
import java.sql.SQLException;
import java.util.List;

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
