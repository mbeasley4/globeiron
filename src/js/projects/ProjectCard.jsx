const PinIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
    fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"
    aria-hidden="true" style={{ flexShrink: 0, marginRight: '3px', verticalAlign: 'middle' }}>
    <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/>
    <circle cx="12" cy="10" r="3"/>
  </svg>
);

export default function ProjectCard({ post }) {
  return (
    <article className="post-card">
      {post.featuredImage && (
        <a href={post.permalink} className="post-card__image" tabIndex="-1" aria-hidden="true">
          <img
            src={post.featuredImage.url}
            alt={post.featuredImage.alt}
            loading="lazy"
          />
        </a>
      )}

      <div className="post-card__body">
        <h2 className="post-card__title">
          <a href={post.permalink} rel="bookmark">{post.title}</a>
        </h2>

        {post.excerpt && <p className="post-card__excerpt">{post.excerpt}</p>}

        {((post.types?.length ?? 0) > 0 || post.location || post.year) && (
          <div className="post-card__cats">
            {(post.types ?? []).map(type => (
              <a key={type.id} href={type.url} className="post-card__cat">
                {type.name}
              </a>
            ))}
            {post.location && (
              <span className="post-card__cat post-card__cat--location">
                <PinIcon />{post.location}
              </span>
            )}
            {post.year && (
              <span className="post-card__cat post-card__cat--meta">{post.year}</span>
            )}
          </div>
        )}
      </div>
    </article>
  );
}
