import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico', 'apple-touch-icon.png', 'logo.svg'],
            manifest: {
                name: 'Barayoro',
                short_name: 'Barayoro',
                description: 'Plateforme SaaS de gestion d\'entreprise',
                theme_color: '#3b82f6',
                background_color: '#ffffff',
                display: 'standalone',
                orientation: 'portrait',
                scope: '/',
                start_url: '/',
                icons: [
                    { src: '/icons/icon-72x72.png', sizes: '72x72', type: 'image/png' },
                    { src: '/icons/icon-96x96.png', sizes: '96x96', type: 'image/png' },
                    { src: '/icons/icon-128x128.png', sizes: '128x128', type: 'image/png' },
                    { src: '/icons/icon-144x144.png', sizes: '144x144', type: 'image/png' },
                    { src: '/icons/icon-152x152.png', sizes: '152x152', type: 'image/png' },
                    { src: '/icons/icon-192x192.png', sizes: '192x192', type: 'image/png' },
                    { src: '/icons/icon-384x384.png', sizes: '384x384', type: 'image/png' },
                    { src: '/icons/icon-512x512.png', sizes: '512x512', type: 'image/png', purpose: 'any maskable' }
                ]
            },
            workbox: {
                globPatterns: ['**/*.{css,js,html,json,svg,png,jpg,ico}'],
                runtimeCaching: [
                    {
                        urlPattern: /^\/api\/.*/,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-cache',
                            networkTimeoutSeconds: 3,
                            expiration: {
                                maxEntries: 100,
                                maxAgeSeconds: 24 * 60 * 60
                            }
                        }
                    },
                    {
                        urlPattern: /\.(jpg|jpeg|png|gif|svg)$/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'images-cache',
                            expiration: {
                                maxEntries: 50,
                                maxAgeSeconds: 7 * 24 * 60 * 60
                            }
                        }
                    }
                ]
            }
        })
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
    },
});