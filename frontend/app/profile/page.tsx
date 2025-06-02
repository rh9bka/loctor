// Страница личного кабинета (SSR, но в будущем будет client)
import React from 'react';

export const metadata = {
  title: 'Личный кабинет',
  description: 'Ваш профиль и объявления',
};

export default function ProfilePage() {
  return (
    <main>
      <h1>Личный кабинет</h1>
      <p>Здесь будет информация о пользователе и его объявлениях.</p>
    </main>
  );
}
