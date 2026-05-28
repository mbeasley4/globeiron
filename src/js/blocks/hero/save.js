import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const {
    heading,
    content,
    primaryLabel,
    primaryUrl,
    primaryNewTab,
    secondaryLabel,
    secondaryUrl,
    secondaryNewTab,
    backgroundType,
    backgroundImageUrl,
    overlayOpacity,
  } = attributes;

  const isImage = backgroundType === 'image';

  const outerStyle = isImage && backgroundImageUrl
    ? {
        backgroundImage: `url(${backgroundImageUrl})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
      }
    : {};

  const blockProps = useBlockProps.save({
    className: [
      'wp-block-globeiron-hero',
      isImage ? 'has-bg-image' : 'has-bg-solid',
    ].join(' '),
    style: outerStyle,
  });

  const primaryRel  = primaryNewTab  ? 'noopener noreferrer' : undefined;
  const secondaryRel = secondaryNewTab ? 'noopener noreferrer' : undefined;

  return (
    <div {...blockProps}>
      {isImage && (
        <div
          className="hero__overlay"
          style={{ '--hero-overlay': overlayOpacity / 100 }}
        />
      )}

      <div className="hero__inner">
        <h1 className="hero__heading">{heading}</h1>

        <p className="hero__content">{content}</p>

        <div className="hero__actions">
          <a
            href={primaryUrl}
            className="btn btn--primary"
            target={primaryNewTab ? '_blank' : undefined}
            rel={primaryRel}
          >
            {primaryLabel}
            {primaryNewTab && (
              <span className="screen-reader-text"> (opens in new tab)</span>
            )}
          </a>
          <a
            href={secondaryUrl}
            className="btn btn--outline-white"
            target={secondaryNewTab ? '_blank' : undefined}
            rel={secondaryRel}
          >
            {secondaryLabel}
            {secondaryNewTab && (
              <span className="screen-reader-text"> (opens in new tab)</span>
            )}
          </a>
        </div>
      </div>
    </div>
  );
}
