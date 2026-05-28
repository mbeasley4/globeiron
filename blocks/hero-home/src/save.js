import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const {
    backgroundImageUrl,
    overlayOpacity,
    eyebrow,
    heading,
    subheading,
    primaryLabel,
    primaryUrl,
    primaryNewTab,
    secondaryLabel,
    secondaryUrl,
    secondaryNewTab,
  } = attributes;

  const blockProps = useBlockProps.save({ className: 'wp-block-globeiron-hero-home' });

  const primaryRel   = primaryNewTab   ? 'noopener noreferrer' : undefined;
  const secondaryRel = secondaryNewTab ? 'noopener noreferrer' : undefined;

  return (
    <div
      {...blockProps}
      style={{
        '--hero-bg':      backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
        '--hero-overlay': `${overlayOpacity / 100}`,
      }}
    >
      <div className="hero-home__overlay" />
      <div className="hero-home__content">
        {eyebrow && (
          <RichText.Content tagName="p" className="hero-home__eyebrow" value={eyebrow} />
        )}
        <RichText.Content tagName="h1" className="hero-home__heading" value={heading} />
        <RichText.Content tagName="p" className="hero-home__subheading" value={subheading} />
        <div className="hero-home__actions">
          <a
            href={primaryUrl}
            className="btn btn--primary"
            target={primaryNewTab ? '_blank' : undefined}
            rel={primaryRel}
          >
            {primaryLabel}
          </a>
          <a
            href={secondaryUrl}
            className="btn btn--outline-white"
            target={secondaryNewTab ? '_blank' : undefined}
            rel={secondaryRel}
          >
            {secondaryLabel}
          </a>
        </div>
      </div>
    </div>
  );
}
