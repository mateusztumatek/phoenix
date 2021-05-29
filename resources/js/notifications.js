window.sendMessage = (token) => {
    axios.post('/test-notification', {device_id: token}).then(res => {

    })
}
require('./firebase-custom');

