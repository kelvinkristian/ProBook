package ws;

import com.google.gson.Gson;
import structs.OrderBookStatus;
import structs.SearchBookResult;
import structs.SerialBookItem;
import structs.TransferResult;

import javax.jws.WebService;
import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.sql.*;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;

@WebService(endpointInterface = "ws.SearchBook")
public class SearchBookImpl implements SearchBook {
    public Config config = new Config();
    @Override
    public List<SerialBookItem> searchBook(String bookName) {
        System.out.println(bookName);
        HttpURLConnection c = null;
        try {
            URL u = new URL("https://www.googleapis.com/books/v1/volumes?q=" + bookName.replace(' ', '+'));
            c = (HttpURLConnection) u.openConnection();
            c.setRequestMethod("GET");
            c.setUseCaches(false);
            c.connect();
            BufferedReader br = new BufferedReader(new InputStreamReader(c.getInputStream()));
            StringBuilder sb = new StringBuilder();
            String line;
            while ((line = br.readLine()) != null) {
                sb.append(line + "\n");
            }
            br.close();

            Gson gson = new Gson();
            String parsedString = sb.toString();
            System.out.println(parsedString);

            SearchBookResult result = gson.fromJson(parsedString, SearchBookResult.class);
            this.saveBook(result.serialize());

            return result.serialize();

        } catch (Exception ex) {
            System.out.println(ex);
            System.out.println("Error getting books");
        } finally {
            if (c != null) {
                try {
                    c.disconnect();
                } catch (Exception ex) {
                    System.out.println("Error closing connection");
                }
            }
        }
        return null;
    }

