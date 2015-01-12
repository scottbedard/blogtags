# Blog Tags Extension
This plugin is an extension to the [RainLab.Blog](https://github.com/rainlab/blog-plugin) plugin. This extension allows for the tagging of blog posts, and using tags to find related articles.

### Related Posts
The `Related Post` component can be used to display a post's related articles. This component has three properties...

- **Slug** - The current post's slug parameter.
- **Results** - The number of results to return
- **Method** - The search method to use for finding related posts. "Newest Related" returns the newest post with a common tag, "Most Related" returns tags ordered by frequency of common tags.


### Tag List
The `Tag List` component can be used to display a list of all tags. This component has only one property...

- **Orphaned Tags** - Determines if tags that no longer have associated posts should be displayed or hidden.


### Tag Search
The `Tag Search` component returns all posts with a particular tag. This component has only four properties...

- **Tag** - The URL parameter used to search for posts.
- **Pagination** - Determines if the results are paginated or not.
- **Page** - The URL parameter defining the page of results.
- **resultsPerPage** - Number of results to display per page.

This component provides several pagination variables. They are **totalPosts**, **postsOnPage**, **currentPage**, **resultsPerPage**, **previousPage**, **nextPage**, and **lastPage**. For an example of how to paginate your results, please review the pagination partial. The posts may be loaded through the **Page** URL parameter, or through the AJAX framework via the **onLoadPage()** method.