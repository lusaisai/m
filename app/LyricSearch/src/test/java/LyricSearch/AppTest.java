package LyricSearch;

import junit.framework.Test;
import junit.framework.TestCase;
import junit.framework.TestSuite;

/**
 * Unit test for simple App.
 */
public class AppTest 
    extends TestCase
{
    /**
     * Create the test case
     *
     * @param testName name of the test case
     */
    public AppTest( String testName )
    {
        super( testName );
    }

    /**
     * @return the suite of tests being tested
     */
    public static Test suite()
    {
        return new TestSuite( AppTest.class );
    }

    /**
     * Rigourous Test :-)
     */
    public void testApp()
    {
        assertEquals("test name cleanup", "回忆里的疯狂", DBTalker.nameCleanUp("光良 - 01.回忆里的疯狂.mp3") );
        assertEquals("test name cleanup", "Angel", DBTalker.nameCleanUp("Angel（天使）.mp3") );
        String songUrl = Search.findSongUrl( "阿桑", "Angel" );
        String songLyric = Search.findLyric(songUrl);
        System.out.println(songLyric);
    }
}
