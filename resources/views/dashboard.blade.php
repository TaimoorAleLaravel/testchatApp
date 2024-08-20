<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <style>
                        /* Reset and basic styling */
                        .chat-container {
                            border: 1px solid #ccc;
                            border-radius: 8px;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                            overflow: hidden;
                        }
                    
                        .chat {
                            display: flex;
                            flex-direction: column;
                        }
                    
                        /* Chat area styles */
                        .chat-area {
                            width: 100%;
                            display: flex;
                            flex-direction: column;
                            height: 60vh;
                            min-height: 350px;
                        }
                    
                        .chat-messages {
                            flex-grow: 1;
                            padding: 1rem;
                            overflow-y: auto;
                            background-color: #f9f9f9;
                            display: flex;
                            flex-direction: column;
                        }
                    
                        .section {
                            margin-bottom: 1rem;
                            position: relative;
                        }
                    
                        .date {
                            text-align: center;
                            font-size: 13px;
                            margin-top: 1rem;
                            margin-bottom: 0.5rem;
                            position: sticky;
                            top: 0;
                            background-color: transparent;
                            z-index: 100;
                            padding: 0.5rem 0;
                        }
                    
                        .date .date_text {
                            padding: 5px 14px;
                            background: #e3e3e3;
                            border-radius: 6px;
                        }
                    
                        .message {
                            display: flex;
                            flex-direction: column;
                            margin-bottom: 1rem;
                            max-width: 487px;
                            position: relative;
                        }
                    
                        .message p {
                            margin: 0;
                            padding: 0.75rem 1rem;
                            border-radius: 12px;
                            background-color: #e4e6eb;
                            line-height: 1.5;
                        }
                    
                        .message.sent p {
                            background-color: #007bff;
                            color: #fff;
                            align-self: flex-end;
                        }
                    
                        .message.received {
                            align-self: start;
                        }
                    
                        .message.sent {
                            align-self: end;
                        }
                    
                        .message.received p {
                            background-color: #f0f0f0;
                        }
                    
                        .timestamp {
                            font-size: 0.75rem;
                            color: #888;
                            margin-top: 0.25rem;
                            align-self: flex-end;
                            margin-right: 10px;
                        }
                    
                        /* Chat input styles */
                        .chat-input {
                            display: flex;
                            padding: 1rem;
                            border-top: 1px solid #ddd;
                            background-color: #ffffff;
                        }
                    
                        .chat-input input {
                            flex-grow: 1;
                            padding: 0.75rem;
                            border: 1px solid #ddd;
                            border-radius: 20px;
                            outline: none;
                            margin-right: 0.5rem;
                        }
                    
                        .chat-input button {
                            padding: 0.75rem 1rem;
                            background-color: #007bff;
                            color: #fff;
                            border: none;
                            border-radius: 20px;
                            cursor: pointer;
                            transition: background-color 0.2s;
                        }
                    
                        .chat-input button:hover {
                            background-color: #0056b3;
                        }
                    
                        .chat-input button:disabled {
                            background: #757575;
                            cursor: not-allowed;
                        }
                    
                        .chat-messages::-webkit-scrollbar {
                            width: 8px;
                        }
                    
                        .chat-messages::-webkit-scrollbar-track {
                            background: #f1f1f1;
                            border-radius: 4px;
                        }
                    
                        .chat-messages::-webkit-scrollbar-thumb {
                            background: #b0b3b8;
                            border-radius: 4px;
                        }
                    
                        .chat-messages::-webkit-scrollbar-thumb:hover {
                            background: #888;
                        }
                    
                        .scroll-button {
                            display: none;
                        }
                    </style>
                    
                    {{-- {{ !is_null(session()->get('client_id')) ? 'cilent' . session()->get('client_id') : '' }}
                    {{ !is_null(session()->get('counselor_id')) ? 'Counselor: ' . session()->get('counselor_id') : '' }}
                    Sender {{ $sender }}
                    <br>
                    Receiver Id {{ $receiver }} --}}
                    
                    <div class="chat-container mt-2 p-0">
                        <div class="chat-area" style="position: relative">
                            <button onclick="scrollToBottom()" id="scrollBtn" class="btn position-absolute"
                                style="background-color:#f0f0f0; bottom: 10px; right: 20px; z-index: 10; padding: 10px; line-height: 0; border-radius: 6px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708" />
                                </svg>
                            </button>
                            <div class="chat-messages" id="chat-messages">
                                @if (!is_null($messages))
                                    @php $lastDate = null; @endphp
                    
                                    @foreach ($messages as $message)
                                        @if ($lastDate !== $message['formatted_date'])
                                            @if (!is_null($lastDate))
                                                </div> <!-- Close the previous date section -->
                                            @endif
                                            <div class="section chat chat-section" id="chat-section">
                                                <div class="date sticky">
                                                    <span class="date_text">{{ $message['formatted_date'] }}</span>
                                                </div>
                                        @endif
                    
                                        <div class="message
                                            @php
                                                    if ($sender == $message['sender_id']) {
                                                        echo 'sent';
                                                    } else {
                                                        echo 'received';
                                                    }
                                                
                                            @endphp
                                        ">
                                            <p>{{ $message['message'] }}</p>
                                            <span class="timestamp">{{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}</span>
                                        </div>
                    
                                        @php $lastDate = $message['formatted_date']; @endphp
                                    @endforeach
                    
                                    @if (!is_null($lastDate))
                                        </div> <!-- Close the last date section -->
                                    @endif
                    
                                @endif
                            </div>
                        </div>
                        <div class="chat-input">
                            <form id="chatForm">
                                <input type="hidden" name="receiver_id" value="{{$receiver}}">
                                <input type="text" id="messageInput" name="message" placeholder="Type a message...">
                                <button type="submit">Send</button>
                            </form>
                           
                        </div>
                    </div>
                    <script src="./js/app.js"></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    {{-- <script>
                        let senderRole = @json($role);
                        let userId = @json($sender);
                        let receiverRole = @json($receiverRole);
                    
                        console.log('senderRole: ' + senderRole);
                        let chatMessages = document.getElementById('chat-messages');
                        let chatArea = document.querySelector('.chat-messages');
                        let inputMessage = document.getElementById('message');
                        let sendMessageButton = document.querySelector('#send-messageBtn');
                        let scrollBtn = document.getElementById('scrollBtn');
                        let protocol = "ws"
                        let domain = document.domain;
                        let port = 8000;
                        let socket = new WebSocket(protocol + "://" + domain + ":" + 8080);
                        // let socket = new WebSocket('ws://localhost:8080');
                    
                        inputMessage.addEventListener('keydown', (event) => {
                            if (event.key === 'Enter') {
                                if (inputMessage.value.length > 0) {
                                    sendMessage();
                                    inputMessage.value = '';
                                    sendMessageButton.disabled = true;
                                }
                            }
                        });
                    
                        function checkScroll() {
                            const scrollTop = chatMessages.scrollTop;
                            const scrollHeight = chatMessages.scrollHeight;
                            const clientHeight = chatMessages.clientHeight;
                    
                            if (scrollHeight > clientHeight) {
                                if (scrollTop + clientHeight >= scrollHeight - 10) {
                                    scrollBtn.style.display = 'none';
                                } else {
                                    scrollBtn.style.display = 'block';
                                }
                            } else {
                                scrollBtn.style.display = 'none';
                            }
                        }
                    
                        chatMessages.addEventListener('scroll', checkScroll);
                        checkScroll();
                    
                        function scrollToBottom() {
                            chatArea.scrollTop = chatArea.scrollHeight;
                        }
                        scrollToBottom();
                    
                        if (senderRole === 'counsler') {
                            window.onload = () => {
                                $.ajax({
                                    url: "{{ route('unreadMsgHandler') }}",
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        client_id: "{{ $receiver }}",
                                        counselor_id: sender
                                    },
                                    success: (response) => console.log('Success:', response),
                                    error: (xhr, status, error) => console.log('Error:', error)
                                });
                            };
                        }
                    
                        socket.onopen = () => console.log("WebSocket connected");
                        socket.onclose = () => console.log("WebSocket closed");
                        socket.onerror = (e) => console.log("WebSocket error: ", e);
                    
                        inputMessage.addEventListener('keyup', (e) => {
                            if (inputMessage.value.length > 0) {
                                sendMessageButton.disabled = false;
                            }
                        });
                    
                        socket.onmessage = (e) => {
                            let data = JSON.parse(e.data);
                            console.log(data);
                    
                            if (data.type === "resource_id") {
                                $.ajax({
                                    url: "{{ route('resource_id') }}",
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        resource_id: data.resource_id,
                                        conn: "initial",
                                        sender_role: senderRole
                                    },
                                    success: (response) => console.log('Success:', response),
                                    error: (xhr, status, error) => console.log('Error:', error)
                                });
                            } else {
                                if (data.status === 200) {
                                    handleChatMessage(data);
                                }
                            }
                        };
                    
                        function handleChatMessage(data) {
                            let messageBy;
                            if (senderRole == 'client') {
                                if (userId == data.sender_id && data.sender_role == 'client') {
                                    messageBy = 'sent';
                                } else {
                                    messageBy = 'received';
                                }
                            } else {
                                console.log("" + data.senderRole);
                                if (data.sender_role == 'client') {
                                    messageBy = 'received';
                                } else {
                                    messageBy = 'sent';
                                }
                            }
                    
                            let sections = document.querySelectorAll('.chat-section');
                            let lastSection = sections.length > 0 ? sections[sections.length - 1] : null;
                            let lastDate = lastSection ? lastSection.querySelector('.date.sticky .date_text').innerText : null;
                            console.log(lastDate);
                    
                            let messageElement = `
                                <div class="message ${messageBy}">
                                    <p>${data.message}</p>
                                    <span class="timestamp">${data.formatted_time}</span>
                                </div>`;
                            console.log(data.formatted_date);
                            console.log(lastDate === data.formatted_date);
                            if (lastDate === data.formatted_date) {
                                lastSection.insertAdjacentHTML('beforeend', messageElement);
                            } else {
                                console.log('call');
                                let newSection = `
                                    <div class="section chat chat-section">
                                        <div class="date sticky">
                                            <span class="date_text">${data.formatted_date}</span>
                                        </div>
                                        ${messageElement}
                                    </div>`;
                                chatMessages.insertAdjacentHTML('beforeend', newSection);
                            }
                    
                            scrollToBottom();
                        }
                    
                        function sendMessage() {
                            if (inputMessage.value.length === 0) return;
                    
                            let message = {
                                sender: userId,
                                sender_role: senderRole,
                                receiver_role: receiverRole,
                                receiver: "{{ $receiver }}",
                                message: inputMessage.value
                            };
                    
                            console.log(message);
                            socket.send(JSON.stringify(message));
                            inputMessage.value = '';
                            sendMessageButton.disabled = true;
                        }
                    
                        sendMessageButton.addEventListener('click', sendMessage);
                    </script> --}}
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
