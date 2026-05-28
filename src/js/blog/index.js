import { render } from '@wordpress/element';
import BlogGrid from './BlogGrid';

const root = document.getElementById('blog-grid-root');

if (root && window.globeironBlog) {
  const { ajaxUrl, nonce, initialPosts, totalPages, currentPage, perPage, blogUrl, categories } = window.globeironBlog;

  render(
    <BlogGrid
      ajaxUrl={ajaxUrl}
      nonce={nonce}
      initialPosts={initialPosts}
      initialPage={currentPage}
      totalPages={totalPages}
      perPage={perPage}
      blogUrl={blogUrl}
      categories={categories || []}
    />,
    root
  );
}
