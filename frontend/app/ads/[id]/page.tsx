// SSR-страница одного объявления
import React from 'react';
import { fetchAdById, Ad } from '../../../api/ads';
import Button from '../../../components/Button';

export default async function AdViewPage({ params }: { params: { id: string } }) {
  let ad: Ad | null = null;
  let error = '';
  try {
    ad = await fetchAdById(params.id);
  } catch (e: any) {
    error = e.message || 'Ошибка';
  }
  if (error) {
    return <main><h1>Ошибка</h1><div style={{color: 'red'}}>{error}</div></main>;
  }
  if (!ad) {
    return <main><h1>Объявление не найдено</h1></main>;
  }
  return (
    <main>
      <h1>{ad.title}</h1>
      {ad.image && <img src={ad.image} alt={ad.title} style={{ maxWidth: 400, borderRadius: 8, marginBottom: 16 }} />}
      <div style={{ fontWeight: 'bold', fontSize: 20 }}>{ad.price} ₽</div>
      <p style={{ margin: '16px 0' }}>{ad.description}</p>
      {/* Здесь могут быть кнопки "В избранное", "Связаться" и т.д. */}
      <Button>В избранное</Button>
    </main>
  );
}
