import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  ToggleControl,
  RadioControl,
  RangeControl,
  Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
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
    backgroundImageId,
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

  const blockProps = useBlockProps({
    className: [
      'wp-block-globeiron-hero',
      isImage ? 'has-bg-image' : 'has-bg-solid',
    ].join(' '),
    style: outerStyle,
  });

  return (
    <>
      <InspectorControls>
        {/* ── Background ─────────────────────────────────────────── */}
        <PanelBody title={__('Background', 'globeiron')} initialOpen>
          <RadioControl
            label={__('Background type', 'globeiron')}
            selected={backgroundType}
            options={[
              { label: __('Solid blue', 'globeiron'), value: 'solid' },
              { label: __('Image', 'globeiron'),      value: 'image' },
            ]}
            onChange={(val) => setAttributes({ backgroundType: val })}
          />

          {isImage && (
            <>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) =>
                    setAttributes({
                      backgroundImageUrl: media.url,
                      backgroundImageId: media.id,
                    })
                  }
                  allowedTypes={['image']}
                  value={backgroundImageId}
                  render={({ open }) => (
                    <div style={{ marginBottom: '8px' }}>
                      {backgroundImageUrl && (
                        <img
                          src={backgroundImageUrl}
                          alt=""
                          style={{
                            width: '100%',
                            height: '80px',
                            objectFit: 'cover',
                            borderRadius: '4px',
                            marginBottom: '6px',
                          }}
                        />
                      )}
                      <Button variant="secondary" onClick={open} style={{ marginRight: '6px' }}>
                        {backgroundImageUrl
                          ? __('Replace image', 'globeiron')
                          : __('Select image', 'globeiron')}
                      </Button>
                      {backgroundImageUrl && (
                        <Button
                          variant="link"
                          isDestructive
                          onClick={() =>
                            setAttributes({ backgroundImageUrl: '', backgroundImageId: 0 })
                          }
                        >
                          {__('Remove', 'globeiron')}
                        </Button>
                      )}
                    </div>
                  )}
                />
              </MediaUploadCheck>

              <RangeControl
                label={__('Overlay opacity (%)', 'globeiron')}
                value={overlayOpacity}
                onChange={(val) => setAttributes({ overlayOpacity: val })}
                min={30}
                max={90}
                step={5}
              />
            </>
          )}
        </PanelBody>

        {/* ── Primary CTA ───────────────────────────────────────── */}
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

        {/* ── Secondary CTA ─────────────────────────────────────── */}
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

      <div {...blockProps}>
        {/* Overlay (only for image background) */}
        {isImage && (
          <div
            className="hero__overlay"
            style={{ '--hero-overlay': overlayOpacity / 100 }}
          />
        )}

        <div className="hero__inner">
          <h1
            className="hero__heading"
            contentEditable
            suppressContentEditableWarning
            onBlur={(e) => setAttributes({ heading: e.target.innerText })}
          >
            {heading}
          </h1>

          <p
            className="hero__content"
            contentEditable
            suppressContentEditableWarning
            onBlur={(e) => setAttributes({ content: e.target.innerText })}
          >
            {content}
          </p>

          <div className="hero__actions">
            <a href={primaryUrl} className="btn btn--primary">
              {primaryLabel}
              {primaryNewTab && (
                <span className="screen-reader-text">
                  {__('(opens in new tab)', 'globeiron')}
                </span>
              )}
            </a>
            <a href={secondaryUrl} className="btn btn--outline-white">
              {secondaryLabel}
              {secondaryNewTab && (
                <span className="screen-reader-text">
                  {__('(opens in new tab)', 'globeiron')}
                </span>
              )}
            </a>
          </div>
        </div>
      </div>
    </>
  );
}
