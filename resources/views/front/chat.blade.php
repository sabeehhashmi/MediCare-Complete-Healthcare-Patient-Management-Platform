{{-- resources/views/front/chat.blade.php --}}
@extends('front.template.layout')

@section('title', 'Messages - MedNero')

@section('styles')
<style>
    .chat-container {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        height: calc(100vh - 250px);
        min-height: 550px;
        display: flex;
    }
    
    .chat-sidebar {
        width: 320px;
        background: #fff;
        border-right: 1px solid #eef2f6;
        display: flex;
        flex-direction: column;
    }
    
    .chat-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #eef2f6;
    }
    
    .chat-sidebar-header h5 {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .chat-search {
        padding: 15px;
        border-bottom: 1px solid #eef2f6;
    }
    
    .chat-search input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 30px;
        font-size: 14px;
        background: #f8fafc;
        outline: none;
    }
    
    .conversation-list {
        flex: 1;
        overflow-y: auto;
    }
    
    .conversation-item {
        display: flex;
        align-items: center;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f0f2f5;
    }
    
    .conversation-item:hover {
        background: #f8fafc;
    }
    
    .conversation-item.active {
        background: #e8f4fe;
        border-left: 3px solid #1baeff;
    }
    
    .conversation-avatar {
        position: relative;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    .conversation-avatar img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #fff;
    }
    
    .conversation-info {
        flex: 1;
        min-width: 0;
    }
    
    .conversation-name {
        font-weight: 600;
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .conversation-name span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }
    
    .conversation-specialty {
        font-size: 11px;
        color: #94a3b8;
    }
    
    .conversation-last-msg {
        font-size: 11px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .unread-badge {
        background: #1baeff;
        color: white;
        border-radius: 20px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 600;
        min-width: 18px;
        text-align: center;
    }
    
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
    }
    
    .chat-header {
        padding: 15px 20px;
        background: #fff;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .chat-header-info h6 {
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }
    
    .chat-header-info p {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .message-row {
        display: flex;
        width: 100%;
    }
    
    .message-row.sent {
        justify-content: flex-end;
    }
    
    .message-row.received {
        justify-content: flex-start;
    }
    
    .message-bubble {
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 18px;
        word-wrap: break-word;
    }
    
    .message-bubble.sent {
        background: #1baeff;
        color: white;
        border-bottom-right-radius: 4px;
    }
    
    .message-bubble.received {
        background: white;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .message-time {
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.7;
        text-align: right;
    }
    
    .date-divider {
        text-align: center;
        margin: 15px 0;
    }
    
    .date-divider span {
        background: #e2e8f0;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        color: #64748b;
    }
    
    .attachment-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 12px;
        cursor: pointer;
    }
    
    .attachment-audio {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 200px;
    }
    
    .attachment-audio audio {
        height: 36px;
    }
    
    .attachment-file {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        background: rgba(0,0,0,0.05);
        border-radius: 12px;
        text-decoration: none;
        color: #1e293b;
    }
    
    .message-bubble.sent .attachment-file {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 14px;
        background: white;
        border-radius: 18px;
    }
    
    .typing-dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typingBounce 1.4s infinite;
    }
    
    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
        30% { transform: translateY(-4px); opacity: 1; }
    }
    
    .chat-input-area {
        position: relative;
        padding: 15px 20px;
        background: #fff;
        border-top: 1px solid #eef2f6;
    }
    
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border-radius: 30px;
        padding: 5px 15px;
        border: 1px solid #e2e8f0;
    }
    
    .input-wrapper input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px 0;
        font-size: 14px;
        outline: none;
    }
    
    .attach-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #64748b;
        padding: 5px;
    }
    
    .mic-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #64748b;
        padding: 5px;
        transition: all 0.2s;
    }
    
    .mic-btn:hover {
        color: #1baeff;
    }
    
    .mic-btn.recording {
        color: #ef4444 !important;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    .send-btn {
        background: #1baeff;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
    }
    
    .attach-menu {
        position: absolute;
        bottom: 70px;
        left: 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        padding: 8px 0;
        min-width: 200px;
        z-index: 1000;
        display: none;
    }
    
    .attach-menu.show {
        display: block;
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 16px;
        cursor: pointer;
    }
    
    .menu-item:hover {
        background: #f1f5f9;
    }
    
    .recording-timer {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #ef4444;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        z-index: 1000;
        display: none;
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        text-align: center;
        padding: 40px;
    }
    
    .empty-state i {
        font-size: 60px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }
    
    .image-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.95);
        z-index: 11000;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .image-modal img {
        max-width: 90%;
        max-height: 90%;
    }
