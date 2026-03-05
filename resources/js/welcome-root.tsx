import React from 'react';
import { createRoot } from 'react-dom/client';
import HeroSection from '@/components/ui/glassmorphism-trust-hero';
import AboutSection from '@/components/ui/about-section';
import FeaturesSection from '@/components/ui/features-section';
import PricingSection from '@/components/ui/pricing-section';
import FaqSection from '@/components/ui/faq-section';
import LegalPoliciesSection from '@/components/ui/legal-policies-section';
import TermsServiceSection from '@/components/ui/terms-service-section';
import PrivacyPolicySection from '@/components/ui/privacy-policy-section';
import { TestimonialsSectionDemo } from '@/components/blocks/testimonials-demo';
import Header from '@/components/ui/header';

// Header Root
const headerElement = document.getElementById('header-root');
if (headerElement) {
    const data = headerElement.dataset;
    const root = createRoot(headerElement);
    root.render(
        <React.StrictMode>
            <Header 
                logoUrl={data.logo || ""} 
                appName={data.appName || "PDEI"}
                isLoggedIn={data.isLoggedIn === 'true'}
                dashboardUrl={data.dashboardUrl || "/dashboard"}
                loginUrl={data.loginUrl || "/login"}
            />
        </React.StrictMode>
    );
}

// Main Content Root
const rootElement = document.getElementById('welcome-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <HeroSection />
            <AboutSection />
            <FeaturesSection />
            <PricingSection />
            <TestimonialsSectionDemo />
            <FaqSection />
            <LegalPoliciesSection />
            <TermsServiceSection />
            <PrivacyPolicySection />
        </React.StrictMode>
    );
}

