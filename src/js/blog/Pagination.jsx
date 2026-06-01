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
  // wp_localize_script serialises PHP ints as strings; coerce both so the
  // strict equality check inside buildPageRange / className works correctly.
  const page  = Number(currentPage);
  const total = Number(totalPages);

  if (total <= 1) return null;

  const range = buildPageRange(page, total);

  return (
    <nav className="navigation pagination" aria-label="Posts pagination">
      <div className="nav-links">

        {page > 1 && (
          <button
            className="page-numbers prev"
            onClick={() => onPageChange(page - 1)}
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
              className={`page-numbers${item === page ? ' current' : ''}`}
              onClick={() => item !== page && !loading && onPageChange(item)}
              disabled={loading}
              aria-current={item === page ? 'page' : undefined}
              aria-disabled={item === page ? 'true' : undefined}
            >
              {item}
            </button>
          )
        )}

        {page < total && (
          <button
            className="page-numbers next"
            onClick={() => onPageChange(page + 1)}
            disabled={loading}
          >
            Next &rarr;
          </button>
        )}

      </div>
    </nav>
  );
}
