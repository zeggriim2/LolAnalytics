
import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig(({ command }) => ({
  plugins: [tailwindcss(), vue(), symfonyPlugin()],
  base: command === 'build' ? '/build/' : '/',
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        admin: "./assets/admin/main.ts",
        front: "./assets/front/main.ts",
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
