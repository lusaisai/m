package LyricSearch;

import org.apache.commons.io.FileUtils;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

import java.io.File;
import java.io.IOException;
import java.net.URL;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class BaiduLyricer extends Lyricer {
    private static final String SITE = "http://music.baidu.com";
    private static final String URL_PRE = SITE + "/search/lrc?key=";
    private static final String AGENT = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36";

    public String findLrcLyric( String artist, String title ) {
        String url = URL_PRE + title.replaceAll("\\s+", "+") + "+" + artist.replaceAll("\\s+", "+");
        Document doc;
        try {
            doc = Jsoup.connect(url)
                    .userAgent(AGENT)
                    .timeout(10000)
                    .get();
        } catch (Exception e) {
            return "";
        }
        Elements contents = doc.select("#lrc_list li");
        String defaultLyric = "";
        int i = 0;
        for( Element e : contents ) {
            Elements songTitles = e.select(".song-title a");
            Elements songLyrics = e.select(".lrc-content p");
            if ( songTitles.isEmpty() || songLyrics.isEmpty() ) {
                continue;
            } else {
                String songTitle = songTitles.first().attr("title");
                if ( title.toLowerCase().equals(songTitle.toLowerCase()) || i == 0 ) {
                    Elements lrcs = e.select(".down-lrc-btn");
                    if (lrcs.isEmpty()) {
                        continue;
                    }
                    Element lrc = lrcs.first();
                    Pattern pattern = Pattern.compile(".*href.*:'(.*)'.*");
                    Matcher matcher = pattern.matcher(lrc.className());
                    if ( matcher.matches() ) {
                        String lrcUrl = SITE + matcher.group(1);
                        try {
                            File tmp = File.createTempFile("BaiduLyricer", "lrcFile");
                            FileUtils.copyURLToFile(new URL(lrcUrl), tmp);
                            tmp.deleteOnExit();
                            defaultLyric = FileUtils.readFileToString(tmp);
                            if ( title.toLowerCase().equals(songTitle.toLowerCase()) ) {
                                return FileUtils.readFileToString(tmp);
                            }
                        } catch (IOException ignored) {}
                    }
                    i++;
                }
            }
        }

        return defaultLyric;
    }


}
