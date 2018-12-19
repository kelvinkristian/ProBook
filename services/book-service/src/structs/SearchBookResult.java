package structs;

import ws.SearchBook;

import java.util.ArrayList;
import java.util.List;

public class SearchBookResult {
    public String kind = "";
    public List<SearchBookItem> items = new ArrayList<SearchBookItem>();

    public List<SerialBookItem> serialize() {
        List<SerialBookItem> result = new ArrayList<SerialBookItem>();
        for (SearchBookItem item : this.items) {
            System.out.println(item.id);
            System.out.println(item.serialize().id);
            result.add(item.serialize());
        }

        return result;
    }
}
