
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig(({ command}) => ({
  plugins: [vue()],
  root: '.',
  base: command === 'build' ? '/build/' : '/',
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        admin: resolve(__dirname, 'assets/admin/main.ts'),
        front: resolve(__dirname, 'assets/front/main.ts'),
      },
    },
  },
  server: {
    origin: 'http://localhost:5173',
    cors: true,
  },
  resolve: {
    alias: {
      '@admin': resolve(__dirname, 'assets/admin'),
      '@front': resolve(__dirname, 'assets/front'),
      '@shared': resolve(__dirname, 'assets/shared'),
    },
  },
}))
