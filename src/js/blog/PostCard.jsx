export default function PostCard({ post }) {
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
        {post.categories.length > 0 && (
          <div className="post-card__cats">
            {post.categories.map(cat => (
              <a key={cat.id} href={cat.url} className="post-card__cat">
                {cat.name}
              </a>
            ))}
          </div>
        )}

        <h2 className="post-card__title">
          <a href={post.permalink} rel="bookmark">{post.title}</a>
        </h2>

        <p className="post-card__excerpt">{post.excerpt}</p>

        {post.date && (
          <time className="post-card__date" dateTime={post.dateIso}>{post.date}</time>
        )}
      </div>
    </article>
  );
}
