import { Favorite, FavoritesResponse } from '../types/favorite';

export const API_BASE_URL = 'http://api.loctor.loc/v1';

export async function fetchFavorites(token: string, page = 1, perPage = 20): Promise<FavoritesResponse | { error: string }> {
  const params = new URLSearchParams();
  params.set('page', String(page));
  params.set('per-page', String(perPage));
  const res = await fetch(`${API_BASE_URL}/ads/favorites?${params.toString()}`, {
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    return { error: err.message || 'Ошибка получения избранного' };
  }
  return await res.json();
}

export async function addToFavorites(token: string, adId: number): Promise<{ success: boolean; error?: string }> {
  const res = await fetch(`${API_BASE_URL}/ads/${adId}/add-to-favorites`, {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    return { success: false, error: err.message || 'Ошибка добавления' };
  }
  return { success: true };
}

export async function removeFromFavorites(token: string, adId: number): Promise<{ success: boolean; error?: string }> {
  const res = await fetch(`${API_BASE_URL}/ads/${adId}/remove-from-favorites`, {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    return { success: false, error: err.message || 'Ошибка удаления' };
  }
  return { success: true };
}
