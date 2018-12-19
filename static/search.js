function search() {
  let box = document.getElementsByName("query")[0];
  let query = box.value;

  if (query) {
    document.getElementById("book-search").submit();
  } else {
    box.setAttribute("invalid", "");
  }
}
