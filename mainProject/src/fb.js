// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-analytics.js";
import  firebase  from "firebase/app";
import "firebase/firestore"
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAAptFcNAyrP8NKJnYJAGo1ygYLrRXpyBY",
  authDomain: "testproject-77b28.firebaseapp.com",
  projectId: "testproject-77b28",
  storageBucket: "testproject-77b28.appspot.com",
  messagingSenderId: "98684876080",
  appId: "1:98684876080:web:3f47d14692a5ac3229c4f5",
  measurementId: "G-PZT3FM109Y"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const db = firebase.firestore();
db.settings({timestampsInSnapshots:true})
console.log(analytics);

export default db;