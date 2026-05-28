import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const { columns, cards } = attributes;
  const blockProps = useBlockProps.save({
    className: 'wp-block-globeiron-card-grid',
    style: { '--columns': columns },
  });

  return (
    <div {...blockProps}>
      {cards.map((card, i) => (
        <div key={i} className="card">
          <div className="card__title">{card.title}</div>
          <div className="card__body">{card.body}</div>
        </div>
      ))}
    </div>
  );
}
