import React from 'react';
import { createRoot } from 'react-dom/client';
import HeroSection from '@/components/ui/glassmorphism-trust-hero';

const rootElement = document.getElementById('welcome-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <HeroSection />
        </React.StrictMode>
    );
}
