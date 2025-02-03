import "@/css/app.css"

import { createSSRApp, h, DefineComponent } from "vue"
import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { trail } from "momentum-trail"
import Toast from "vue-toastification"
import { notifications } from "./plugins/notifications"
import routes from "./routes/routes.json"

const appName = "Lost City Markets"

createInertiaApp({
  title: (title: string) => (title ? `${title} — ${appName}` : `${appName}`),
  resolve: (name: string) => {
    return resolvePageComponent(
      `../views/pages/${name}.vue`,
      import.meta.glob<DefineComponent>("../views/pages/**/*.vue"),
    )
  },
  setup({ el, App, props, plugin }) {
    createSSRApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Toast)
      .use(notifications)
      .use(trail, { routes, absolute: false })
      .mount(el)
  },
})
