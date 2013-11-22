package LyricSearch;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

/**
 * Created with IntelliJ IDEA.
 * User: lusaisai
 * Date: 13-9-28
 */
public class Search {
    private static final String SITE = "http://www.xiami.com";
    private static final String URL_PRE = SITE + "/search?key=";
    private static final String AGENT = "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/24.0";

    public static String findSongUrl( String artist, String title ) {
        String url = URL_PRE + artist.replaceAll("\\s+", "+") + "+" + title.replaceAll("\\s+", "+");
        Document doc;
        try {
            doc = Jsoup.connect(url)
                    .userAgent(AGENT)
                    .timeout(10000)
                    .get();
        } catch (Exception e) {
            // silent skip if there's any error
            //System.err.println(e.getMessage());
            return "";
        }
        Elements songs = doc.select(".search_result .result_main tbody td.song_name a");
        for (Element song : songs) {
            String href = song.attr("href");
            if ( href.contains("/song") ) {
                return href.replaceAll("\\?.*$", "");
            }
        }
        return "";
    }

    public static String findLyric(String url) {
        if ( ! url.contains(SITE) ) return "";
        Document doc;
        try {
            doc = Jsoup.connect(url)
                    .userAgent(AGENT)
                    .timeout(10000)
                    .get();
        } catch (Exception e) {
            //System.err.println(e.getMessage());
            return "";
        }
        String lyric = "";
        try {
            lyric = doc.select(".lrc_main").first().html().trim();
        } catch (Exception e) {
            //silent skip
        }
        return lyric;
    }

}
