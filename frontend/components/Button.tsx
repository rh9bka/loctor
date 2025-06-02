// Переиспользуемая кнопка для web и mobile
import React from 'react';

type ButtonProps = React.ButtonHTMLAttributes<HTMLButtonElement> & {
  children: React.ReactNode;
};

export default function Button({ children, ...props }: ButtonProps) {
  return (
    <button
      style={{
        padding: '8px 20px',
        borderRadius: 6,
        border: 'none',
        background: '#0070f3',
        color: 'white',
        fontWeight: 500,
        cursor: 'pointer',
        fontSize: 16,
        margin: 4,
      }}
      {...props}
    >
      {children}
    </button>
  );
}
