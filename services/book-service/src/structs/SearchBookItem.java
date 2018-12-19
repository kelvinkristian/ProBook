package structs;

import java.util.ArrayList;
import java.util.List;

public class SearchBookItem {
    public String kind = "";
    public String id = "";
    public String etag = "";
    public String selfLink = "";
    public BookSaleInfo saleInfo;
    public BookVolumeInfo volumeInfo;

    public SerialBookItem serialize() {
        SerialBookItem item = new SerialBookItem();
        item.id = this.id;
        item.authors = this.volumeInfo.authors;
        item.description = this.volumeInfo.description;
        item.genres = this.volumeInfo.categories;
        item.title = this.volumeInfo.title;

        try {
            item.currency = this.saleInfo.listPrice.currencyCode;
            item.price = this.saleInfo.listPrice.amount;
        } catch (Exception e) {
            item.currency = "IDR";
            item.price = 0.0;
        }

        try {
            item.imageLink = this.volumeInfo.imageLinks.thumbnail;
        } catch (Exception e) {
            item.imageLink = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT32Z-t0YQRXP3kjs84oNHwSkNJ5j-97EkE28h0ZK39o54Y8x8s";
        }

        return item;
    }
}
