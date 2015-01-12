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
The `Tag Search` component returns all posts with a particular tag. This component has only one property...

- **Tag** - The tag being searched for.