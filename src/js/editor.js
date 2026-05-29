// Block editor entry point — registers all custom blocks
import '../scss/editor.scss';

const { addFilter } = window.wp.hooks;

addFilter(
  'blocks.registerBlockType',
  'globeiron/core-heading-level-options',
  (settings, name) => {
    if (name !== 'core/heading') {
      return settings;
    }

    return {
      ...settings,
      attributes: {
        ...settings.attributes,
        level: {
          ...settings.attributes.level,
          default: 2,
        },
        levelOptions: {
          type: 'array',
          default: [2, 3, 4, 5, 6],
        },
      },
    };
  }
);

// Import blocks (bundled via editor.js)
import './blocks/hero';
import './blocks/card-grid';
// hero-home and hero-interior are self-contained in /blocks/ and registered via PHP