    @Override
    public List<SerialBookItem> searchBookByGenre(String[] genres) {
        List<SerialBookItem> list = new ArrayList<>();
        HashMap<String, Integer> hashMap = new HashMap<>();
        System.out.println(genres[0]);
        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection(config.url, config.username, config.password);
            for (String genre : genres) {
                String query = "SELECT *, sum(ordered_book.amount) AS total " +
                        "FROM book INNER JOIN book_genre ON book.book_id = book_genre.book_id " +
                        "INNER JOIN genre ON book_genre.genre_id = genre.id INNER JOIN " +
                        "ordered_book ON book.book_id = ordered_book.book_id WHERE genre.genre = ?" +
                        " GROUP BY book.book_id ORDER BY total DESC";
                PreparedStatement preparedStatement = con.prepareStatement(query);
                preparedStatement.setString(1, genre);
                ResultSet resultSet = preparedStatement.executeQuery();
                int maxAmount = Integer.MAX_VALUE;
                int totalRow = 0;

                while (resultSet.next()) {
                    if (maxAmount < resultSet.getInt("total")) break;
                    else maxAmount = resultSet.getInt("total");
                    totalRow++;
                    String bookId = resultSet.getString("book_id");

                    if (!hashMap.containsKey(bookId)) {
                        hashMap.put(bookId, list.size());
                        SerialBookItem serialBookItem = new SerialBookItem();
                        serialBookItem.id = bookId;
                        serialBookItem.title = resultSet.getString("title");
                        serialBookItem.description = resultSet.getString("description");
                        serialBookItem.currency = "IDR";
                        serialBookItem.price = resultSet.getDouble("price");
                        serialBookItem.authors = Arrays.asList(resultSet.getString("authors").split(","));
                        serialBookItem.imageLink = resultSet.getString("img");
                        list.add(serialBookItem);
                    } else list.get(hashMap.get(bookId)).genres.add(genre);
                }

                if (totalRow == 0) {
                    List<SerialBookItem> li = searchBook(genre);
                    if (li != null && li.size() > 0) list.add(li.get(0));
                }
            }
            con.close();
        } catch (Exception e) {
            System.out.println(e.getMessage());
        }
        return list;
    }

    @Override
    public OrderBookStatus orderBook(String id, int amount, String buyer_account_number, String otp) {
        HttpURLConnection connection = null;
        OrderBookStatus orderBookStatus = new OrderBookStatus();
        orderBookStatus.id = id;
        orderBookStatus.amount = amount;
        orderBookStatus.success = false;
        orderBookStatus.reason = "test";
        orderBookStatus.buyer = "someone";
        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection(config.url, config.username, config.password);
            String query = "SELECT price FROM book WHERE book_id = (?)";
            PreparedStatement preparedStatement = con.prepareStatement(query);
            preparedStatement.setString(1, id);
            ResultSet resultSet = preparedStatement.executeQuery();
            resultSet.next();

            double value = amount * resultSet.getDouble("price");

            String urlParameters = "card_number_sender=" + buyer_account_number
                    + "&card_number_receiver=" + config.probookCardNumber
                    + "&value=" + value
                    + "&otp=" + otp;
            String url = config.transferEndpoint;


            connection = (HttpURLConnection) new URL(url).openConnection();
            connection.setDoOutput(true); // Triggers POST.
            connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
            connection.setRequestProperty("Accept-Charset", urlParameters);
            connection.setRequestMethod("POST");

            try (OutputStream output = connection.getOutputStream()) {
                output.write(urlParameters.getBytes(StandardCharsets.UTF_8));
            } catch (Exception e) {
                System.out.println(e.getMessage());
            }

            InputStream response = connection.getInputStream();
            BufferedReader br = new BufferedReader(new InputStreamReader(response));
            StringBuilder sb = new StringBuilder();
            String line;
            while ((line = br.readLine()) != null) {
                sb.append(line + "\n");
            }
            br.close();

            Gson gson = new Gson();
            String parsedString = sb.toString();
            System.out.println(parsedString);

            TransferResult transferResult = gson.fromJson(parsedString, TransferResult.class);
            orderBookStatus.success = transferResult.success;
            orderBookStatus.reason = transferResult.message;

            if (!orderBookStatus.success) {
                return orderBookStatus;
            }

            String insert_book = "INSERT INTO ordered_book (book_id, amount, buyer_account_number) VALUES (?, ?, ?)";
            preparedStatement = con.prepareStatement(insert_book);
            preparedStatement.setString(1, id);
            preparedStatement.setInt(2, amount);
            preparedStatement.setString(3, buyer_account_number);
            preparedStatement.executeUpdate();
            con.close();
        } catch (Exception ex) {
            System.out.println(ex);
            System.out.println("Error Transfer");
        } finally {
            if (connection != null) {
                try {
                    connection.disconnect();
                } catch (Exception ex) {
                    System.out.println("Error closing connection");
                }
            }
        }

        System.out.println(orderBookStatus.reason);

        return orderBookStatus;
    }

    public void saveBook(List<SerialBookItem> serialBookItems) {
        try {
            for (SerialBookItem item : serialBookItems) {
                Class.forName("com.mysql.jdbc.Driver");
                String book_id = item.id;
                String title = item.title;
                String description = item.description;
                List<String> categories = item.genres;
                List<String> authors = item.authors;
                String img = item.imageLink;
                Double price;
                if (item.price == null) {
                    price = 0.0;
                } else {
                    price = item.price;
                }
                String input_author = "";
                for (String author : authors) {
                    input_author += author;
                    if (authors.indexOf(author) == authors.size() - 1) input_author += ", ";
                }

                Connection con = DriverManager.getConnection(config.url, config.username, config.password);
                String find_book = "SELECT book_id FROM book WHERE book_id = (?)";
                PreparedStatement preparedStatement = con.prepareStatement(find_book);
                preparedStatement.setString(1, book_id);
                ResultSet rs1 = preparedStatement.executeQuery();
                if (!rs1.first()) {
                    String insert_book = "INSERT INTO book"
                            + "(book_id, title, authors, description, price, img) VALUES"
                            + "(?,?,?,?,?,?)";
                    preparedStatement = con.prepareStatement(insert_book);
                    preparedStatement.setString(1, book_id);
                    preparedStatement.setString(2, title);
                    preparedStatement.setString(3, input_author);
                    preparedStatement.setString(4, description);
                    preparedStatement.setDouble(5, price);
                    preparedStatement.setString(6, img);
                    preparedStatement.executeUpdate();
                    for (String category : categories) {
                        String find_genre = "SELECT genre FROM genre WHERE genre = (?)";
                        preparedStatement = con.prepareStatement(find_genre);
                        preparedStatement.setString(1, category);
                        ResultSet rs2 = preparedStatement.executeQuery();
                        if (!rs2.first()) {
                            String insert_genre = "INSERT INTO genre (genre) VALUES (?)";
                            preparedStatement = con.prepareStatement(insert_genre);
                            preparedStatement.setString(1, category);
                            preparedStatement.executeUpdate();
                        }
                        String insert_book_genre = "INSERT INTO book_genre (book_id, genre_id)"
                                + " VALUES (?, (SELECT id FROM genre WHERE genre = ?))";
                        preparedStatement = con.prepareStatement(insert_book_genre);
                        preparedStatement.setString(1, book_id);
                        preparedStatement.setString(2, category);
                        preparedStatement.executeUpdate();
                    }
                }
                con.close();
            }
        } catch (Exception e) {
            System.out.println(e);
        }
    }
}
