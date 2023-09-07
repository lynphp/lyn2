###Example you have the request URI or /products/category/mens/shoes
In order to get the mens and shoes as request parameters
Set up a subdirectory under /routes/products/category
Create a file inside the category folder and use the name either category-index.php or index.php
Example:
 routes/
    products/
        category/
            category-index.php or
            index.php
To handle the GET request method. The script inside the category must have the get() method
To handle the POST request method. The script inside the category must have the post() method
To handle the PUT request method. The script inside the category must have the put() method
To handle the DELETE request method. The script inside the category must have the delete() method