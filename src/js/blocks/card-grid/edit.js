import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { columns, cards } = attributes;
  const blockProps = useBlockProps({ className: 'wp-block-globeiron-card-grid' });

  const updateCard = (index, field, value) => {
    const updated = cards.map((card, i) =>
      i === index ? { ...card, [field]: value } : card
    );
    setAttributes({ cards: updated });
  };

  const addCard = () => setAttributes({ cards: [...cards, { title: 'New Card', body: 'Card description.' }] });
  const removeCard = (index) => setAttributes({ cards: cards.filter((_, i) => i !== index) });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Grid Settings', 'globeiron')}>
          <RangeControl label={__('Columns', 'globeiron')} value={columns} min={1} max={4}
            onChange={(val) => setAttributes({ columns: val })} />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps} style={{ '--columns': columns }}>
        {cards.map((card, i) => (
          <div key={i} className="card">
            <div className="card__title" contentEditable suppressContentEditableWarning
              onBlur={(e) => updateCard(i, 'title', e.target.innerText)}>{card.title}</div>
            <div className="card__body" contentEditable suppressContentEditableWarning
              onBlur={(e) => updateCard(i, 'body', e.target.innerText)}>{card.body}</div>
            <Button isDestructive isSmall onClick={() => removeCard(i)}
              style={{ marginTop: '8px' }}>{__('Remove', 'globeiron')}</Button>
          </div>
        ))}
        <Button isPrimary onClick={addCard}>{__('+ Add Card', 'globeiron')}</Button>
      </div>
    </>
  );
}
