import { User, AuthResponse } from '../types/user';

export const API_BASE_URL = 'http://api.loctor.loc/v1';

export async function fetchUserProfile(token: string): Promise<User | null> {
  const res = await fetch(`${API_BASE_URL}/user/profile`, {
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!res.ok) return null;
  return await res.json();
}

export async function loginUser(email: string, password: string): Promise<AuthResponse | { error: string }> {
  const res = await fetch(`${API_BASE_URL}/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });
  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    return { error: err.message || 'Ошибка авторизации' };
  }
  return await res.json();
}
