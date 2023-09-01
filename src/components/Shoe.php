<?php


/**
 * Component to render products catalog
 * 
 * URL:products/catalog/mens/shoes
 * Path: src/routes/products/cataglog/[slug]/index.php
 */
function index($props = [])
{
    return "<div class='container'>
    <div class='lyn-style'>
        <div class='welcome'>
            <h1>This is Lyn PHP Framework</h1>
        </div>
        <div class='welcome-msg'>
            <div>
                Lyn PHP Framework is based on the MVC (Model-View-Controller) pattern, which separates the logic, presentation, and data layers of your application. This makes your code more organized, reusable, and maintainable. Lyn PHP Framework also supports RESTful APIs, database abstraction, caching, authentication, validation, templating, and more. You can use the built-in components or extend them with your own custom classes.
            </div>
            <div>
                Lyn PHP Framework is designed to be simple and intuitive, with a clear and consistent syntax. You don’t need to learn a lot of configuration or conventions to start using it. Just follow the quick start guide and you’ll have a working web app in no time. Lyn PHP Framework also has a comprehensive documentation and a friendly community that can help you with any questions or issues.
            </div>
        </div>
    </div>
</div>";
};
