console.log('in the chat');


import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const messageElement = document.getElementById('message');
const userMessageInput = document.getElementById('messageInput');
const sendMessageForm = document.getElementById('chatForm');


sendMessageForm.addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent the default form submission
    
    
     echo.channel('laravelChat')
 .listen('.chatting', (e) => {
 console.log(e.message);
 });

    if (userMessageInput.value.trim() != '') {
        try {
            const response = await axios.post('/sendMessage', {
                username: 'taimoor',
                message: userMessageInput.value
            });
            console.log('Response:', response);
            alert('Message sent successfully!');
            userMessageInput.value = ''; // Clear the input field
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        }
    } else {
        alert('Please enter a message before submitting.');
    }


});

