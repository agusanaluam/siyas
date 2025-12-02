'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'

const DEFAULT_SLIDES = [
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600&auto=format&fit=crop',
    title: 'Bantu Mereka yang Membutuhkan',
    description: 'Bantuan sekecil apapun membuat perubahan berarti dalam kehidupan mereka yang membutuhkan'
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=1600&auto=format&fit=crop',
    title: 'Berbagi Kebahagiaan',
    description: 'Senyum mereka adalah kebahagiaan kita semua. Mari berbagi untuk masa depan yang lebih baik.'
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1600&auto=format&fit=crop',
    title: 'Peduli Sesama',
    description: 'Uluran tangan anda sangat berarti bagi kelangsungan hidup dan pendidikan mereka.'
  }
]

interface HeroSlide {
  id: number
  title: string
  description: string
  image: string
}

export default function HeroSection() {
  const [currentSlide, setCurrentSlide] = useState(0)
  const [slides, setSlides] = useState<HeroSlide[]>(DEFAULT_SLIDES)

  useEffect(() => {
    const fetchSlides = async () => {
      try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'
        const response = await fetch(`${apiUrl}/hero-slides`)
        const data = await response.json()
        if (data && data.length > 0) {
          setSlides(data)
        }
      } catch (error) {
        console.error('Error fetching slides:', error)
      }
    }
    fetchSlides()
  }, [])

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length)
    }, 5000)
    return () => clearInterval(timer)
  }, [slides.length])

  const getImageUrl = (path: string) => {
    if (path.startsWith('http')) return path
    return `${process.env.NEXT_PUBLIC_API_URL?.replace('/api', '')}${path}`
  }

  return (
    <section className="relative h-[600px] md:h-[700px] overflow-hidden">
      {slides.map((slide, index) => (
        <div
          key={slide.id}
          className={`absolute inset-0 transition-opacity duration-1000 ${
            index === currentSlide ? 'opacity-100' : 'opacity-0'
          }`}
        >
          {/* Background Image with Overlay */}
          <div 
            className="absolute inset-0 bg-cover bg-center"
            style={{ backgroundImage: `url(${getImageUrl(slide.image)})` }}
          >
            <div className="absolute inset-0 bg-black/40" />
          </div>

          {/* Content */}
          <div className="relative container mx-auto px-4 h-full flex items-center">
            <div className="max-w-2xl text-white">
              <h1 className="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                {slide.title}
              </h1>
              <p className="text-lg md:text-xl mb-8 opacity-90">
                {slide.description}
              </p>
              <Link
                href="/donate"
                className="inline-block bg-brand-600 text-white px-8 py-3 rounded-full font-semibold text-lg hover:bg-brand-700 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1"
              >
                Donasi Sekarang
              </Link>
            </div>
          </div>
        </div>
      ))}

      {/* Navigation Dots */}
      <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrentSlide(index)}
            className={`w-3 h-3 rounded-full transition-all ${
              index === currentSlide ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/80'
            }`}
            aria-label={`Go to slide ${index + 1}`}
          />
        ))}
      </div>

      {/* Arrows */}
      <button 
        onClick={() => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length)}
        className="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white p-2 rounded-full backdrop-blur-sm transition-colors"
      >
        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button 
        onClick={() => setCurrentSlide((prev) => (prev + 1) % slides.length)}
        className="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white p-2 rounded-full backdrop-blur-sm transition-colors"
      >
        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </section>
  )
}
