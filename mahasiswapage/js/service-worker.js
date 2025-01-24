// service-worker.js

self.addEventListener("push", function (event) {
  const data = event.data.json(); // Mendapatkan data dari push message

  const options = {
    body: data.body,
    icon: "icon.png", // Ganti dengan ikon notifikasi Anda
    badge: "badge.png", // Ganti dengan badge notifikasi Anda
    data: {
      url: data.url, // URL tujuan ketika notifikasi diklik
    },
  };

  event.waitUntil(self.registration.showNotification(data.title, options));
});

// Menangani klik pada notifikasi
self.addEventListener("notificationclick", function (event) {
  event.notification.close(); // Menutup notifikasi
  event.waitUntil(
    clients.openWindow(event.notification.data.url) // Arahkan pengguna ke halaman URL yang diinginkan
  );
});
