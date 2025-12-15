/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  output: "standalone",
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
  },
  images: {
    domains: ['localhost', '127.0.0.1', 'images.unsplash.com'],
    remotePatterns: [
      // Lokal API storage (http/https, localhost/127)
      { protocol: 'http', hostname: 'localhost', port: '8000', pathname: '/storage/**' },
      { protocol: 'https', hostname: 'localhost', port: '8000', pathname: '/storage/**' },
      { protocol: 'http', hostname: '127.0.0.1', port: '8000', pathname: '/storage/**' },
      { protocol: 'https', hostname: '127.0.0.1', port: '8000', pathname: '/storage/**' },
      // CDN/Prod wildcard (ALLOW ALL for R2 and others)
      { protocol: 'https', hostname: '**' },
      { protocol: 'http', hostname: '**' },
    ],
  },
}

module.exports = nextConfig

