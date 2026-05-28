import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import metadata from './block.json';

// Handles blocks saved with the old explicit className causing duplicate classes.
const deprecated = [
  {
    save({ attributes }) {
      const { heading, body, buttonLabel, buttonUrl } = attributes;
      return (
        <div className="wp-block-globeiron-cta">
          <h2 className="cta__heading">{heading}</h2>
          <p className="cta__body">{body}</p>
          <a href={buttonUrl} className="btn btn--secondary">{buttonLabel}</a>
        </div>
      );
    },
  },
];

registerBlockType(metadata.name, { ...metadata, edit: Edit, save: Save, deprecated });
