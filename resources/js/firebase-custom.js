// Your web app's Firebase configuration

// Your web app's Firebase configuration
if('serviceWorker' in navigator) {
    navigator.serviceWorker.register('../firebase-messaging-sw.js')
        .then(function(registration) {
            console.log("Service Worker Registered");
            messaging.useServiceWorker(registration);
        });
}
var firebaseConfig = {
    apiKey: "AIzaSyBAF_AzwIMrsu9vQUJDyKPQXcoEhA3AXM4",
    authDomain: "continual-math-237308.firebaseapp.com",
    databaseURL: "https://continual-math-237308.firebaseio.com",
    projectId: "continual-math-237308",
    storageBucket: "continual-math-237308.appspot.com",
    messagingSenderId: "142675662070",
    appId: "1:142675662070:web:1d5d57e22e6fb4bb8948d7",
    measurementId: "G-QDXE372DS5"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
//firebase.analytics();
const messaging = firebase.messaging();
	messaging
.requestPermission()
.then(function () {
//MsgElem.innerHTML = "Notification permission granted." 
	console.log("Notification permission granted.");

	return messaging.getToken()
})
.then(function(token) {
    window.sendMessage(token);

})
.catch(function (err) {
	console.log("Unable to get permission to notify.", err);
});

messaging.onMessage(function(payload) {
    console.log('PERMISSION', Notification.permission);
    if(Notification.permission == 'granted'){
        var notify;
        notify = new Notification(payload.notification.title,{
            body: payload.notification.body,
            icon: payload.notification.icon,
            tag: "Dummy"
        });
        console.log(payload.notification);
    }
});

    //firebase.initializeApp(config);
var database = firebase.database().ref().child("/users/");

database.on('value', function(snapshot) {
    renderUI(snapshot.val());
});

// On child added to db
database.on('child_added', function(data) {
    console.log('PERMISSION', Notification.permission);
    if(Notification.permission!=='default'){
        var notify;

    }else{
        alert('Please allow the notification first');
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
});

