import React, { ReactNode } from 'react';
import { createRoot } from 'react-dom/client';
import { Ripple, TechOrbitDisplay, AuthTabs } from '@/components/blocks/modern-animated-sign-in';


interface OrbitIcon {
  component: () => ReactNode;
  className: string;
  duration?: number;
  delay?: number;
  radius?: number;
  path?: boolean;
  reverse?: boolean;
}

// Icons for a finance platform (replacing standard devicons)
const iconsArray: OrbitIcon[] = [
  {
    component: () => (
      <svg className="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
         <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    ),
    className: 'border-none bg-transparent',
    duration: 25,
    delay: 10,
    radius: 120,
    path: false,
    reverse: false,
  },
  {
    component: () => (
      <svg className="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
         <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
      </svg>
    ),
    className: 'border-none bg-transparent',
    radius: 190,
    duration: 30,
    path: false,
    reverse: true,
  },
  {
    component: () => (
      <svg className="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
         <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
    ),
    className: 'border-none bg-transparent',
    duration: 20,
    delay: 20,
    radius: 260,
    path: false,
    reverse: false,
  },
  {
    component: () => (
      <svg className="w-8 h-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
         <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
      </svg>
    ),
    className: 'border-none bg-transparent',
    radius: 350,
    duration: 40,
    delay: 5,
    path: false,
    reverse: true,
  },
];

export function LoginApp({ 
    csrfToken, 
    serverErrors, 
    sessionStatus, 
    oldEmail, 
    routeLogin, 
    routePasswordRequest 
}: {
    csrfToken: string;
    serverErrors: Record<string, string[]>;
    sessionStatus: string | null;
    oldEmail: string;
    routeLogin: string;
    routePasswordRequest: string;
}) {

  const goToForgotPassword = () => {
    window.location.href = routePasswordRequest;
  };

  const formFields = {
    csrfToken,
    serverErrors,
    sessionStatus,
    header: 'Welcome back',
    subHeader: 'Sign in to your account',
    fields: [
      {
        label: 'Email',
        name: 'email',
        type: 'email' as const,
        placeholder: 'Enter your email address',
        defaultValue: oldEmail,
        required: true,
      },
      {
        label: 'Password',
        name: 'password',
        type: 'password' as const,
        placeholder: 'Enter your password',
        required: true,
      },
    ],
    submitButton: 'Sign in',
    textVariantButton: 'Forgot password?',
    goTo: goToForgotPassword
  };

  return (
    <section className='flex max-lg:justify-center dark bg-zinc-950 min-h-screen text-white font-sans overflow-hidden'>
      {/* Background Decorators */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none -z-10">
          <div className="absolute top-[30%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px] animate-pulse"></div>
          <div className="absolute top-[60%] -right-[10%] w-[40%] h-[40%] rounded-full bg-cyan-600/10 blur-[120px]" style={{animationDelay: '2s'}}></div>
      </div>
        
      {/* Left Side (Animation/Branding) */}
      <span className='flex flex-col justify-center w-1/2 max-lg:hidden relative z-0'>
        <Ripple mainCircleSize={120} mainCircleOpacity={0.15} numCircles={8} />
        <TechOrbitDisplay iconsArray={iconsArray} text="PDEI Portal" />
      </span>

      {/* Right Side (Form) */}
      <div className='w-full lg:w-1/2 relative z-10'>
        <AuthTabs
          formFields={formFields}
          action={routeLogin}
        />
      </div>
    </section>
  );
}

const rootElement = document.getElementById('login-root');
if (rootElement) {
    const dataset = rootElement.dataset;
    const errorsStr = dataset.errors || "{}";
    let serverErrors = {};
    try {
        serverErrors = JSON.parse(errorsStr);
    } catch(e) {}

    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <LoginApp 
                csrfToken={dataset.csrf || ""}
                serverErrors={serverErrors}
                sessionStatus={dataset.status || null}
                oldEmail={dataset.oldEmail || ""}
                routeLogin={dataset.routeLogin || "/login"}
                routePasswordRequest={dataset.routePasswordRequest || "/forgot-password"}
            />
        </React.StrictMode>
    );
}
