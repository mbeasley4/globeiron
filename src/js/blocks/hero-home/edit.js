import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  RichText,
} from '@wordpress/block-editor';
import {
  PanelBody,
  Button,
  TextControl,
} from '@wordpress/components';
import { URLInput } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageUrl,
    backgroundImageId,
    heading,
    subheading,
    primaryLabel,
    primaryUrl,
    secondaryLabel,
    secondaryUrl,
  } = attributes;

  const blockProps = useBlockProps({ className: 'wp-block-globeiron-hero-home' });

  const overlayStyle = {
    '--hero-bg': backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
  };

  return (
    <>
      <InspectorControls>
        {/* Background image */}
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

        </PanelBody>

        {/* CTA buttons */}
        <PanelBody title={__('Call-to-Action Buttons', 'globeiron')}>
          <TextControl
            label={__('Primary label', 'globeiron')}
            value={primaryLabel}
            onChange={(val) => setAttributes({ primaryLabel: val })}
          />
          <URLInput
            label={__('Primary URL', 'globeiron')}
            value={primaryUrl}
            onChange={(val) => setAttributes({ primaryUrl: val })}
          />
          <TextControl
            label={__('Secondary label', 'globeiron')}
            value={secondaryLabel}
            onChange={(val) => setAttributes({ secondaryLabel: val })}
          />
          <URLInput
            label={__('Secondary URL', 'globeiron')}
            value={secondaryUrl}
            onChange={(val) => setAttributes({ secondaryUrl: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps} style={overlayStyle}>
        <div className="hero-home__overlay" />
        <div className="hero-home__content">
          <RichText
            tagName="h1"
            className="hero-home__heading"
            placeholder={__('Enter heading…', 'globeiron')}
            value={heading}
            onChange={(val) => setAttributes({ heading: val })}
            allowedFormats={[]}
          />
          <RichText
            tagName="p"
            className="hero-home__subheading"
            placeholder={__('Enter subheading…', 'globeiron')}
            value={subheading}
            onChange={(val) => setAttributes({ subheading: val })}
            allowedFormats={['core/bold', 'core/italic']}
          />
          <div className="hero-home__actions">
            <span className="btn btn--primary">{primaryLabel}</span>
            <span className="btn btn--outline-white">{secondaryLabel}</span>
          </div>
        </div>
      </div>
    </>
  );
}
