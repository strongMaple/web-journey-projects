// orders-sw.js

self.addEventListener("install", (event) => {
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  event.waitUntil(self.clients.claim());
});

// LISTEN FOR NEW ORDER MESSAGES
self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "NEW_ORDER") {
    const title = "New Order Received";
    const options = {
      body: event.data.body || "A customer placed a new order.",
      icon: "https://strongMaple.com/og-image.png",
      badge: "https://strongMaple.com/og-image.png",
      vibrate: [80, 40, 120],
      data: {
        url: "/admin/html/orders.html", // fixed URL
      },
    };

    self.registration.showNotification(title, options);
  }
});

// WHEN USER CLICKS NOTIFICATION
self.addEventListener("notificationclick", (event) => {
  event.notification.close();

  event.waitUntil(
    clients
      .matchAll({ type: "window", includeTabs: true })
      .then((clientList) => {
        for (const client of clientList) {
          if (client.url.includes("/admin/html/orders.html")) {
            return client.focus();
          }
        }

        return clients.openWindow("/admin/html/orders.html");
      })
  );
});
