// Страница регистрации (SSR)
import React from 'react';

export const metadata = {
  title: 'Регистрация',
  description: 'Создать новый аккаунт на доске объявлений',
};

export default function RegisterPage() {
  return (
    <main>
      <h1>Регистрация</h1>
      {/* Здесь будет форма регистрации */}
      <form>
        <label htmlFor="email">Email:</label><br />
        <input type="email" id="email" name="email" required /><br />
        <label htmlFor="password">Пароль:</label><br />
        <input type="password" id="password" name="password" required /><br />
        <label htmlFor="password2">Повторите пароль:</label><br />
        <input type="password" id="password2" name="password2" required /><br />
        <button type="submit">Зарегистрироваться</button>
      </form>
    </main>
  );
}
