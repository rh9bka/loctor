// Общий layout для всех страниц (SSR)
import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import React from 'react';
import Link from 'next/link';

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: {
    default: 'Доска объявлений',
    template: '%s | Доска объявлений',
  },
  description: 'Современная доска объявлений на Next.js',
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ru">
      <body
        className={`${geistSans.variable} ${geistMono.variable} antialiased`}
      >
        <header style={{ padding: '1rem', borderBottom: '1px solid #eee', marginBottom: '2rem' }}>
          <nav style={{ display: 'flex', gap: '1rem' }}>
            <Link href="/">Главная</Link>
            <Link href="/ads">Объявления</Link>
            <Link href="/login">Вход</Link>
            <Link href="/register">Регистрация</Link>
            <Link href="/profile">Личный кабинет</Link>
          </nav>
        </header>
        {/* children может быть массивом: main и debugPanel */}
        <main style={{ minHeight: '70vh', padding: '1rem' }}>{children}</main>
        <footer style={{ padding: '1rem', borderTop: '1px solid #eee', marginTop: '2rem', textAlign: 'center', color: '#888' }}>
          {new Date().getFullYear()} Доска объявлений
        </footer>
      </body>
    </html>
  );
}
