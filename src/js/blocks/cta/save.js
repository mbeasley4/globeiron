import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const { heading, body, buttonLabel, buttonUrl } = attributes;
  // No custom className — WP auto-generates 'wp-block-globeiron-cta' from the block name.
  // Passing it again caused duplicate classes and block validation failures.
  const blockProps = useBlockProps.save();

  return (
    <div {...blockProps}>
      <h2 className="cta__heading">{heading}</h2>
      <p className="cta__body">{body}</p>
      <a href={buttonUrl} className="btn btn--secondary">{buttonLabel}</a>
    </div>
  );
}
