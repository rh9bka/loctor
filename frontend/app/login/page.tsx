// Страница входа (SSR)
import React from 'react';

export const metadata = {
  title: 'Вход',
  description: 'Вход на сайт доски объявлений',
};

export default function LoginPage() {
  return (
    <main>
      <h1>Вход</h1>
      {/* Здесь будет форма входа */}
      <form>
        <label htmlFor="email">Email:</label><br />
        <input type="email" id="email" name="email" required /><br />
        <label htmlFor="password">Пароль:</label><br />
        <input type="password" id="password" name="password" required /><br />
        <button type="submit">Войти</button>
      </form>
    </main>
  );
}
