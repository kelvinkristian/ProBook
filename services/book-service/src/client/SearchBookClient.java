package client;


import java.net.URL;
import java.util.List;
import javax.xml.namespace.QName;
import javax.xml.ws.Service;

import structs.OrderBookStatus;
import structs.SerialBookItem;
import structs.TransferResult;
import ws.SearchBook;

public class SearchBookClient {

    public static void main(String[] args) throws Exception {
        URL url = new URL("http://localhost:9999/ws/search?wsdl");
        QName qname = new QName("http://ws/", "SearchBookImplService");

        Service service = Service.create(url, qname);

        SearchBook bookService = service.getPort(SearchBook.class);

        OrderBookStatus result = bookService.orderBook("eqEEAwAAQBAJ", 1, "111122233334444", "");
        System.out.println(result.reason);
//        for (SerialBookItem item : result) {
//            //bookService.orderBook(item.id, 9999, "Dummy123");
//            System.out.println(item.imageLink);
//        }

        //TransferResult status = bookService.orderBook(result.get(0).id, 1, "test");

        //System.out.println(status.success);
    }

}