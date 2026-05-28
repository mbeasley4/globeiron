import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const {
    backgroundImageUrl,
    heading,
    subheading,
    primaryLabel,
    primaryUrl,
    secondaryLabel,
    secondaryUrl,
  } = attributes;

  const blockProps = useBlockProps.save({ className: 'wp-block-globeiron-hero-home' });

  const overlayStyle = {
    '--hero-bg': backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
  };

  return (
    <div {...blockProps} style={overlayStyle}>
      <div className="hero-home__overlay" />
      <div className="hero-home__content">
        <RichText.Content tagName="h1" className="hero-home__heading" value={heading} />
        <RichText.Content tagName="p" className="hero-home__subheading" value={subheading} />
        <div className="hero-home__actions">
          <a href={primaryUrl} className="btn btn--primary">{primaryLabel}</a>
          <a href={secondaryUrl} className="btn btn--outline-white">{secondaryLabel}</a>
        </div>
      </div>
    </div>
  );
}
