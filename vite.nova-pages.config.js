import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

/**
 * Build Nova custom Inertia pages as IIFE using Nova's global Vue.
 * Bundling a second Vue copy breaks scoped styles / page mounting.
 */
export default defineConfig({
  plugins: [
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  define: {
    'process.env.NODE_ENV': JSON.stringify('production'),
  },
  build: {
    outDir: 'public/js',
    emptyOutDir: false,
    copyPublicDir: false,
    cssCodeSplit: false,
    lib: {
      entry: path.resolve(__dirname, 'resources/js/nova-pages.js'),
      name: 'NovaPages',
      formats: ['iife'],
      fileName: () => 'nova-pages.js',
      cssFileName: 'nova-pages',
    },
    rollupOptions: {
      external: ['vue'],
      output: {
        globals: {
          vue: 'Vue',
        },
        assetFileNames: 'nova-pages[extname]',
      },
    },
  },
})
