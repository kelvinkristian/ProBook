package endpoint;

import javax.xml.ws.Endpoint;

import ws.SearchBookImpl;

//Endpoint publisher
public class BookServicePublisher {

    public static void main(String[] args) {
        Endpoint.publish("http://localhost:9999/ws/search", new SearchBookImpl());
    }

}