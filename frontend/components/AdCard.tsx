// Переиспользуемый UI-компонент карточки объявления
import React from 'react';

type AdCardProps = {
  title: string;
  description: string;
  image?: string;
  price?: number;
  // Можно расширить по мере необходимости
};

export default function AdCard({ title, description, image, price }: AdCardProps) {
  return (
    <div style={{ border: '1px solid #eee', borderRadius: 8, padding: 16, marginBottom: 16, maxWidth: 400 }}>
      {image && <img src={image} alt={title} style={{ width: '100%', borderRadius: 8, marginBottom: 8 }} />}
      <h2 style={{ margin: '8px 0' }}>{title}</h2>
      <p style={{ color: '#666' }}>{description}</p>
      {price !== undefined && <div style={{ fontWeight: 'bold', marginTop: 8 }}>{price} ₽</div>}
    </div>
  );
}
