import { render } from '@wordpress/element';
import ProjectGrid from './ProjectGrid';

const root = document.getElementById('projects-grid-root');

if (root && window.globeironProjects) {
  const { ajaxUrl, nonce, initialPosts, totalPages, currentPage, perPage, archiveUrl, types, featuredId } = window.globeironProjects;

  render(
    <ProjectGrid
      ajaxUrl={ajaxUrl}
      nonce={nonce}
      initialPosts={initialPosts}
      initialPage={currentPage}
      totalPages={totalPages}
      perPage={perPage}
      archiveUrl={archiveUrl}
      types={types || []}
      featuredId={featuredId || 0}
    />,
    root
  );
}
