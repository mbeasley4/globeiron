import { useState } from '@wordpress/element';
import PostCard from './PostCard';
import Pagination from './Pagination';

const SearchIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
    fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"
    aria-hidden="true">
    <circle cx="11" cy="11" r="8"/>
    <path d="m21 21-4.35-4.35"/>
  </svg>
);

export default function BlogGrid({ ajaxUrl, nonce, initialPosts, initialPage, totalPages: initTotal, perPage, blogUrl, categories = [] }) {
  const [posts, setPosts]               = useState(initialPosts);
  const [currentPage, setCurrentPage]   = useState(Number(initialPage) || 1);
  const [totalPages, setTotalPages]     = useState(Number(initTotal) || 1);
  const [loading, setLoading]           = useState(false);
  const [error, setError]               = useState(null);
  const [activeCategory, setActiveCategory] = useState(0);
  const [searchQuery, setSearchQuery]   = useState('');

  const fetchPosts = async (page, catId, search) => {
    setLoading(true);
    setError(null);

    try {
      const body = new FormData();
      body.append('action',   'globeiron_get_posts');
      body.append('nonce',    nonce);
      body.append('page',     page);
      body.append('per_page', perPage);
      body.append('category', catId);
      body.append('search',   search);

      const res = await fetch(ajaxUrl, { method: 'POST', body });
      if (!res.ok) throw new Error(`Server error ${res.status}`);
      const data = await res.json();

      setPosts(data.posts);
      setCurrentPage(data.currentPage);
      setTotalPages(data.totalPages);

      const url = data.currentPage === 1 ? blogUrl : `${blogUrl}page/${data.currentPage}/`;
      history.pushState({ page: data.currentPage }, '', url);
    } catch (err) {
      setError('Failed to load posts. Please try again.');
      console.error('[globeiron blog]', err);
    } finally {
      setLoading(false);
    }
  };

  const handlePageChange = (page) => {
    const el = document.getElementById('blog-grid-root');
    if (el) {
      const stickyHeader = document.querySelector('.tw-sticky');
      const offset = (stickyHeader?.offsetHeight ?? 0) + 24;
      const top = el.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
    }
    fetchPosts(page, activeCategory, searchQuery);
  };

  const handleCategoryChange = (catId) => {
    setActiveCategory(catId);
    fetchPosts(1, catId, searchQuery);
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchPosts(1, activeCategory, searchQuery);
  };

  return (
    <>
      <div className="blog-controls">
        <nav className="blog-filters" aria-label="Filter by category">
          <button
            type="button"
            className={`blog-filters__tab${activeCategory === 0 ? ' is-active' : ''}`}
            onClick={() => handleCategoryChange(0)}
          >
            All
          </button>
          {categories.map(cat => (
            <button
              key={cat.id}
              type="button"
              className={`blog-filters__tab${activeCategory === cat.id ? ' is-active' : ''}`}
              onClick={() => handleCategoryChange(cat.id)}
            >
              {cat.name}
            </button>
          ))}
        </nav>

        <form className="blog-search" onSubmit={handleSearchSubmit} role="search">
          <input
            type="search"
            className="blog-search__input"
            placeholder="Search"
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            aria-label="Search posts"
          />
          <button type="submit" className="blog-search__btn" aria-label="Submit search">
            <SearchIcon />
          </button>
        </form>
      </div>

      {error && <p className="alert alert--error">{error}</p>}

      <div className={`posts-grid${loading ? ' posts-grid--loading' : ''}`}>
        {posts.map(post => <PostCard key={post.id} post={post} />)}
      </div>

      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
        loading={loading}
      />
    </>
  );
}
