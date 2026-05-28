import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, URLInput } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { heading, body, buttonLabel, buttonUrl } = attributes;
  const blockProps = useBlockProps({ className: 'wp-block-globeiron-cta' });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('CTA Settings', 'globeiron')}>
          <TextControl label={__('Button label', 'globeiron')} value={buttonLabel}
            onChange={(val) => setAttributes({ buttonLabel: val })} />
          <URLInput label={__('Button URL', 'globeiron')} value={buttonUrl}
            onChange={(val) => setAttributes({ buttonUrl: val })} />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps}>
        <h2 className="cta__heading" contentEditable suppressContentEditableWarning
          onBlur={(e) => setAttributes({ heading: e.target.innerText })}>{heading}</h2>
        <p className="cta__body" contentEditable suppressContentEditableWarning
          onBlur={(e) => setAttributes({ body: e.target.innerText })}>{body}</p>
        <a href={buttonUrl} className="btn btn--secondary">{buttonLabel}</a>
      </div>
    </>
  );
}
