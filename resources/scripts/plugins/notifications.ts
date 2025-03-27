import { TYPE as NotificationType, useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";

const toast = useToast();

export const notifications = () => {
    router.on("finish", () => {
        const notification = usePage().props.notification;

        if (notification) {
            toast(notification.body, {
                type: notification.type as NotificationType,
            });
        }
    });
};
