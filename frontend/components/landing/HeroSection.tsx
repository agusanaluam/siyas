'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'

import { getImageUrl } from '@/lib/utils'

const DEFAULT_SLIDES = [
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600&auto=format&fit=crop',
    title: 'Bantu Mereka yang Membutuhkan',
    description: 'Bantuan sekecil apapun membuat perubahan berarti dalam kehidupan mereka yang membutuhkan'
  },
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
  const [loading, setLoading] = useState(true)
  const [imagesLoaded, setImagesLoaded] = useState<{ [key: number]: boolean }>({})

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
      } finally {
        setLoading(false)
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

  /* Removed local getImageUrl function */

  const handleImageLoad = (slideId: number) => {
    setImagesLoaded(prev => ({ ...prev, [slideId]: true }))
  }

  if (loading) {
    return (
      <section className="relative h-[600px] md:h-[700px] overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300 animate-pulse">
        <div className="absolute inset-0 flex items-center justify-center">
          <div className="text-center">
            <div className="animate-spin rounded-full h-16 w-16 border-b-4 border-white mx-auto mb-4"></div>
            <p className="text-white text-lg font-medium">Memuat...</p>
          </div>
        </div>
      </section>
    )
  }

  return (
    <section className="relative h-[600px] md:h-[700px] overflow-hidden">
      {slides.map((slide, index) => {
        const imageUrl = getImageUrl(slide.image)
        const isImageLoaded = imagesLoaded[slide.id]
        const isActive = index === currentSlide

        return (
          <div
            key={slide.id}
            className={`absolute inset-0 transition-opacity duration-1000 ${
              isActive ? 'opacity-100' : 'opacity-0'
            }`}
          >
            {/* Background Image with Overlay */}
            {/* Background Image with Overlay */}
            {/* Background Image with Overlay */}
            <div className="absolute inset-0 bg-gradient-to-br from-brand-500/60 via-brand-600/70 to-support-500/60 z-0">
               <Image
                src={imageUrl}
                alt={slide.title}
                className={`object-cover transition-opacity duration-500 ${
                  isImageLoaded ? 'opacity-100' : 'opacity-0'
                }`}
                onLoadingComplete={() => handleImageLoad(slide.id)}
                fill
                priority={index === 0} // Priority for the first slide
                sizes="100vw"
              />
              {!isImageLoaded && (
                <div className="absolute inset-0 flex items-center justify-center z-10">
                  <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                </div>
              )}
              {/* Overlay is separate or part of the gradient div? The user had image INSIDE the gradient div which is weird if gradient is background-image. 
                 But wait, the gradient div has `bg-gradient-to-br...`. Image inside would obscure it unless image has opacity or mix-blend. 
                 Actually, the previous code had `img` inside a div with gradient class. 
                 Most likely the gradient is intended to be *on top* of the image. 
                 But if `img` is normal flow or absolute, it depends.
                 The previous code:
                    <div className="absolute inset-0 bg-gradient-to-br ...">
                      <img ... className="w-full h-full object-cover ..." />
                      ...
                      <div className="absolute inset-0 bg-black/35 z-20" />
                    </div>
                 If the div has a bg-gradient AND contains an img that covers it, the img covers the gradient unless the img is transparent.
                 However, the gradient classes `from-brand-500/60` imply alpha. 
                 If the div has background, the img is ON TOP of the background.
                 So the gradient is HIDDEN by the image.
                 The user probably meant for the gradient to be an overlay.
                 But let's stick to replacing the `img` tag. 
                 I will place Image inside the div.
              */}
              <div className="absolute inset-0 bg-black/35 z-20" />
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
                  className="inline-block bg-accent-500 text-white px-8 py-3 rounded-full font-semibold text-lg hover:bg-accent-600 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                >
                  Donasi Sekarang
                </Link>
              </div>
            </div>
          </div>
        )
      })}

      {/* Navigation Dots */}
      <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrentSlide(index)}
            className={`w-3 h-3 rounded-full transition-all ${
              index === currentSlide ? 'bg-accent-500 w-8' : 'bg-white/60 hover:bg-accent-400'
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
