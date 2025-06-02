// Типы пользователя для web и mobile

export interface User {
  id: number;
  username: string;
  email: string;
  avatar?: string;
  // ... другие поля по мере необходимости
}

export interface AuthResponse {
  user: User;
  token: string;
}
