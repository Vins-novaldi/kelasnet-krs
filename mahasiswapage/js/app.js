// app.js

if ("serviceWorker" in navigator && "PushManager" in window) {
  navigator.serviceWorker
    .register("service-worker.js")
    .then(function (registration) {
      console.log("Service Worker terdaftar dengan sukses", registration);

      // Meminta izin notifikasi dari pengguna
      Notification.requestPermission().then(function (permission) {
        if (permission === "granted") {
          console.log("Izin notifikasi diberikan");
          // Anda dapat mengirimkan pemberitahuan di sini, misalnya melalui API
          sendPushNotification();
        }
      });
    })
    .catch(function (error) {
      console.log("Pendaftaran Service Worker gagal:", error);
    });
}

function sendPushNotification() {
  // Token push pengguna biasanya diperoleh melalui server
  const pushToken = "USER_PUSH_TOKEN"; // Ganti dengan token pengguna yang sudah terdaftar

  // Data pemberitahuan yang akan dikirimkan
  const data = {
    title: "Pemindahan Ruangan Terbaru",
    body: "Ada perubahan jadwal ruangan Anda, klik untuk melihat detail.",
    url: "https://www.example.com/ruangan", // URL halaman detail
  };

  // Kirim data pemberitahuan ke server atau API
  fetch("https://yourserver.com/sendPush", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      token: pushToken,
      notification: data,
    }),
  });
}
