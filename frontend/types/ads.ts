// Типы для объявлений (универсально для web и mobile)

export interface Ad {
  id: number;
  title: string;
  description: string;
  image?: string;
  price?: number;
  created_at?: string;
  // ... другие поля по мере необходимости
}

export interface AdsResponse {
  items: Ad[];
  total: number;
  page: number;
  perPage: number;
}
