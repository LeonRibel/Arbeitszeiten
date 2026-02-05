import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import { reactRouter } from '@react-router/dev/vite'
import path from 'path'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    reactRouter(),
    tailwindcss()
  ],
  resolve: {
    alias: {
      '@catalyst': path.resolve(__dirname, './catalyst')
    }
  }
})
