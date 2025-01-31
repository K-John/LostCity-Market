import "@/css/app.css"

import { createSSRApp, h, DefineComponent } from "vue"
import { createInertiaApp } from "@inertiajs/vue3"
import createServer from "@inertiajs/vue3/server"
import { renderToString } from "@vue/server-renderer"
import { Page } from "@inertiajs/core"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { trail } from "momentum-trail"
import routes from "./routes/routes.json"

const appName = "Lost City Market"

createServer((page: Page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
      return resolvePageComponent(
        `../views/pages/${name}.vue`,
        import.meta.glob<DefineComponent>("../views/pages/**/*.vue"),
      )
    },
    setup({ App, props, plugin }) {
      return createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(trail, { routes, absolute: false, url: props.initialPage.url })
    },
  }),
)
