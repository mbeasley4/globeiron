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
              <span className="post-card__cat post-card__cat--meta">{post.location}</span>
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
