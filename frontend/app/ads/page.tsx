// Страница списка объявлений с SSR, фильтрацией и пагинацией, с отладочной панелью
import React from 'react';
import AdCard from '../../components/AdCard';
import Button from '../../components/Button';
import { fetchAds, AdsResponse } from '../../api/ads';
import Link from 'next/link';

export default async function AdsPage({ searchParams }: { searchParams?: Record<string, string> }) {
  // SSR: searchParams приходит от Next.js (app router)
  const page = Number(searchParams?.page) || 1;
  const perPage = 10; // 10 объявлений на страницу (per-page для yii2)
  const search = searchParams?.search || '';

  let adsData: AdsResponse & { requestUrl: string; status: number; error?: string } = { items: [], total: 0, page, perPage, requestUrl: '', status: 0 };
  let error = '';
  let requestDebug = { page, 'per-page': perPage, search };
  try {
    adsData = await fetchAds({ page, perPage, search });
    error = adsData.error || '';
  } catch (e: any) {
    error = e.message || 'Ошибка';
  }
  const { items: ads, total, requestUrl, status } = adsData;
  const totalPages = Math.ceil(total / perPage);

  // Отладочная панель: всегда выводим url, статус и ошибку (если есть)
  const debugPanel = (
    <div key="debug-panel">
      <strong>URL запроса:</strong>
      <pre style={{background:'#222', color:'#b8f', fontSize:13, padding:8, margin:'8px 0', borderRadius:6}}>{requestUrl || '—'}</pre>
      <strong>HTTP статус:</strong> <span style={{color:'#8fb'}}>{status}</span>
      {error && <><br /><strong style={{color:'red'}}>Ошибка:</strong> <span style={{color:'red'}}>{error}</span></>}
      <br />
      <strong>Параметры запроса:</strong>
      <pre style={{background:'#222', color:'#b8f', fontSize:13, padding:8, margin:'8px 0', borderRadius:6}}>{JSON.stringify(requestDebug, null, 2)}</pre>
      <strong>Ответ:</strong>
      <pre style={{background:'#222', color:'#8fb', fontSize:13, padding:8, margin:'8px 0', borderRadius:6}}>{JSON.stringify(adsData, null, 2)}</pre>
    </div>
  );

  return [
    (
      <main key="main">
        <h1>Объявления</h1>
        <form method="get" style={{ marginBottom: 24 }}>
          <input
            type="text"
            name="search"
            placeholder="Поиск по объявлениям..."
            defaultValue={search}
            style={{ padding: 8, borderRadius: 4, border: '1px solid #ccc', marginRight: 8 }}
          />
          <Button type="submit">Найти</Button>
        </form>
        {error && <div style={{color: 'red', margin: '1rem 0'}}>{error}</div>}
        {ads.length === 0 && !error && <div>Нет объявлений</div>}
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 24 }}>
          {ads.map(ad => (
            <AdCard key={ad.id} {...ad} />
          ))}
        </div>
        {/* Пагинация */}
        {totalPages > 1 && (
          <div style={{ marginTop: 32, display: 'flex', gap: 8 }}>
            {Array.from({ length: totalPages }, (_, i) => (
              <Link
                key={`page-btn-${i + 1}`}
                href={`?page=${i + 1}${search ? `&search=${encodeURIComponent(search)}` : ''}`}
                scroll={false}
                prefetch={false}
              >
                <Button
                  as={undefined} 
                  style={{ background: page === i + 1 ? '#0051a3' : undefined }}
                >
                  {i + 1}
                </Button>
              </Link>
            ))}
          </div>
        )}
      </main>
    ),
    debugPanel
  ];
}
