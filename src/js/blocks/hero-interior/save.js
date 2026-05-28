import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const {
    backgroundImageUrl,
    overlayOpacity,
    title,
    subtitle,
    textAlign,
  } = attributes;

  const blockProps = useBlockProps.save({
    className: `wp-block-globeiron-hero-interior has-text-align-${textAlign}`,
  });

  const overlayStyle = {
    '--hero-bg':      backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
    '--hero-overlay': `${overlayOpacity / 100}`,
  };

  return (
    <div {...blockProps} style={overlayStyle}>
      <div className="hero-interior__overlay" />
      <div className="hero-interior__content">
        <RichText.Content tagName="h1" className="hero-interior__title" value={title} />
        {subtitle && (
          <RichText.Content tagName="p" className="hero-interior__subtitle" value={subtitle} />
        )}
      </div>
    </div>
  );
}
