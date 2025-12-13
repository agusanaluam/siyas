'use client'

import LandingHeader from '@/components/landing/LandingHeader'
import HeroSection from '@/components/landing/HeroSection'
import CampaignSection from '@/components/landing/CampaignSection'
import AboutPreviewSection from '@/components/landing/AboutPreviewSection'
import NewsSection from '@/components/landing/NewsSection'
import ContactSection from '@/components/landing/ContactSection'
import LandingFooter from '@/components/landing/LandingFooter'
import FloatingWhatsApp from '@/components/FloatingWhatsApp'

export default function Home() {
  return (
    <main className="min-h-screen bg-gradient-to-br from-[#E8F0FF] via-white to-[#FFE7F3] relative overflow-hidden">
      {/* Decorative background elements */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-20 left-10 w-72 h-72 bg-brand-400/15 rounded-full blur-3xl"></div>
        <div className="absolute bottom-20 right-10 w-96 h-96 bg-accent-400/20 rounded-full blur-3xl"></div>
        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[520px] h-[520px] bg-support-400/12 rounded-full blur-3xl"></div>
      </div>
      
      <div className="relative z-10">
        <LandingHeader />
        
        <HeroSection />

        <div className="px-4 md:px-[150px]">
          <CampaignSection />
          
          <AboutPreviewSection />
          
          <NewsSection />
        </div>
        
        <ContactSection />
        
        <LandingFooter />
      </div>

      {/* Floating WhatsApp Button */}
      <FloatingWhatsApp />
    </main>
  )
}
