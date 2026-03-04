import React from 'react';
import { createRoot } from 'react-dom/client';
import HeroSection from '@/components/ui/glassmorphism-trust-hero';
import { TestimonialsSectionDemo } from '@/components/blocks/testimonials-demo';

const rootElement = document.getElementById('welcome-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <HeroSection />
            <TestimonialsSectionDemo />
        </React.StrictMode>
    );
}
