// Типы для избранного объявления

import { Ad } from './ads';

export interface Favorite {
  id: number;
  ad: Ad;
  user_id: number;
  created_at: string;
}

export interface FavoritesResponse {
  items: Favorite[];
  total: number;
  page: number;
  perPage: number;
}