</style>
@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            <div class="col-lg-3">
                @include('front.layouts.user-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="chat-container">
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-header">
                            <h5><i class="bx bx-chat"></i> Messages</h5>
                        </div>
                        <div class="chat-search">
                            <input type="text" id="searchInput" placeholder="Search conversations..." autocomplete="off">
                        </div>
                        <div class="conversation-list" id="conversationsList">
                            <div class="empty-state">
                                <i class="bx bx-loader-alt bx-spin"></i>
                                <p>Loading conversations...</p>
                            </div>
                        </div>
                    </div>

                    <div class="chat-main">
                        <div id="activeChat" style="display: none; height: 100%; flex-direction: column;">
                            <div class="chat-header">
                                <img id="chatAvatar" class="chat-header-avatar" src="">
                                <div class="chat-header-info">
                                    <h6 id="chatName">Select a conversation</h6>
                                    <p id="chatStatus">Click to start chatting</p>
                                </div>
                            </div>
                            <div class="chat-messages" id="messagesArea">
                                <div class="empty-state">
                                    <i class="bx bx-message-dots"></i>
                                    <p>No conversation selected</p>
                                </div>
                            </div>
                            <div class="chat-input-area" id="inputArea" style="display: none;">
                                <div class="input-wrapper">
                                    <button class="attach-btn" id="attachBtn"><i class="bx bx-paperclip"></i></button>
                                    <input type="text" id="messageInput" placeholder="Type a message..." autocomplete="off">
                                    <button class="mic-btn" id="micBtn"><i class="bx bx-microphone"></i></button>
                                    <button class="send-btn" id="sendBtn"><i class="bx bx-send"></i></button>
                                </div>
                                <div class="attach-menu" id="attachMenu">
                                    <div class="menu-item" data-type="gallery"><i class="bx bx-images"></i><span>Gallery</span></div>
                                    <div class="menu-item" data-type="audio"><i class="bx bx-microphone"></i><span>Voice Note</span></div>
                                    <div class="menu-item" data-type="document"><i class="bx bx-file"></i><span>Document</span></div>
                                </div>
                            </div>
                        </div>
                        <div id="emptyChat" class="empty-state" style="display: flex; height: 100%;">
                            <i class="bx bx-message-dots"></i>
                            <h5>Welcome to Messages</h5>
                            <p>Select a conversation to start chatting</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="recordingTimer" class="recording-timer">Recording... 0s</div>

<input type="file" id="galleryInput" accept="image/*" style="display: none;">
<input type="file" id="documentInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif" style="display: none;">

<audio id="notifySound" preload="auto" style="display: none;">
    <source src="https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3" type="audio/mpeg">
</audio>
@endsection

@section('scripts')
<script src="https://unpkg.com/@cometchat/chat-sdk-javascript@4.0.5/CometChat.js"></script>
<script>
$(document).ready(function() {
    let cometChat = null;
    let currentReceiver = null;
    let isReady = false;
    let unreadCounts = {};
    let conversations = [];
    let allUsers = [];
    let currentUser = null;
    let messageListenerId = null;
    let typingTimeout = null;
    
    // Audio recording variables
    let mediaRecorder = null;
    let audioChunks = [];
    let isRecording = false;
    let recordingStartTime = null;
    let recordingInterval = null;

    function showToast(msg, type = 'info') {
        let bg = type === 'error' ? '#ef4444' : '#25D366';
        let toast = $(`<div style="position:fixed;bottom:20px;right:20px;background:${bg};color:white;padding:12px 20px;border-radius:8px;z-index:10000;font-size:14px;">${msg}<span style="margin-left:10px;cursor:pointer;" onclick="$(this).parent().remove()">×</span></div>`);
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, () => toast.remove()), 3000);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function formatTime(timestamp) {
        let d = new Date(timestamp);
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function formatDate(timestamp) {
        let d = new Date(timestamp);
        let today = new Date();
        let yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        if (d.toDateString() === today.toDateString()) return 'Today';
        if (d.toDateString() === yesterday.toDateString()) return 'Yesterday';
        return d.toLocaleDateString('en-US', { day: 'numeric', month: 'short' });
    }

    function scrollToBottom() {
        let container = document.getElementById('messagesArea');
        if (container) container.scrollTop = container.scrollHeight;
    }
    
    // Audio Recording Functions
    async function initRecorder() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            
            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) audioChunks.push(event.data);
            };
            
            mediaRecorder.onstop = () => {
                if (audioChunks.length > 0) {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/mp4' });
                    const audioFile = new File([audioBlob], `voice_${Date.now()}.mp4`, { type: 'audio/mp4' });
                    sendAudioFile(audioFile);
                    audioChunks = [];
                }
                stream.getTracks().forEach(track => track.stop());
            };
            return true;
        } catch (error) {
            showToast('Microphone access denied', 'error');
            return false;
        }
    }
    
    function startRecording() {
        if (!mediaRecorder) {
            initRecorder().then(success => { if (success) startRecording(); });
            return;
        }
        
        if (mediaRecorder.state === 'inactive') {
            audioChunks = [];
            mediaRecorder.start(100);
            isRecording = true;
            recordingStartTime = Date.now();
            
            $('#micBtn').html('<i class="bx bx-stop-circle"></i>').addClass('recording');
            $('#recordingTimer').show();
            
            recordingInterval = setInterval(() => {
                let elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
                $('#recordingTimer').text(`Recording... ${elapsed}s`);
                if (elapsed >= 60) stopRecording();
            }, 1000);
            showToast('Recording... Click stop when done', 'info');
        }
    }
    
    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            isRecording = false;
            if (recordingInterval) clearInterval(recordingInterval);
            $('#micBtn').html('<i class="bx bx-microphone"></i>').removeClass('recording');
            $('#recordingTimer').hide();
        }
    }
    
    async function sendAudioFile(file) {
        if (!currentReceiver || !cometChat) {
            showToast('Chat not ready', 'error');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            showToast('Audio too large. Max 10MB', 'error');
            return;
        }
        
        try {
            showToast('Uploading voice note...', 'info');
            
            let mediaMessage = new cometChat.MediaMessage(
                currentReceiver,
                file,
                cometChat.MESSAGE_TYPE.AUDIO,
                cometChat.RECEIVER_TYPE.USER
            );
            
            let sentMessage = await cometChat.sendMediaMessage(mediaMessage);
            showToast('Voice note sent!', 'success');
            loadMessages(currentReceiver);
            
        } catch (error) {
            showToast('Failed to send voice note: ' + error.message, 'error');
        }
    }

    async function init() {
        try {
            let res = await fetch('{{ route("front.chat.init") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            });
            let result = await res.json();
            if (result.status !== '1') throw new Error(result.message);
            
            let { app_id, region, auth_token } = result.data;
            let settings = new CometChat.AppSettingsBuilder().setRegion(region).autoEstablishSocketConnection(true).build();
            await CometChat.init(app_id, settings);
            currentUser = await CometChat.login(auth_token);
            cometChat = CometChat;
            isReady = true;
            
            setupMessageListener();
            await loadUsers();
            
            initRecorder();
            showToast('Chat ready', 'success');
            
        } catch(e) {
            console.error(e);
            showToast('Chat init failed: ' + e.message, 'error');
        }
    }

    function setupMessageListener() {
        if (messageListenerId) cometChat.removeMessageListener(messageListenerId);
        
        messageListenerId = "patient_chat_" + Date.now();
        
        cometChat.addMessageListener(messageListenerId, new cometChat.MessageListener({
            onTextMessageReceived: message => handleNewMessage(message),
            onMediaMessageReceived: message => handleNewMessage(message),
            onTypingStarted: indicator => {
                if (indicator.sender.uid === currentReceiver) {
                    $('#chatStatus').html('<span class="typing-indicator"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></span> typing...');
                }
            },
            onTypingEnded: indicator => {
                if (indicator.sender.uid === currentReceiver) $('#chatStatus').text('Online');
            }
        }));
    }

    function handleNewMessage(message) {
        let audio = document.getElementById('notifySound');
        if (audio) audio.play().catch(e => console.log);
        
        if (currentReceiver === message.sender.uid) {
            loadMessages(currentReceiver);
            cometChat.markAsRead(message.sender.uid, cometChat.RECEIVER_TYPE.USER);
            updateConversationLastMessage(currentReceiver, message);
        } else {
            unreadCounts[message.sender.uid] = (unreadCounts[message.sender.uid] || 0) + 1;
            showToast(`New message from ${message.sender.name}`);
            updateConversationLastMessage(message.sender.uid, message);
        }
    }

    function updateConversationLastMessage(uid, message) {
        let convIndex = conversations.findIndex(c => c.receiver_id === uid);
        if (convIndex !== -1) {
            let messageText = '';
            if (message.type === 'text') {
                messageText = message.text;
            } else if (message.type === 'image') {
                messageText = '📷 Image';
            } else if (message.type === 'audio') {
                messageText = '🎵 Voice note';
            } else {
                messageText = '📎 File';
            }
            
            conversations[convIndex].last_message = messageText;
            conversations[convIndex].last_message_time = message.sentAt;
            
            // Re-sort to put recent chat on top
            conversations.sort((a, b) => b.last_message_time - a.last_message_time);
            renderConversations(conversations);
        }
    }

    async function loadUsers() {
        try {
            let res = await fetch('{{ route("front.chat.conversations") }}', {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            let data = await res.json();
            if (data.status === '1') {
                allUsers = data.data;
                await loadConversationsFromSDK();
            }
        } catch(e) {
            console.error('Load users error:', e);
        }
    }

    async function loadConversationsFromSDK() {
        if (!cometChat || !isReady) return;
        
        try {
            let conversationsRequest = new cometChat.ConversationsRequestBuilder()
                .setLimit(50)
                .build();
            
            let conversationsList = await conversationsRequest.fetchNext();
            let convos = [];
            
            for (let conv of conversationsList) {
                let lastMessage = conv.getLastMessage();
                let conversationWith = conv.getConversationWith();
                let user = allUsers.find(u => u.uid === conversationWith.getUid());
                
                if (user) {
                    let lastMessageText = 'No messages yet';
                    let lastMessageTime = 0;
                    
                    if (lastMessage) {
                        lastMessageTime = lastMessage.getSentAt();
                        let msgType = lastMessage.getType();
                        if (msgType === 'text') {
                            lastMessageText = lastMessage.getText();
                        } else if (msgType === 'image') {
                            lastMessageText = '📷 Image';
                        } else if (msgType === 'audio') {
                            lastMessageText = '🎵 Voice note';
                        } else {
                            lastMessageText = '📎 File';
                        }
                        
                        if (lastMessageText.length > 35) {
                            lastMessageText = lastMessageText.substring(0, 32) + '...';
                        }
                    }
                    
                    convos.push({
                        id: user.id,
                        receiver_id: user.uid,
                        name: user.name,
                        avatar: user.avatar,
                        specialty: user.specialty,
                        last_message: lastMessageText,
                        last_message_time: lastMessageTime,
                        unread_count: conv.getUnreadMessageCount() || 0
                    });
                }
            }
            
            // Also add users with no conversations yet
            for (let user of allUsers) {
                if (!convos.find(c => c.receiver_id === user.uid)) {
                    convos.push({
                        id: user.id,
                        receiver_id: user.uid,
                        name: user.name,
                        avatar: user.avatar,
                        specialty: user.specialty,
                        last_message: 'No messages yet',
                        last_message_time: 0,
                        unread_count: 0
                    });
                }
            }
            
            // Sort by most recent message time (newest first)
            convos.sort((a, b) => b.last_message_time - a.last_message_time);
            conversations = convos;
            renderConversations(conversations);
            
        } catch (err) {
            console.error('SDK conversations error:', err);
            // Fallback: show all users without last message
            let convos = [];
            for (let user of allUsers) {
                convos.push({
                    id: user.id,
                    receiver_id: user.uid,
                    name: user.name,
                    avatar: user.avatar,
                    specialty: user.specialty,
                    last_message: 'No messages yet',
                    last_message_time: 0,
                    unread_count: 0
                });
            }
            conversations = convos;
            renderConversations(conversations);
        }
    }

    function renderConversations(chats) {
        let container = $('#conversationsList');
        if (!chats?.length) {
            container.html('<div class="empty-state"><i class="bx bx-message-dots"></i><p>No conversations yet</p></div>');
            return;
        }
        
        let html = '';
        for (let chat of chats) {
            let active = currentReceiver === chat.receiver_id ? 'active' : '';
            let unread = unreadCounts[chat.receiver_id] || chat.unread_count || 0;
            let badge = unread > 0 ? `<span class="unread-badge">${unread > 9 ? '9+' : unread}</span>` : '';
            let lastMsg = chat.last_message || 'No messages yet';
            if (lastMsg.length > 40) lastMsg = lastMsg.substring(0, 37) + '...';
            
            html += `
                <div class="conversation-item ${active}" data-uid="${chat.receiver_id}" data-name="${escapeHtml(chat.name)}" data-avatar="${chat.avatar || ''}">
                    <div class="conversation-avatar">
                        <img src="${chat.avatar || '{{ asset("assets/img/doctor-avatar.jpg") }}'}" onerror="this.src='{{ asset("assets/img/doctor-avatar.jpg") }}'">
                        <span class="online-dot"></span>
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-name">
                            <span>${escapeHtml(chat.name)}</span>
                            ${badge}
                        </div>
                        <div class="conversation-specialty">${escapeHtml(chat.specialty || '')}</div>
                        <div class="conversation-last-msg">${escapeHtml(lastMsg)}</div>
                    </div>
                </div>
            `;
        }
        container.html(html);
        
        $('.conversation-item').off('click').on('click', function() {
            openChat($(this).data('uid'), $(this).data('name'), $(this).data('avatar'));
        });
    }

    async function openChat(uid, name, avatar) {
        if (!isReady) { showToast('Chat initializing...', 'warning'); return; }
        
        currentReceiver = uid;
        $('#activeChat').css('display', 'flex');
        $('#emptyChat').hide();
        $('#chatName').text(name);
        $('#chatStatus').text('Online');
        $('#chatAvatar').attr('src', avatar || '{{ asset("assets/img/doctor-avatar.jpg") }}');
        $('#inputArea').show();
        
        await loadMessages(uid);
        $('#messageInput').focus();
        
        try {
            await cometChat.markAsRead(uid, cometChat.RECEIVER_TYPE.USER);
        } catch(e) {}
        
        if (unreadCounts[uid]) {
            delete unreadCounts[uid];
            loadConversationsFromSDK();
        }
        
        $('.conversation-item').removeClass('active');
        $(`.conversation-item[data-uid="${uid}"]`).addClass('active');
    }

    async function loadMessages(uid) {
        if (!cometChat || !uid) return;
        
        try {
            $('#messagesArea').html('<div class="empty-state"><i class="bx bx-loader-alt bx-spin"></i><p>Loading messages...</p></div>');
            let messagesRequest = new cometChat.MessagesRequestBuilder().setUID(uid).setLimit(50).build();
            let messages = await messagesRequest.fetchPrevious();
            renderMessages(messages);
        } catch(e) {
            console.error(e);
            $('#messagesArea').html('<div class="empty-state"><i class="bx bx-error-circle"></i><p>Error loading messages</p></div>');
        }
    }

    function renderMessages(messages) {
        let container = $('#messagesArea');
        let wasAtBottom = container[0].scrollHeight - container[0].clientHeight <= container[0].scrollTop + 100;
        container.empty();
        
        if (!messages?.length) {
            container.html('<div class="empty-state"><i class="bx bx-chat"></i><p>No messages yet</p></div>');
            return;
        }
        
        let lastDate = '';
        for (let msg of messages) {
            let msgDate = formatDate(msg.getSentAt());
            if (msgDate !== lastDate) {
                container.append(`<div class="date-divider"><span>${msgDate}</span></div>`);
                lastDate = msgDate;
            }
            
            let isSentByMe = msg.getSender().getUid() === currentUser?.uid;
            let rowClass = isSentByMe ? 'sent' : 'received';
            let content = '';
            
            if (msg.getType() === 'image') {
                content = `<img src="${escapeHtml(msg.getData().url)}" class="attachment-image" onclick="window.showImagePreview('${escapeHtml(msg.getData().url)}')">`;
            } else if (msg.getType() === 'audio') {
                content = `<div class="attachment-audio"><i class="bx bx-headphone"></i><audio controls src="${escapeHtml(msg.getData().url)}"></audio></div>`;
            } else if (msg.getType() === 'file') {
                content = `<a href="${escapeHtml(msg.getData().url)}" class="attachment-file" target="_blank" download><i class="bx bx-file"></i><span>${escapeHtml(msg.getData().name || 'File')}</span></a>`;
            } else {
                content = `<div class="message-text">${escapeHtml(msg.getText())}</div>`;
            }
            
            container.append(`
                <div class="message-row ${rowClass}">
                    <div class="message-bubble ${rowClass}">
                        ${content}
                        <div class="message-time">${formatTime(msg.getSentAt())}</div>
                    </div>
                </div>
            `);
        }
        
        if (wasAtBottom) container.scrollTop(container[0].scrollHeight);
    }

    async function sendMessage() {
        let text = $('#messageInput').val().trim();
        if (!text || !currentReceiver) {
            showToast('Select a conversation and enter a message', 'warning');
            return;
        }
        if (!isReady) { showToast('Chat not ready', 'warning'); return; }
        
        let original = text;
        let tempId = 'temp_' + Date.now();
        
        // Optimistic update - add message instantly
        let tempHtml = `
            <div class="message-row sent" data-temp-id="${tempId}">
                <div class="message-bubble sent">
                    <div class="message-text">${escapeHtml(text)}</div>
                    <div class="message-time">Sending...</div>
                </div>
            </div>
        `;
        $('#messagesArea').append(tempHtml);
        scrollToBottom();
        
        $('#messageInput').val('');
        
        try {
            let textMessage = new cometChat.TextMessage(currentReceiver, text, cometChat.RECEIVER_TYPE.USER);
            let sentMessage = await cometChat.sendMessage(textMessage);
            
            // Replace temp message with real one
            $(`.message-row[data-temp-id="${tempId}"]`).fadeOut(200, function() {
                $(this).remove();
                let realHtml = `
                    <div class="message-row sent">
                        <div class="message-bubble sent">
                            <div class="message-text">${escapeHtml(sentMessage.getText())}</div>
                            <div class="message-time">${formatTime(sentMessage.getSentAt())}</div>
                        </div>
                    </div>
                `;
                $('#messagesArea').append(realHtml);
                scrollToBottom();
            });
            
            updateConversationLastMessage(currentReceiver, sentMessage);
            
            await fetch('{{ route("front.chat.notify") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: currentReceiver, message: text })
            });
            
        } catch(e) {
            showToast('Failed to send', 'error');
            $('#messageInput').val(original);
            $(`.message-row[data-temp-id="${tempId}"]`).remove();
        }
        $('#messageInput').focus();
    }

    function sendTyping() {
        if (!currentReceiver || !isReady || !cometChat) return;
        try {
            let typing = new cometChat.TypingIndicator(currentReceiver, cometChat.RECEIVER_TYPE.USER, "typing");
            cometChat.sendTypingIndicator(typing);
            if (typingTimeout) clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                let ended = new cometChat.TypingIndicator(currentReceiver, cometChat.RECEIVER_TYPE.USER, "ended");
                cometChat.sendTypingIndicator(ended);
            }, 1000);
        } catch(e) {}
    }

    async function sendFile(file, type) {
        if (!currentReceiver || !cometChat) {
            showToast('Chat not ready', 'error');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            showToast('File too large. Max 10MB', 'error');
            return;
        }
        
        try {
            showToast('Uploading...', 'info');
            let messageType = cometChat.MESSAGE_TYPE.FILE;
            if (type === 'image') messageType = cometChat.MESSAGE_TYPE.IMAGE;
            
            let mediaMessage = new cometChat.MediaMessage(
                currentReceiver,
                file,
                messageType,
                cometChat.RECEIVER_TYPE.USER
            );
            
            let sentMessage = await cometChat.sendMediaMessage(mediaMessage);
            showToast('Sent!', 'success');
            loadMessages(currentReceiver);
            updateConversationLastMessage(currentReceiver, sentMessage);
            
        } catch(error) {
            showToast('Failed to send: ' + error.message, 'error');
        }
    }

    // Event handlers
    $('#searchInput').on('keyup', function() {
        let term = $(this).val().toLowerCase();
        let filtered = conversations.filter(c => c.name.toLowerCase().includes(term));
        renderConversations(filtered);
    });
    
    $('#attachBtn').on('click', function(e) {
        e.stopPropagation();
        $('#attachMenu').toggleClass('show');
    });
    
    $(document).on('click', function() { $('#attachMenu').removeClass('show'); });
    
    $('.menu-item').on('click', function(e) {
        e.stopPropagation();
        let type = $(this).data('type');
        $('#attachMenu').removeClass('show');
        if (type === 'gallery') {
            $('#galleryInput').click();
        } else if (type === 'audio') {
            if (isRecording) stopRecording();
            else startRecording();
        } else if (type === 'document') {
            $('#documentInput').click();
        }
    });
    
    $('#galleryInput').on('change', function() {
        let file = this.files[0];
        if (file) sendFile(file, 'image');
        this.value = '';
    });
    
    $('#documentInput').on('change', function() {
        let file = this.files[0];
        if (file) sendFile(file, 'file');
        this.value = '';
    });
    
    $('#micBtn').on('click', function() {
        if (isRecording) stopRecording();
        else startRecording();
    });
    
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        } else if (e.which >= 32) {
            sendTyping();
        }
    });
    
    $('#sendBtn').on('click', sendMessage);
    
    window.showImagePreview = function(url) {
        let modal = $(`<div class="image-modal"><img src="${url}"></div>`);
        $('body').append(modal);
        modal.on('click', function() { $(this).remove(); });
    };
    
    init();
});
</script>
@endsection