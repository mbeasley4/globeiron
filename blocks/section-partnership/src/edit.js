import {
  useBlockProps,
  InspectorControls,
  RichText,
} from '@wordpress/block-editor';
import {
  PanelBody,
  SelectControl,
  Spinner,
  Notice,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
  const { eyebrow, heading, subheading, bgStyle } = attributes;

  const blockProps = useBlockProps( {
    className: `wp-block-globeiron-section-partnership is-style-${ bgStyle }`,
  } );

  // ── Fetch published partners from the REST API ────────────────────────────
  // logo_url is a registered REST field (see inc/post-types.php).
  // meta._partner_url is exposed via register_post_meta show_in_rest: true.
  const { partners, isLoading } = useSelect( ( select ) => {
    const query = {
      per_page: 100,
      orderby:  'title',
      order:    'asc',
      status:   'publish',
    };
    const records = select( coreStore ).getEntityRecords(
      'postType',
      'partner',
      query
    );
    return {
      partners:  records ?? [],
      isLoading: records === undefined,
    };
  }, [] );

  // ── Preview ───────────────────────────────────────────────────────────────
  return (
    <>
      {/* ─── Sidebar ──────────────────────────────────────────────────── */}
      <InspectorControls>
        <PanelBody title={ __( 'Section', 'globeiron' ) } initialOpen>
          <SelectControl
            label={ __( 'Background Style', 'globeiron' ) }
            value={ bgStyle }
            options={ [
              { label: __( 'Dark',  'globeiron' ), value: 'dark'  },
              { label: __( 'Light', 'globeiron' ), value: 'light' },
              { label: __( 'Brand', 'globeiron' ), value: 'brand' },
            ] }
            onChange={ ( val ) => setAttributes( { bgStyle: val } ) }
          />
        </PanelBody>

        <PanelBody title={ __( 'Partners', 'globeiron' ) } initialOpen={ false }>
          { isLoading && <Spinner /> }
          { ! isLoading && partners.length === 0 && (
            <Notice status="warning" isDismissible={ false }>
              { __( 'No published partners found. Add some under Partners in the admin menu.', 'globeiron' ) }
            </Notice>
          ) }
          { ! isLoading && partners.length > 0 && (
            <p style={ { margin: 0, fontSize: 12, color: '#757575' } }>
              { partners.length === 1
                ? __( '1 partner found. Manage them under Partners in the admin menu.', 'globeiron' )
                : `${ partners.length } ${ __( 'partners found. Manage them under Partners in the admin menu.', 'globeiron' ) }`
              }
            </p>
          ) }
        </PanelBody>
      </InspectorControls>

      {/* ─── Editor preview ───────────────────────────────────────────── */}
      <section { ...blockProps }>
        <div className="partners__inner">

          {/* Header — editable inline */}
          <header className="partners__header">
            <RichText
              tagName="p"
              className="partners__eyebrow"
              placeholder={ __( 'Eyebrow text…', 'globeiron' ) }
              value={ eyebrow }
              onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
              allowedFormats={ [] }
            />
            <RichText
              tagName="h2"
              className="partners__heading"
              placeholder={ __( 'Section heading…', 'globeiron' ) }
              value={ heading }
              onChange={ ( val ) => setAttributes( { heading: val } ) }
              allowedFormats={ [] }
            />
            <RichText
              tagName="p"
              className="partners__subheading"
              placeholder={ __( 'Supporting line…', 'globeiron' ) }
              value={ subheading }
              onChange={ ( val ) => setAttributes( { subheading: val } ) }
              allowedFormats={ [ 'core/bold', 'core/italic' ] }
            />
          </header>

          {/* Partner grid — live from CPT */}
          { isLoading && (
            <div style={ { textAlign: 'center', padding: '2rem' } }>
              <Spinner />
            </div>
          ) }

          { ! isLoading && partners.length > 0 && (
            <div className="partners__marquee">
              <ul className="partners__track" role="list">
                { partners.map( ( partner ) => {
                  const name    = partner.title?.rendered ?? '';
                  const logoUrl = partner.logo_url ?? '';
                  return (
                    <li key={ partner.id } className="partners__tile">
                      <div className="partners__tile-inner">
                        { logoUrl ? (
                          <img
                            src={ logoUrl }
                            alt={ name }
                            className="partners__logo"
                          />
                        ) : (
                          <span className="partners__logo-placeholder">{ name }</span>
                        ) }
                      </div>
                    </li>
                  );
                } ) }
              </ul>
            </div>
          ) }

          { ! isLoading && partners.length === 0 && (
            <p style={ {
              textAlign: 'center',
              opacity: 0.5,
              padding: '2rem 0',
              fontStyle: 'italic',
            } }>
              { __( 'No partners yet — add some under Partners in the admin.', 'globeiron' ) }
            </p>
          ) }

        </div>
      </section>
    </>
  );
}
