declare global {
    interface Window {
      Echo: import("laravel-echo").default | any;
      Pusher: import("pusher-js").default | any;
    }
  }
  
  export {};
  