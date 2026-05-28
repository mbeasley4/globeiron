import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  RichText,
  BlockControls,
  AlignmentControl,
} from '@wordpress/block-editor';
import {
  PanelBody,
  Button,
  RangeControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageUrl,
    backgroundImageId,
    overlayOpacity,
    title,
    subtitle,
    textAlign,
  } = attributes;

  const blockProps = useBlockProps({
    className: `wp-block-globeiron-hero-interior has-text-align-${textAlign}`,
  });

  const overlayStyle = {
    '--hero-bg':      backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
    '--hero-overlay': `${overlayOpacity / 100}`,
  };

  return (
    <>
      <BlockControls group="block">
        <AlignmentControl
          value={textAlign}
          onChange={(val) => setAttributes({ textAlign: val || 'left' })}
        />
      </BlockControls>

      <InspectorControls>
        <PanelBody title={__('Background Image', 'globeiron')} initialOpen>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) =>
                setAttributes({ backgroundImageUrl: media.url, backgroundImageId: media.id })
              }
              allowedTypes={['image']}
              value={backgroundImageId}
              render={({ open }) => (
                <>
                  {backgroundImageUrl && (
                    <img
                      src={backgroundImageUrl}
                      alt=""
                      style={{ width: '100%', marginBottom: 8, borderRadius: 4 }}
                    />
                  )}
                  <Button variant="secondary" onClick={open} style={{ width: '100%' }}>
                    {backgroundImageUrl
                      ? __('Replace Image', 'globeiron')
                      : __('Choose Image', 'globeiron')}
                  </Button>
                  {backgroundImageUrl && (
                    <Button
                      isDestructive
                      variant="tertiary"
                      onClick={() =>
                        setAttributes({ backgroundImageUrl: '', backgroundImageId: 0 })
                      }
                      style={{ width: '100%', marginTop: 4 }}
                    >
                      {__('Remove Image', 'globeiron')}
                    </Button>
                  )}
                </>
              )}
            />
          </MediaUploadCheck>

          <RangeControl
            label={__('Overlay Opacity (%)', 'globeiron')}
            value={overlayOpacity}
            onChange={(val) => setAttributes({ overlayOpacity: val })}
            min={0}
            max={90}
            step={5}
            style={{ marginTop: 16 }}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps} style={overlayStyle}>
        <div className="hero-interior__overlay" />
        <div className="hero-interior__content">
          <RichText
            tagName="h1"
            className="hero-interior__title"
            placeholder={__('Page title…', 'globeiron')}
            value={title}
            onChange={(val) => setAttributes({ title: val })}
            allowedFormats={[]}
          />
          <RichText
            tagName="p"
            className="hero-interior__subtitle"
            placeholder={__('Optional subtitle…', 'globeiron')}
            value={subtitle}
            onChange={(val) => setAttributes({ subtitle: val })}
            allowedFormats={['core/bold', 'core/italic']}
          />
        </div>
      </div>
    </>
  );
}
