# Blog Tags Extension
This plugin is an extension to the [RainLab.Blog](https://github.com/rainlab/blog-plugin) plugin. This extension enables tagging blog posts and displaying related articles.

#### Websites using Blog Tags Extension
[Keios Solutions](http://blog.keios.eu/)

Want to show how your website is using this plugin? Feel free to get in touch with a review.

#### Related Posts
The `blogRelated` component can be used to display a post's related articles.

- **Slug** - The target post's slug parameter.
- **Results** - The number of related posts to display.
- **Sort by** - How to sort the related articles (by relevance, title, published date, or updated date).
- **Order** - Direction to sort the results (ascending or descending).
- **Blog post page** - Link to page, where blog post to be displayed.
- **Blog post categories** - Links to pages, where blog post categories to be displayed.

#### Tag List
The `blogTags` component is can be used to display a list of all tags.

- **Hide orphaned tags** - Hides tags with no associated posts.
- **Results** - The number of tags to display.
- **Sort by** - How to sort the tags (by number of posts, title, or created date).
- **Order** - Direction to sort the results (ascending or descending).

#### Tag Search
The `blogTagSearch` component returns all posts with a particular tag.

- **Tag** - The URL parameter used to search for posts.
- **Paginate results** - Determines if the results are paginated or not.
- **Page** - The URL parameter defining the page number.
- **Results** - Number of posts to display per page.
- **Blog post page** - Link to page, where blog post to be displayed.


This component also provides several pagination variables. They are ```totalPosts```, ```postsOnPage```, ```currentPage```, ```resultsPerPage```, ```previousPage```, ```nextPage```, and ```lastPage```. For an example of how to paginate your results, please review the pagination partial. The posts may be loaded through the ```Page``` URL parameter, or through the AJAX framework via the ```onLoadPage()``` method.
