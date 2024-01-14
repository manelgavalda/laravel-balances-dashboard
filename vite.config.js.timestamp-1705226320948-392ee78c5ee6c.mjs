// vite.config.js
import { defineConfig } from "file:///home/manel/code/laravel-balances-dashboard/node_modules/vite/dist/node/index.js";
import vue from "file:///home/manel/code/laravel-balances-dashboard/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import laravel from "file:///home/manel/code/laravel-balances-dashboard/node_modules/laravel-vite-plugin/dist/index.mjs";
var vite_config_default = defineConfig({
  test: {
    environment: "happy-dom"
  },
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js"
      ],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  build: {
    rollupOptions: {
      output: {
        entryFileNames: `assets/[name].js`,
        chunkFileNames: `assets/[name].js`,
        assetFileNames: `assets/[name].[ext]`
      }
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvaG9tZS9tYW5lbC9jb2RlL2xhcmF2ZWwtYmFsYW5jZXMtZGFzaGJvYXJkXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvaG9tZS9tYW5lbC9jb2RlL2xhcmF2ZWwtYmFsYW5jZXMtZGFzaGJvYXJkL3ZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9ob21lL21hbmVsL2NvZGUvbGFyYXZlbC1iYWxhbmNlcy1kYXNoYm9hcmQvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcbmltcG9ydCB2dWUgZnJvbSBcIkB2aXRlanMvcGx1Z2luLXZ1ZVwiO1xuaW1wb3J0IGxhcmF2ZWwgZnJvbSAnbGFyYXZlbC12aXRlLXBsdWdpbic7XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gICAgdGVzdDoge1xuICAgICAgICBlbnZpcm9ubWVudDogJ2hhcHB5LWRvbSdcbiAgICB9LFxuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgbGFyYXZlbCh7XG4gICAgICAgICAgICBpbnB1dDogW1xuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvY3NzL2FwcC5jc3MnLFxuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvanMvYXBwLmpzJyxcbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICAgICAgdnVlKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiB7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtQXNzZXRVcmxzOiB7XG4gICAgICAgICAgICAgICAgICAgIGJhc2U6IG51bGwsXG4gICAgICAgICAgICAgICAgICAgIGluY2x1ZGVBYnNvbHV0ZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0pXG4gICAgXSxcbiAgICBidWlsZDoge1xuICAgICAgICByb2xsdXBPcHRpb25zOiB7XG4gICAgICAgICAgICBvdXRwdXQ6IHtcbiAgICAgICAgICAgICAgICBlbnRyeUZpbGVOYW1lczogYGFzc2V0cy9bbmFtZV0uanNgLFxuICAgICAgICAgICAgICAgIGNodW5rRmlsZU5hbWVzOiBgYXNzZXRzL1tuYW1lXS5qc2AsXG4gICAgICAgICAgICAgICAgYXNzZXRGaWxlTmFtZXM6IGBhc3NldHMvW25hbWVdLltleHRdYFxuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQW1ULFNBQVMsb0JBQW9CO0FBQ2hWLE9BQU8sU0FBUztBQUNoQixPQUFPLGFBQWE7QUFFcEIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsTUFBTTtBQUFBLElBQ0YsYUFBYTtBQUFBLEVBQ2pCO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsUUFDSDtBQUFBLFFBQ0E7QUFBQSxNQUNKO0FBQUEsTUFDQSxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxPQUFPO0FBQUEsSUFDSCxlQUFlO0FBQUEsTUFDWCxRQUFRO0FBQUEsUUFDSixnQkFBZ0I7QUFBQSxRQUNoQixnQkFBZ0I7QUFBQSxRQUNoQixnQkFBZ0I7QUFBQSxNQUNwQjtBQUFBLElBQ0o7QUFBQSxFQUNKO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
