/**
 * Numbered pagination with leading/trailing ellipsis.
 * Shows: [prev] [1] [...] [n-1] [n] [n+1] [...] [last] [next]
 */
function buildPageRange(current, total) {
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1);
  }

  const pages = new Set([1, total, current]);
  for (let d = 1; d <= 2; d++) {
    if (current - d >= 1)     pages.add(current - d);
    if (current + d <= total) pages.add(current + d);
  }

  const sorted = Array.from(pages).sort((a, b) => a - b);
  const result = [];

  for (let i = 0; i < sorted.length; i++) {
    result.push(sorted[i]);
    if (i < sorted.length - 1 && sorted[i + 1] - sorted[i] > 1) {
      result.push('…');
    }
  }

  return result;
}

export default function Pagination({ currentPage, totalPages, onPageChange, loading }) {
  if (totalPages <= 1) return null;

  const range = buildPageRange(currentPage, totalPages);

  return (
    <nav className="navigation pagination" aria-label="Posts pagination">
      <div className="nav-links">

        {currentPage > 1 && (
          <button
            className="page-numbers prev"
            onClick={() => onPageChange(currentPage - 1)}
            disabled={loading}
          >
            &larr; Previous
          </button>
        )}

        {range.map((item, i) =>
          item === '…' ? (
            <span key={`dots-${i}`} className="page-numbers dots">&hellip;</span>
          ) : (
            <button
              key={item}
              className={`page-numbers${item === currentPage ? ' current' : ''}`}
              onClick={() => item !== currentPage && !loading && onPageChange(item)}
              disabled={loading}
              aria-current={item === currentPage ? 'page' : undefined}
              aria-disabled={item === currentPage ? 'true' : undefined}
            >
              {item}
            </button>
          )
        )}

        {currentPage < totalPages && (
          <button
            className="page-numbers next"
            onClick={() => onPageChange(currentPage + 1)}
            disabled={loading}
          >
            Next &rarr;
          </button>
        )}

      </div>
    </nav>
  );
}
