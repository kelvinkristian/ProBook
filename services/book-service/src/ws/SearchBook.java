package ws;

import structs.OrderBookStatus;
import structs.SerialBookItem;
import structs.TransferResult;

import javax.jws.WebMethod;
import javax.jws.WebService;
import javax.jws.soap.SOAPBinding;
import java.util.List;

//Service Endpoint Interface
@WebService
@SOAPBinding(style = SOAPBinding.Style.DOCUMENT, use= SOAPBinding.Use.LITERAL) //optional
public interface SearchBook{
    @WebMethod
    List<SerialBookItem> searchBook(String bookName);
    @WebMethod
    List<SerialBookItem> searchBookByGenre(String[] genres);
    @WebMethod
    OrderBookStatus orderBook(String id, int amount, String buyer_account_number, String otp);

}
