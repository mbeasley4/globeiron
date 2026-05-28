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
  RangeControl,
  TextControl,
  ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageUrl,
    backgroundImageId,
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

  const blockProps = useBlockProps({ className: 'wp-block-globeiron-hero-home' });

  return (
    <>
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

        <PanelBody title={__('Primary Button', 'globeiron')}>
          <TextControl
            label={__('Label', 'globeiron')}
            value={primaryLabel}
            onChange={(val) => setAttributes({ primaryLabel: val })}
          />
          <TextControl
            label={__('URL', 'globeiron')}
            value={primaryUrl}
            onChange={(val) => setAttributes({ primaryUrl: val })}
          />
          <ToggleControl
            label={__('Open in new tab', 'globeiron')}
            checked={primaryNewTab}
            onChange={(val) => setAttributes({ primaryNewTab: val })}
          />
        </PanelBody>

        <PanelBody title={__('Secondary Button', 'globeiron')}>
          <TextControl
            label={__('Label', 'globeiron')}
            value={secondaryLabel}
            onChange={(val) => setAttributes({ secondaryLabel: val })}
          />
          <TextControl
            label={__('URL', 'globeiron')}
            value={secondaryUrl}
            onChange={(val) => setAttributes({ secondaryUrl: val })}
          />
          <ToggleControl
            label={__('Open in new tab', 'globeiron')}
            checked={secondaryNewTab}
            onChange={(val) => setAttributes({ secondaryNewTab: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div
        {...blockProps}
        style={{
          '--hero-bg':      backgroundImageUrl ? `url(${backgroundImageUrl})` : 'none',
          '--hero-overlay': `${overlayOpacity / 100}`,
        }}
      >
        <div className="hero-home__overlay" />
        <div className="hero-home__content">
          <RichText
            tagName="p"
            className="hero-home__eyebrow"
            placeholder={__('Eyebrow text…', 'globeiron')}
            value={eyebrow}
            onChange={(val) => setAttributes({ eyebrow: val })}
            allowedFormats={[]}
          />
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
