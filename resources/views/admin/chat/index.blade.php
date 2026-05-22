{{-- resources/views/admin/chat/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Admin Chat')

@section('page-title', 'Admin Chat Management')

@section('content')
<style>
    .chat-app {
        display: flex;
        height: calc(100vh - 120px);
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .chat-sidebar {
        width: 360px;
        background: #f5f7fb;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-header {
        padding: 20px;
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .sidebar-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        color: #1e293b;
    }
    
    .sidebar-tabs {
        display: flex;
        padding: 10px 16px;
        gap: 8px;
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .tab-btn {
        flex: 1;
        padding: 8px;
        border: none;
        background: #f1f5f9;
        border-radius: 20px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .tab-btn.active {
        background: #25D366;
        color: white;
    }
    
    .search-box {
        padding: 12px 16px;
        background: #fff;
    }
    
    .search-box input {
        width: 100%;
        padding: 10px 16px;
        border: none;
        background: #f1f5f9;
        border-radius: 25px;
        font-size: 14px;
        outline: none;
    }
    
    .chats-list, .contacts-list {
        flex: 1;
        overflow-y: auto;
    }
    
    .contacts-list {
        display: none;
    }
    
    .contacts-list.show {
        display: block;
    }
    
    .chat-item, .contact-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .chat-item:hover, .contact-item:hover {
        background: #f1f5f9;
    }
    
    .chat-item.active, .contact-item.active {
        background: #e8f4e8;
    }
    
    .avatar {
        position: relative;
        margin-right: 12px;
    }
    
    .avatar img {
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
        background: #25D366;
        border-radius: 50%;
        border: 2px solid #fff;
    }
    
    .chat-info {
        flex: 1;
        min-width: 0;
    }
    
    .chat-name {
        font-weight: 600;
        font-size: 15px;
        color: #1e293b;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
    }
    
    .chat-name span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chat-preview {
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .badge {
        background: #25D366;
        color: white;
        border-radius: 20px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #efeae2;
        position: relative;
    }
    
    .chat-header {
        padding: 12px 20px;
        background: #f0f2f5;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    
    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .message {
        display: flex;
        margin-bottom: 4px;
    }
    
    .message.sent {
        justify-content: flex-end;
    }
    
    .message.received {
        justify-content: flex-start;
    }
    
    .bubble {
        max-width: 65%;
        padding: 8px 12px;
        border-radius: 18px;
        position: relative;
    }
    
    .message.sent .bubble {
        background: #dcf8c5;
        color: #1e293b;
        border-bottom-right-radius: 4px;
    }
    
    .message.received .bubble {
        background: white;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    }
    
    .message-text {
        font-size: 14px;
        line-height: 1.4;
        word-break: break-word;
    }
    
    .message-time {
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.7;
        text-align: right;
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
    
    .date-divider {
        text-align: center;
        margin: 16px 0;
    }
    
    .date-divider span {
        background: rgba(0,0,0,0.1);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
    }
    
    .input-area {
        padding: 12px 20px;
        background: #f0f2f5;
        border-top: 1px solid #e2e8f0;
    }
    
    .input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        border-radius: 30px;
        padding: 6px 16px;
    }
    
    .input-wrapper input {
        flex: 1;
        border: none;
        padding: 10px 0;
        font-size: 14px;
        outline: none;
    }
    
    .attach-btn, .mic-btn {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
        color: #64748b;
        padding: 5px;
        transition: all 0.2s;
    }
    
    .attach-btn:hover, .mic-btn:hover {
        color: #25D366;
    }
    
    .send-btn {
        background: #25D366;
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
        bottom: 80px;
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
        animation: fadeInUp 0.2s;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
    
    .recording {
        background: #ef4444 !important;
        animation: pulse 1s infinite;
        color: white !important;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
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
        font-weight: bold;
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
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 12px;
        background: white;
        border-radius: 18px;
    }
    
    .typing-dot {
        width: 8px;
        height: 8px;
        background: #94a3b8;
        border-radius: 50%;
        animation: bounce 1.4s infinite;
    }
    
    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    
    @keyframes bounce {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-6px);
        }
    }
    
    .loading-spinner {
        text-align: center;
        padding: 20px;
        color: #64748b;
    }
</style>

<div class="chat-app">
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h3>Chats</h3>
        </div>
        <div class="sidebar-tabs">
            <button class="tab-btn active" data-tab="chats">Chats</button>
            <button class="tab-btn" data-tab="contacts">Contacts</button>
        </div>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search..." autocomplete="off">
        </div>
        <div class="chats-list" id="chatsList">
            <div class="empty-state"><i class="bx bx-loader-alt bx-spin"></i><p>Loading chats...</p></div>
        </div>
        <div class="contacts-list" id="contactsList">
            <div class="empty-state"><i class="bx bx-user"></i><p>Loading contacts...</p></div>
        </div>
    </div>

    <div class="chat-main">
        <div id="activeChat" style="display: none; height: 100%; flex-direction: column;">
            <div class="chat-header">
                <img id="chatAvatar" class="chat-header-avatar" src="">
                <div>
                    <h4 id="chatName" style="margin:0;font-size:16px;">Select a chat</h4>
                    <p id="chatStatus" style="margin:0;font-size:12px;color:#64748b;">Click to start</p>
                </div>
            </div>
            <div class="messages-area" id="messagesArea">
                <div class="empty-state"><i class="bx bx-chat"></i><p>No messages yet</p></div>
            </div>
            <div class="input-area" id="inputArea" style="display: none;">
                <div class="input-wrapper">
                    <button class="attach-btn" id="attachBtn"><i class="bx bx-paperclip"></i></button>
                    <input type="text" id="messageInput" placeholder="Type a message..." autocomplete="off">
                    <button class="mic-btn" id="micBtn"><i class="bx bx-microphone"></i></button>
                    <button class="send-btn" id="sendBtn"><i class="bx bx-send"></i></button>
                </div>
                <div class="attach-menu" id="attachMenu">
                    <div class="menu-item" data-type="gallery"><i class="bx bx-images" style="color:#3b82f6;"></i><span>Gallery</span></div>
                    <div class="menu-item" data-type="audio"><i class="bx bx-microphone" style="color:#ef4444;"></i><span>Voice Note</span></div>
                    <div class="menu-item" data-type="document"><i class="bx bx-file" style="color:#8b5cf6;"></i><span>Document</span></div>
                </div>
            </div>
        </div>
        <div id="emptyChat" class="empty-state" style="display: flex;">
            <i class="bx bx-message-dots"></i>
            <p>Select a chat to start messaging</p>
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
    let currentReceiverName = '';
    let currentReceiverAvatar = '';
    let isReady = false;
    let conversations = [];
    let contacts = [];
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
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
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
            
            addMessageToUI({
                id: sentMessage.getId(),
                text: '🎵 Voice note',
                sender: sentMessage.getSender().getUid(),
                sent_at: sentMessage.getSentAt(),
                sent_time: formatTime(sentMessage.getSentAt()),
                is_sent_by_me: true,
                type: 'audio',
                url: sentMessage.getData().url,
                name: file.name
            });
            scrollToBottom();
            loadConversations();
            
        } catch (error) {
            showToast('Failed to send voice note: ' + error.message, 'error');
        }
    }
    
    async function init() {
        try {
            let res = await fetch('{{ route("admin.chat.init") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            });
            let result = await res.json();
            
            if (result.status !== '1') throw new Error(result.message);
            
            let { app_id, region, auth_token } = result.data;
            
            let settings = new CometChat.AppSettingsBuilder()
                .setRegion(region)
                .autoEstablishSocketConnection(true)
                .build();
            
            await CometChat.init(app_id, settings);
            currentUser = await CometChat.login(auth_token);
            cometChat = CometChat;
            isReady = true;
            
            setupMessageListener();
            await loadUsers();
            await loadConversations();
            
            initRecorder();
            showToast('Chat ready', 'success');
            
        } catch (error) {
            console.error('Init error:', error);
            showToast('Failed to initialize chat: ' + error.message, 'error');
        }
    }
    
    function setupMessageListener() {
        if (messageListenerId) {
            cometChat.removeMessageListener(messageListenerId);
        }
        
        messageListenerId = "admin_chat_" + Date.now();
        
        cometChat.addMessageListener(messageListenerId, new cometChat.MessageListener({
            onTextMessageReceived: message => {
                handleNewMessage(message);
            },
            onMediaMessageReceived: message => {
                handleNewMessage(message);
            },
            onTypingStarted: typingIndicator => {
                if (typingIndicator.getSender().getUid() === currentReceiver) {
                    $('#chatStatus').html('<span class="typing-indicator"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></span> typing...');
                }
            },
            onTypingEnded: typingIndicator => {
                if (typingIndicator.getSender().getUid() === currentReceiver) {
                    $('#chatStatus').text('Online');
                }
            }
        }));
    }
    
    function handleNewMessage(message) {
        let audio = document.getElementById('notifySound');
        if (audio) audio.play().catch(e => console.log);
        
        if (currentReceiver === message.sender.uid) {
            let messageText = message.text;
            let messageType = message.type;
            let url = null;
            let name = null;
            
            if (messageType === 'image') {
                messageText = '📷 Image';
                url = message.data?.url;
            } else if (messageType === 'audio') {
                messageText = '🎵 Voice note';
                url = message.data?.url;
            } else if (messageType === 'file') {
                messageText = '📎 File';
                url = message.data?.url;
                name = message.data?.name;
            }
            
            addMessageToUI({
                id: message.id,
                text: messageText,
                sender: message.sender.uid,
                sent_at: message.sentAt,
                sent_time: formatTime(message.sentAt),
                is_sent_by_me: false,
                type: messageType,
                url: url,
                name: name
            });
            scrollToBottom();
            cometChat.markAsRead(message.sender.uid, cometChat.RECEIVER_TYPE.USER);
        } else {
            showToast(`New message from ${message.sender.name}`);
            loadConversations();
        }
    }
    
    async function loadUsers() {
        try {
            let res = await fetch('{{ route("admin.chat.users") }}', {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            let data = await res.json();
            
            if (data.status === '1') {
                contacts = data.data;
                renderContacts(contacts);
            }
        } catch (error) {
            console.error('Load users error:', error);
        }
    }
    
    async function loadConversations() {
        try {
            let res = await fetch('{{ route("admin.chat.conversations") }}', {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            let data = await res.json();
            
            if (data.status === '1') {
                let users = data.data;
                let convos = [];
                
                if (cometChat && isReady) {
                    try {
                        let conversationsRequest = new cometChat.ConversationsRequestBuilder()
                            .setLimit(50)
                            .build();
                        
                        let conversationsList = await conversationsRequest.fetchNext();
                        
                        for (let conv of conversationsList) {
                            let lastMessage = conv.getLastMessage();
                            let conversationWith = conv.getConversationWith();
                            let user = users.find(u => u.uid === conversationWith.getUid());
                            
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
                                    role_name: user.role_name,
                                    last_message: lastMessageText,
                                    last_message_time: lastMessageTime,
                                    unread_count: conv.getUnreadMessageCount() || 0
                                });
                            }
                        }
                        
                        convos.sort((a, b) => b.last_message_time - a.last_message_time);
                        
                    } catch (err) {
                        console.error('SDK conversations error:', err);
                        for (let user of users) {
                            convos.push({
                                id: user.id,
                                receiver_id: user.uid,
                                name: user.name,
                                avatar: user.avatar,
                                role_name: user.role_name,
                                last_message: 'No messages yet',
                                last_message_time: 0,
                                unread_count: 0
                            });
                        }
                    }
                }
                
                conversations = convos;
                renderChats(conversations);
            }
        } catch (error) {
            console.error('Load conversations error:', error);
            $('#chatsList').html('<div class="empty-state"><i class="bx bx-error-circle"></i><p>Error loading chats</p></div>');
        }
    }
    
    function renderChats(chats) {
        if (!chats || chats.length === 0) {
            $('#chatsList').html('<div class="empty-state"><i class="bx bx-message-dots"></i><p>No chats yet</p></div>');
            return;
        }
        
        let html = '';
        for (let chat of chats) {
            let active = currentReceiver === chat.receiver_id ? 'active' : '';
            let badge = chat.unread_count > 0 ? `<span class="badge">${chat.unread_count}</span>` : '';
            
            html += `
                <div class="chat-item ${active}" data-uid="${chat.receiver_id}" data-name="${escapeHtml(chat.name)}" data-avatar="${chat.avatar || ''}">
                    <div class="avatar">
                        <img src="${chat.avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'}" onerror="this.src='{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'">
                        <span class="online-dot"></span>
                    </div>
                    <div class="chat-info">
                        <div class="chat-name">
                            <span>${escapeHtml(chat.name)}</span>
                            ${badge}
                        </div>
                        <div class="chat-preview">${escapeHtml(chat.last_message)}</div>
                    </div>
                </div>
            `;
        }
        
        $('#chatsList').html(html);
        
        $('.chat-item').off('click').on('click', function() {
            let uid = $(this).data('uid');
            let name = $(this).data('name');
            let avatar = $(this).data('avatar');
            openChat(uid, name, avatar);
        });
    }
    
    function renderContacts(contactsListData) {
        if (!contactsListData || contactsListData.length === 0) {
            $('#contactsList').html('<div class="empty-state"><i class="bx bx-user"></i><p>No contacts found</p></div>');
            return;
        }
        
        let html = '';
        for (let contact of contactsListData) {
            html += `
                <div class="contact-item" data-uid="${contact.uid}" data-name="${escapeHtml(contact.name)}" data-avatar="${contact.avatar || ''}">
                    <div class="avatar">
                        <img src="${contact.avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'}" onerror="this.src='{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'">
                        <span class="online-dot"></span>
                    </div>
                    <div class="chat-info">
                        <div class="chat-name"><span>${escapeHtml(contact.name)}</span></div>
                        <div class="chat-preview">${escapeHtml(contact.type || 'User')}</div>
                    </div>
                </div>
            `;
        }
        
        $('#contactsList').html(html);
        
        $('.contact-item').off('click').on('click', function() {
            let uid = $(this).data('uid');
            let name = $(this).data('name');
            let avatar = $(this).data('avatar');
            openChat(uid, name, avatar);
        });
    }
    
    async function openChat(uid, name, avatar) {
        if (!isReady) {
            showToast('Chat initializing...', 'warning');
            return;
        }
        
        currentReceiver = uid;
        currentReceiverName = name;
        currentReceiverAvatar = avatar;
        
        $('#activeChat').css('display', 'flex');
        $('#emptyChat').hide();
        $('#chatName').text(name);
        $('#chatStatus').text('Online');
        $('#chatAvatar').attr('src', avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}');
        $('#inputArea').show();
        
        await loadMessages(uid);
        $('#messageInput').focus();
        
        try {
            await cometChat.markAsRead(uid, cometChat.RECEIVER_TYPE.USER);
        } catch(e) {}
        
        $('.chat-item, .contact-item').removeClass('active');
        $(`.chat-item[data-uid="${uid}"], .contact-item[data-uid="${uid}"]`).addClass('active');
    }
    
    async function loadMessages(uid) {
        if (!cometChat || !uid) return;
        
        try {
            $('#messagesArea').html('<div class="empty-state"><i class="bx bx-loader-alt bx-spin"></i><p>Loading messages...</p></div>');
            
            let messagesRequest = new cometChat.MessagesRequestBuilder()
                .setUID(uid)
                .setLimit(50)
                .build();
            
            let messages = await messagesRequest.fetchPrevious();
            renderMessages(messages);
            
        } catch (error) {
            console.error('Load messages error:', error);
            $('#messagesArea').html('<div class="empty-state"><i class="bx bx-error-circle"></i><p>Error loading messages</p></div>');
        }
    }
    
    function renderMessages(messages) {
        let container = $('#messagesArea');
        let wasAtBottom = container[0].scrollHeight - container[0].clientHeight <= container[0].scrollTop + 100;
        container.empty();
        
        if (!messages || messages.length === 0) {
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
            let type = msg.getType();
            
            if (type === 'image') {
                let url = msg.getData().url;
                content = `<img src="${escapeHtml(url)}" class="attachment-image" onclick="window.showImagePreview('${escapeHtml(url)}')">`;
            } else if (type === 'audio') {
                let url = msg.getData().url;
                content = `<div class="attachment-audio"><i class="bx bx-headphone"></i><audio controls src="${escapeHtml(url)}"></audio></div>`;
            } else if (type === 'file') {
                let url = msg.getData().url;
                let name = msg.getData().name || 'File';
                content = `<a href="${escapeHtml(url)}" class="attachment-file" target="_blank" download><i class="bx bx-file"></i><span>${escapeHtml(name)}</span></a>`;
            } else {
                content = `<div class="message-text">${escapeHtml(msg.getText())}</div>`;
            }
            
            container.append(`
                <div class="message ${rowClass}">
                    <div class="bubble">
                        ${content}
                        <div class="message-time">${formatTime(msg.getSentAt())}</div>
                    </div>
                </div>
            `);
        }
        
        if (wasAtBottom) {
            container.scrollTop(container[0].scrollHeight);
        }
    }
    
    function addMessageToUI(message) {
        let container = $('#messagesArea');
        let isSentByMe = message.is_sent_by_me;
        let rowClass = isSentByMe ? 'sent' : 'received';
        let content = '';
        
        if (message.type === 'image') {
            content = `<img src="${escapeHtml(message.url)}" class="attachment-image" onclick="window.showImagePreview('${escapeHtml(message.url)}')">`;
        } else if (message.type === 'audio') {
            content = `<div class="attachment-audio"><i class="bx bx-headphone"></i><audio controls src="${escapeHtml(message.url)}"></audio></div>`;
        } else if (message.type === 'file') {
            content = `<a href="${escapeHtml(message.url)}" class="attachment-file" target="_blank" download><i class="bx bx-file"></i><span>${escapeHtml(message.name || 'File')}</span></a>`;
        } else {
            content = `<div class="message-text">${escapeHtml(message.text)}</div>`;
        }
        
        let lastMessage = container.find('.message').last();
        
        if (lastMessage.length === 0) {
            container.append(`<div class="date-divider"><span>${formatDate(message.sent_at)}</span></div>`);
        } else {
            let lastDateElement = container.find('.date-divider').last();
            let lastDate = lastDateElement.length ? lastDateElement.find('span').text() : '';
            let currentDate = formatDate(message.sent_at);
            if (currentDate !== lastDate) {
                container.append(`<div class="date-divider"><span>${currentDate}</span></div>`);
            }
        }
        
        container.append(`
            <div class="message ${rowClass}" data-message-id="${message.id}">
                <div class="bubble">
                    ${content}
                    <div class="message-time">${message.sent_time}</div>
                </div>
            </div>
        `);
    }
    
    async function sendMessage() {
        let text = $('#messageInput').val().trim();
        if (!text || !currentReceiver) {
            showToast('Select a contact and enter a message', 'warning');
            return;
        }
        if (!isReady) {
            showToast('Chat not ready', 'warning');
            return;
        }
        
        let original = text;
        let tempId = 'temp_' + Date.now();
        
        addMessageToUI({
            id: tempId,
            text: text,
            sender: currentUser?.uid,
            sent_at: Date.now(),
            sent_time: formatTime(Date.now()),
            is_sent_by_me: true,
            type: 'text'
        });
        
        $('#messageInput').val('');
        scrollToBottom();
        
        try {
            let textMessage = new cometChat.TextMessage(currentReceiver, text, cometChat.RECEIVER_TYPE.USER);
            let sentMessage = await cometChat.sendMessage(textMessage);
            
            $(`.message[data-message-id="${tempId}"]`).fadeOut(200, function() {
                $(this).remove();
                addMessageToUI({
                    id: sentMessage.getId(),
                    text: sentMessage.getText(),
                    sender: sentMessage.getSender().getUid(),
                    sent_at: sentMessage.getSentAt(),
                    sent_time: formatTime(sentMessage.getSentAt()),
                    is_sent_by_me: true,
                    type: 'text'
                });
                scrollToBottom();
            });
            
            loadConversations();
            
            await fetch('{{ route("admin.chat.notify") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: currentReceiver, message: text })
            });
            
        } catch (error) {
            showToast('Failed to send: ' + error.message, 'error');
            $('#messageInput').val(original);
            $(`.message[data-message-id="${tempId}"]`).remove();
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
            
            addMessageToUI({
                id: sentMessage.getId(),
                text: sentMessage.getData().caption || (type === 'image' ? '📷 Image' : '📎 File'),
                sender: sentMessage.getSender().getUid(),
                sent_at: sentMessage.getSentAt(),
                sent_time: formatTime(sentMessage.getSentAt()),
                is_sent_by_me: true,
                type: type,
                url: sentMessage.getData().url,
                name: file.name
            });
            scrollToBottom();
            loadConversations();
            
        } catch (error) {
            showToast('Failed to send: ' + error.message, 'error');
        }
    }
    
    // Event handlers
    $('.tab-btn').on('click', function() {
        let tab = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        
        if (tab === 'chats') {
            $('#chatsList').show();
            $('#contactsList').removeClass('show');
        } else {
            $('#chatsList').hide();
            $('#contactsList').addClass('show');
            if (contacts.length === 0) {
                loadUsers();
            }
        }
    });
    
    $('#searchInput').on('keyup', function() {
        let term = $(this).val().toLowerCase();
        let activeTab = $('.tab-btn.active').data('tab');
        
        if (activeTab === 'chats') {
            let filtered = conversations.filter(c => c.name.toLowerCase().includes(term));
            renderChats(filtered);
        } else {
            let filtered = contacts.filter(c => c.name.toLowerCase().includes(term));
            renderContacts(filtered);
        }
    });
    
    $('#attachBtn').on('click', function(e) {
        e.stopPropagation();
        $('#attachMenu').toggleClass('show');
    });
    
    $(document).on('click', function() {
        $('#attachMenu').removeClass('show');
    });
    
    $('.menu-item').on('click', function(e) {
        e.stopPropagation();
        let type = $(this).data('type');
        $('#attachMenu').removeClass('show');
        
        if (type === 'gallery') {
            $('#galleryInput').click();
        } else if (type === 'audio') {
            if (isRecording) {
                stopRecording();
            } else {
                startRecording();
            }
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
        if (file) {
            if (file.type.startsWith('image/')) {
                sendFile(file, 'image');
            } else {
                sendFile(file, 'file');
            }
        }
        this.value = '';
    });
    
    $('#micBtn').on('click', function() {
        if (isRecording) {
            stopRecording();
        } else {
            startRecording();
        }
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