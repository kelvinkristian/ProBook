package structs;

import java.util.ArrayList;
import java.util.List;

public class BookVolumeInfo {
    public String title = "";
    public String subtitle = "";
    public List<String> authors = new ArrayList<String>();
    public String publisher = "";
    public String publishedDate = "";
    public String description = "";
    public List<String> categories;
    public ImageLinks imageLinks;
}
