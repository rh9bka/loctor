import { Ad, AdsResponse } from '../types/ads';

// Базовый URL API для всех запросов
export const API_BASE_URL = 'http://api.loctor.loc/v1';

// Бизнес-логика для работы с объявлениями (ads API)
// Важно: параметры строго соответствуют backend (AdsController, yii2)
// page (1-based), per-page (default 20, можно менять), search

// Универсальная функция для получения объявлений с учётом параметров API
export async function fetchAds({ page = 1, perPage = 20, search = '' }: { page?: number; perPage?: number; search?: string }): Promise<AdsResponse & { requestUrl: string; status: number; error?: string }> {
  const params = new URLSearchParams();
  params.set('page', String(page));
  params.set('per-page', String(perPage));
  if (search) params.set('search', search);

  const url = `${API_BASE_URL}/ads?${params.toString()}`;
  let res: Response | null = null;
  let status = 0;
  let data: any = null;
  try {
    res = await fetch(url);
    status = res.status;
    data = await res.json();
    if (!res.ok) throw new Error('Ошибка загрузки объявлений');
  } catch (e: any) {
    return {
      items: [],
      total: 0,
      page,
      perPage,
      requestUrl: url,
      status: status || 0,
      error: e?.message || 'Ошибка'
    };
  }
  if (Array.isArray(data)) {
    return {
      items: data,
      total: data.length,
      page,
      perPage,
      requestUrl: url,
      status
    };
  }
  return {
    items: data.items || [],
    total: data.total ?? data.items?.length ?? 0,
    page: data.page ?? page,
    perPage: data['per-page'] ?? perPage,
    requestUrl: url,
    status
  };
}

// Получить одно объявление по id
export async function fetchAdById(id: number | string): Promise<Ad | null> {
  const res = await fetch(`${API_BASE_URL}/ads/${id}`);
  if (!res.ok) return null;
  return await res.json();
}
