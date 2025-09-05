import "@/css/app.css";

import { createSSRApp, h, DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { modal } from "momentum-modal";
import { trail } from "momentum-trail";
import Toast from "vue-toastification";
import { notifications } from "./plugins/notifications";
import routes from "./routes/routes.json";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
    });
}

const appName = "Lost City Markets";

createInertiaApp({
    title: (title: string) => (title ? `${title} — ${appName}` : `${appName}`),
    resolve: (name: string) => {
        return resolvePageComponent(
            `../views/pages/${name}.vue`,
            import.meta.glob<DefineComponent>("../views/pages/**/*.vue"),
        );
    },
    setup({ el, App, props, plugin }) {
        createSSRApp({ render: () => h(App, props) })
            .use(modal, {
                resolve: (name: string) => {
                    return resolvePageComponent(
                        `../views/pages/${name}.vue`,
                        import.meta.glob<DefineComponent>(
                            "../views/pages/**/*.vue",
                        ),
                    );
                },
            })
            .use(plugin)
            .use(Toast)
            .use(notifications)
            .use(trail, { routes, absolute: false })
            .mount(el);
    },
});
