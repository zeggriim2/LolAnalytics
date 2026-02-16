import js from '@eslint/js'
import tseslint from 'typescript-eslint'
import pluginVue from 'eslint-plugin-vue'
import eslintConfigPrettier from 'eslint-config-prettier'
import globals from 'globals'

export default tseslint.config(
  {
    ignores: ['node_modules/', 'public/', 'vendor/', 'var/', 'migrations/'],
  },

  // Base JS rules
  js.configs.recommended,

  // TypeScript rules
  ...tseslint.configs.recommended,

  // Vue 3 rules (essential + strongly recommended + recommended)
  ...pluginVue.configs['flat/recommended'],

  // Vue files: use typescript-eslint parser inside <script>
  {
    files: ['assets/**/*.vue'],
    languageOptions: {
      globals: {
        ...globals.browser,
      },
      parserOptions: {
        parser: tseslint.parser,
      },
    },
    rules: {
      // False positive with Vue ref() assignments
      'no-useless-assignment': 'off',
    },
  },

  // Disable formatting rules that conflict with Prettier
  eslintConfigPrettier,

  // Project-specific rules
  {
    files: ['assets/**/*.{ts,vue}'],
    languageOptions: {
      globals: {
        console: 'readonly',
        fetch: 'readonly',
        window: 'readonly',
        document: 'readonly',
        RequestInit: 'readonly',
      },
    },
    rules: {
      'no-console': ['warn', { allow: ['warn', 'error'] }],
      '@typescript-eslint/no-unused-vars': [
        'error',
        { argsIgnorePattern: '^_' },
      ],
      'vue/multi-word-component-names': 'off',
    },
  },
)
