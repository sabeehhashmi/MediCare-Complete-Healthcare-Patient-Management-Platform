{{-- resources/views/doctor/chat/index.blade.php --}}
@include('doctor.template.header')

<style>
    /* Chat Container Styles */
    .chat-wrapper {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        height: calc(100vh - 200px);
        min-height: 550px;
        display: flex;
        flex-direction: row;
        position: relative;
    }

    /* Sidebar Styles */
    .chat-sidebar {
        width: 320px;
        background: #fff;
        border-right: 1px solid #eef2f6;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        height: 100%;
    }

    .chat-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #eef2f6;
        background: #fff;
    }

    .chat-sidebar-header h5 {
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
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

    .chat-search input:focus {
        outline: none;
        border-color: #1baeff;
        background: #fff;
    }

    /* Conversation List */
    .conversation-list {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .conversation-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
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
        width: 45px;
        height: 45px;
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

    .conversation-last-msg {
        font-size: 11px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .unread-badge {
        background: #ef4444;
        color: white;
        border-radius: 20px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 600;
        min-width: 18px;
        text-align: center;
    }

    /* Chat Main Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        min-width: 0;
        height: 100%;
    }

    .chat-header {
        padding: 15px 20px;
        background: #fff;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
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
        font-size: 15px;
    }

    .chat-header-info p {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }

    /* Messages Area */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Message Bubbles */
    .message-row {
        display: flex;
        width: 100%;
        margin-bottom: 8px;
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

    .message-bubble.sent .attachment-file {
        background: rgba(255,255,255,0.2);
        color: white;
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

    /* Typing Indicator */
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

    /* Input Area */
    .chat-input-area {
        padding: 15px 20px;
        background: #fff;
        border-top: 1px solid #eef2f6;
        flex-shrink: 0;
        position: relative;
    }

    .input-wrapper {
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

    .input-wrapper input:focus {
        outline: none;
    }

    .attach-btn, .mic-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #64748b;
        padding: 5px;
        transition: all 0.2s;
    }

    .attach-btn:hover, .mic-btn:hover {
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
        transition: background 0.2s;
    }

    .send-btn:hover {
        background: #0099ff;
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
        animation: fadeInUp 0.2s ease;
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
        transition: background 0.2s;
    }

    .menu-item:hover {
        background: #f1f5f9;
    }

    .menu-item i {
        font-size: 20px;
        width: 24px;
        text-align: center;
    }

    .menu-item span {
        font-size: 14px;
        color: #1e293b;
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

    /* Empty State */
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

    .empty-state h5 {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 13px;
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

    /* Scrollbar */
    .chat-messages::-webkit-scrollbar,
    .conversation-list::-webkit-scrollbar {
        width: 4px;
    }

    .chat-messages::-webkit-scrollbar-track,
    .conversation-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb,
    .conversation-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
</style>

<div class="position-relative mb-5">
    <div class="chat-wrapper">
        <!-- Sidebar -->
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

        <!-- Main Chat Area -->
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
                        <h5>No conversation selected</h5>
                        <p>Choose a patient from the left to start chatting</p>
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
                        <div class="menu-item" data-type="gallery"><i class="bx bx-images" style="color:#3b82f6;"></i><span>Gallery</span></div>
                        <div class="menu-item" data-type="audio"><i class="bx bx-microphone" style="color:#ef4444;"></i><span>Voice Note</span></div>
                        <div class="menu-item" data-type="document"><i class="bx bx-file" style="color:#8b5cf6;"></i><span>Document</span></div>
                    </div>
                </div>
            </div>
            <div id="emptyChat" class="empty-state" style="display: flex; height: 100%;">
                <i class="bx bx-message-dots"></i>
                <h5>Welcome to Messages</h5>
                <p>Select a patient to start chatting</p>
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

@include('doctor.template.footer')

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
            updateConversationLastMessage(currentReceiver, sentMessage);
            
        } catch (error) {
            showToast('Failed to send voice note: ' + error.message, 'error');
        }
    }

    async function init() {
        try {
            let res = await fetch('{{ route("doctor.chat.init") }}', {
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
        
        messageListenerId = "doctor_chat_" + Date.now();
        
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

    // FIXED: Handle new messages with proper unread count
    function handleNewMessage(message) {
        let audio = document.getElementById('notifySound');
        if (audio) audio.play().catch(e => console.log);
        
        // Check if this message is from the currently open chat
        if (currentReceiver === message.sender.uid) {
            // Add message to UI immediately
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
            
            // Mark as read immediately for current chat
            cometChat.markAsRead(message.sender.uid, cometChat.RECEIVER_TYPE.USER);
            
            // Update conversation preview
            updateConversationLastMessage(currentReceiver, message);
            
            // Clear unread count for this conversation
            if (unreadCounts[currentReceiver]) {
                delete unreadCounts[currentReceiver];
            }
            // Also update unread count in the conversation object
            let convIndex = conversations.findIndex(c => c.receiver_id === currentReceiver);
            if (convIndex !== -1) {
                conversations[convIndex].unread_count = 0;
            }
            renderConversations(conversations);
            
        } else {
            // Message from other user - increment unread count
            const senderUid = message.sender.uid;
            unreadCounts[senderUid] = (unreadCounts[senderUid] || 0) + 1;
            
            // Also update unread count in conversation object
            let convIndex = conversations.findIndex(c => c.receiver_id === senderUid);
            if (convIndex !== -1) {
                conversations[convIndex].unread_count = (conversations[convIndex].unread_count || 0) + 1;
            }
            
            showToast(`📩 New message from ${message.sender.name}`);
            updateConversationLastMessage(senderUid, message);
            renderConversations(conversations);
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
            let res = await fetch('{{ route("doctor.chat.conversations") }}', {
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

    // FIXED: Load conversations from SDK with unread count
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
                    let unreadCount = conv.getUnreadMessageCount() || 0;
                    
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
                    
                    // If this is the currently open chat, unread count should be 0
                    if (currentReceiver === user.uid) {
                        unreadCount = 0;
                        // Also clear from unreadCounts object
                        if (unreadCounts[user.uid]) {
                            delete unreadCounts[user.uid];
                        }
                    }
                    
                    convos.push({
                        id: user.id,
                        receiver_id: user.uid,
                        name: user.name,
                        avatar: user.avatar,
                        type: user.type,
                        last_message: lastMessageText,
                        last_message_time: lastMessageTime,
                        unread_count: unreadCount
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
                        type: user.type,
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
                let unreadCount = 0;
                if (currentReceiver === user.uid) {
                    unreadCount = 0;
                }
                convos.push({
                    id: user.id,
                    receiver_id: user.uid,
                    name: user.name,
                    avatar: user.avatar,
                    type: user.type,
                    last_message: 'No messages yet',
                    last_message_time: 0,
                    unread_count: unreadCount
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
            // Use unread_count from chat object (which is now properly managed)
            let unread = chat.unread_count || 0;
            let badge = unread > 0 ? `<span class="unread-badge">${unread > 9 ? '9+' : unread}</span>` : '';
            let lastMsg = chat.last_message || 'No messages yet';
            if (lastMsg.length > 40) lastMsg = lastMsg.substring(0, 37) + '...';
            
            html += `
                <div class="conversation-item ${active}" data-uid="${chat.receiver_id}" data-name="${escapeHtml(chat.name)}" data-avatar="${chat.avatar || ''}">
                    <div class="conversation-avatar">
                        <img src="${chat.avatar || '{{ asset("doctor/assets/images/patient-avatar.jpg") }}'}" onerror="this.src='{{ asset("doctor/assets/images/patient-avatar.jpg") }}'">
                        <span class="online-dot"></span>
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-name">
                            <span>${escapeHtml(chat.name)}</span>
                            ${badge}
                        </div>
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

    // FIXED: Open chat and clear unread count
    async function openChat(uid, name, avatar) {
        if (!isReady) { showToast('Chat initializing...', 'warning'); return; }
        
        currentReceiver = uid;
        
        // Clear unread count for this conversation BEFORE loading
        if (unreadCounts[uid]) {
            delete unreadCounts[uid];
        }
        
        // Update conversation object unread count to 0
        let convIndex = conversations.findIndex(c => c.receiver_id === uid);
        if (convIndex !== -1) {
            conversations[convIndex].unread_count = 0;
        }
        
        // Refresh the conversation list to remove badge immediately
        renderConversations(conversations);
        
        $('#activeChat').css('display', 'flex');
        $('#emptyChat').hide();
        $('#chatName').text(name);
        $('#chatStatus').text('Online');
        $('#chatAvatar').attr('src', avatar || '{{ asset("doctor/assets/images/patient-avatar.jpg") }}');
        $('#inputArea').show();
        
        await loadMessages(uid);
        $('#messageInput').focus();
        
        // Mark all messages as read in CometChat
        try {
            await cometChat.markAsRead(uid, cometChat.RECEIVER_TYPE.USER);
        } catch(e) {}
        
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
        
        let lastMessage = container.find('.message-row').last();
        
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
            <div class="message-row ${rowClass}" data-message-id="${message.id}">
                <div class="message-bubble ${rowClass}">
                    ${content}
                    <div class="message-time">${message.sent_time}</div>
                </div>
            </div>
        `);
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
            
            await fetch('{{ route("doctor.chat.notify") }}', {
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