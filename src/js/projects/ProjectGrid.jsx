import { useState } from '@wordpress/element';
import ProjectCard from './ProjectCard';
import Pagination from '../blog/Pagination';

const SearchIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
    fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"
    aria-hidden="true">
    <circle cx="11" cy="11" r="8"/>
    <path d="m21 21-4.35-4.35"/>
  </svg>
);

export default function ProjectGrid({ ajaxUrl, nonce, initialPosts, initialPage, totalPages: initTotal, perPage, archiveUrl, featuredId = 0 }) {
  const [posts, setPosts]             = useState(initialPosts ?? []);
  const [currentPage, setCurrentPage] = useState(Number(initialPage) || 1);
  const [totalPages, setTotalPages]   = useState(Number(initTotal) || 1);
  const [loading, setLoading]         = useState(false);
  const [error, setError]             = useState(null);
  const [searchQuery, setSearchQuery] = useState('');

  const fetchPosts = async (page, search) => {
    setLoading(true);
    setError(null);

    try {
      const body = new FormData();
      body.append('action',   'globeiron_get_projects');
      body.append('nonce',    nonce);
      body.append('page',     page);
      body.append('per_page', perPage);
      body.append('type',     0);
      body.append('search',   search);
      body.append('exclude',  featuredId);

      const res = await fetch(ajaxUrl, { method: 'POST', body });
      if (!res.ok) throw new Error(`Server error ${res.status}`);
      const data = await res.json();

      setPosts(data.posts);
      setCurrentPage(data.currentPage);
      setTotalPages(data.totalPages);

      const url = data.currentPage === 1 ? archiveUrl : `${archiveUrl}page/${data.currentPage}/`;
      history.pushState({ page: data.currentPage }, '', url);
    } catch (err) {
      setError('Failed to load projects. Please try again.');
      console.error('[globeiron projects]', err);
    } finally {
      setLoading(false);
    }
  };

  const handlePageChange = (page) => {
    const el = document.getElementById('projects-grid-root');
    if (el) {
      const stickyHeader = document.querySelector('.tw-sticky');
      const offset = (stickyHeader?.offsetHeight ?? 0) + 24;
      const top = el.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
    }
    fetchPosts(page, searchQuery);
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchPosts(1, searchQuery);
  };

  return (
    <>
      <div className="blog-controls">
        <form className="blog-search" onSubmit={handleSearchSubmit} role="search">
          <input
            type="search"
            className="blog-search__input"
            placeholder="Search"
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            aria-label="Search projects"
          />
          <button type="submit" className="blog-search__btn" aria-label="Submit search">
            <SearchIcon />
          </button>
        </form>
      </div>

      {error && <p className="alert alert--error">{error}</p>}

      <div className={`posts-grid${loading ? ' posts-grid--loading' : ''}`}>
        {posts.map(post => <ProjectCard key={post.id} post={post} />)}
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
