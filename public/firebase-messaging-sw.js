// Import the Firebase scripts
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js');

// Firebase configuration
var firebaseConfig = {
  apiKey: "dfc7345af3e6a211e29be063d5efca414bc35143",
  authDomain: "mednero.firebaseapp.com",
  databaseURL: "https://mednero-default-rtdb.firebaseio.com",
  projectId: "mednero",
  storageBucket: "mednero.firebasestorage.app",
  messagingSenderId: "690636094583",
  appId: "1:690636094583:web:2d6d47f409aa90bad01a72"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  console.log('Received background message ', payload);

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
