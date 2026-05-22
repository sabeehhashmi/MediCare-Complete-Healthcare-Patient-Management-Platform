{{-- resources/views/admin/chat/monitor.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Chat Monitor')

@section('page-title', 'Chat Monitor - View Conversations Between Users')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .chat-monitor-wrapper {
        background: #f0f2f5;
        height: calc(100vh - 100px);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }

    /* Main Layout */
    .monitor-container {
        display: flex;
        height: 100%;
        background: #fff;
    }

    /* Left Panel - Users List */
    .users-panel {
        width: 300px;
        background: #fff;
        border-right: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .panel-header {
        padding: 20px;
        background: #fff;
        border-bottom: 1px solid #e9ecef;
    }

    .panel-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 5px 0;
    }

    .panel-header p {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }

    .search-box {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        border-radius: 25px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s;
        background: #f8f9fa;
    }

    .search-box input:focus {
        border-color: #00bfff;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,191,255,0.1);
    }

    .users-list {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .user-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f0f2f5;
    }

    .user-item:hover {
        background: #f8f9fa;
    }

    .user-item.selected {
        background: #e3f2fd;
        border-left: 3px solid #00bfff;
    }

    .user-item.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8f9fa;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 4px;
    }

    .user-role {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }

    .role-admin { background: #f1f5f9; color: #1e293b; }
    .role-patient { background: #dbeafe; color: #1e40af; }
    .role-doctor { background: #dcfce7; color: #166534; }
    .role-hospital { background: #fef3c7; color: #92400e; }
    .role-clinic { background: #fef3c7; color: #92400e; }
    .role-agent { background: #e0e7ff; color: #3730a3; }
    .role-callcenter { background: #fce7f3; color: #9d174d; }

    /* Center Panel - Select Second User */
    .second-user-panel {
        width: 300px;
        background: #f8f9fa;
        border-right: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .second-user-list {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Right Panel - Conversation View */
    .conversation-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
    }

    .conversation-header {
        padding: 15px 25px;
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .participants-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .participant {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .participant-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .participant-details {
        display: flex;
        flex-direction: column;
    }

    .participant-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
    }

    .participant-role {
        font-size: 11px;
        color: #6c757d;
    }

    .vs-badge {
        font-size: 14px;
        font-weight: 600;
        color: #00bfff;
        background: #e3f2fd;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .refresh-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.2s;
        color: #6c757d;
    }

    .refresh-btn:hover {
        background: #f0f2f5;
        color: #00bfff;
    }

    /* Messages Area */
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 20px 25px;
        display: flex;
        flex-direction: column;
    }

    .message-wrapper {
        display: flex;
        margin-bottom: 15px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message-wrapper.left {
        justify-content: flex-start;
    }

    .message-wrapper.right {
        justify-content: flex-end;
    }

    .message-bubble {
        max-width: 65%;
        padding: 10px 14px;
        border-radius: 18px;
        position: relative;
    }

    .message-wrapper.left .message-bubble {
        background: white;
        color: #1a1a2e;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .message-wrapper.right .message-bubble {
        background: #00bfff;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-sender {
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 4px;
        opacity: 0.8;
    }

    .message-text {
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .message-time {
        font-size: 10px;
        margin-top: 5px;
        opacity: 0.7;
        text-align: right;
    }

    .attachment-image {
        max-width: 200px;
        border-radius: 12px;
        cursor: pointer;
    }

    .attachment-audio audio {
        height: 40px;
        width: 250px;
    }

    .attachment-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: rgba(0,0,0,0.05);
        border-radius: 8px;
        text-decoration: none;
        color: inherit;
        font-size: 13px;
    }

    .date-divider {
        text-align: center;
        margin: 20px 0;
    }

    .date-divider span {
        background: #e9ecef;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        color: #6c757d;
    }

    /* Empty States */
    .empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        text-align: center;
        padding: 40px;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 15px;
    }

    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        color: #adb5bd;
    }

    .error-message {
        background: #fee;
        color: #c00;
        padding: 10px;
        border-radius: 8px;
        margin: 10px;
        text-align: center;
    }

    /* Image Preview Modal */
    .image-preview-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .image-preview-modal img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
</style>

<div class="chat-monitor-wrapper">
    <div class="monitor-container">
        <!-- Left Panel: First User -->
        <div class="users-panel">
            <div class="panel-header">
                <h3>👤 User 1</h3>
                <p>Select first participant</p>
            </div>
            <div class="search-box">
                <input type="text" id="searchUser1" placeholder="Search users..." autocomplete="off">
            </div>
            <div class="users-list" id="user1List">
                <div class="loading">Loading users...</div>
            </div>
        </div>

        <!-- Center Panel: Second User -->
        <div class="second-user-panel" id="secondUserPanel" style="display: none;">
            <div class="panel-header">
                <h3>👤 User 2</h3>
                <p>Select second participant</p>
            </div>
            <div class="search-box">
                <input type="text" id="searchUser2" placeholder="Search users..." autocomplete="off">
            </div>
            <div class="second-user-list" id="user2List">
                <div class="empty-state">
                    <i class="bx bx-user"></i>
                    <p>Select User 1 first</p>
                </div>
            </div>
        </div>

        <!-- Right Panel: Conversation -->
        <div class="conversation-panel" id="conversationPanel" style="display: none;">
            <div class="conversation-header" id="conversationHeader">
                <div class="participants-info">
                    <!-- Dynamic content -->
                </div>
                <button class="refresh-btn" id="refreshConversationBtn" title="Refresh">
                    <i class="bx bx-refresh"></i>
                </button>
            </div>
            <div class="messages-container" id="messagesContainer">
                <div class="empty-state">
                    <i class="bx bx-conversation"></i>
                    <h4>Select two users to view conversation</h4>
                    <p>Choose User 1 and User 2 from the left panels</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/@cometchat/chat-sdk-javascript@4.0.5/CometChat.js"></script>
<script>
$(document).ready(function() {
    let cometChat = null;
    let isReady = false;
    let currentUser = null;
    let allUsers = [];
    let selectedUser1 = null;
    let selectedUser2 = null;
    let currentMessageListener = null;
    
    // Helper Functions
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
        let d = new Date(timestamp * 1000);
        let now = new Date();
        let diff = now - d;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return Math.floor(diff / 60000) + ' min ago';
        if (diff < 86400000) return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        return d.toLocaleDateString([], { month: 'short', day: 'numeric' });
    }
    
    function formatDateDivider(timestamp) {
        let d = new Date(timestamp * 1000);
        let today = new Date();
        let yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        if (d.toDateString() === today.toDateString()) return 'Today';
        if (d.toDateString() === yesterday.toDateString()) return 'Yesterday';
        return d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
    }
    
    function getRoleClass(roleId) {
        const classes = {
            1: 'role-admin', 7: 'role-patient', 6: 'role-doctor', 
            5: 'role-hospital', 8: 'role-clinic', 3: 'role-agent', 4: 'role-callcenter'
        };
        return classes[roleId] || 'role-patient';
    }
    
    function getRoleName(roleId) {
        const names = {
            1: 'Admin', 7: 'Patient', 6: 'Doctor', 5: 'Hospital', 8: 'Clinic', 3: 'Agent', 4: 'Call Center'
        };
        return names[roleId] || 'User';
    }
    
    // Initialize CometChat
    async function initCometChat() {
        try {
            let res = await fetch('{{ route("admin.chat.init") }}', {
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
            
            await loadUsers();
            
        } catch (error) {
            console.error('Init error:', error);
            $('#user1List').html('<div class="empty-state"><i class="bx bx-error-circle"></i> Failed to initialize: ' + error.message + '</div>');
        }
    }
    
    // Load all users
    async function loadUsers() {
        try {
            let res = await fetch('{{ route("admin.chat.monitor.users") }}', {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            let data = await res.json();
            if (data.status === '1') {
                allUsers = data.data;
                if (allUsers.length === 0) {
                    $('#user1List').html('<div class="empty-state"><i class="bx bx-user-x"></i> No users with CometChat accounts found. Please run sync command.</div>');
                } else {
                    renderUser1List(allUsers);
                }
            } else {
                $('#user1List').html('<div class="empty-state"><i class="bx bx-error-circle"></i> ' + (data.message || 'Error loading users') + '</div>');
            }
        } catch (error) {
            console.error('Load users error:', error);
            $('#user1List').html('<div class="empty-state"><i class="bx bx-error-circle"></i> Error loading users</div>');
        }
    }
    
    // Render User 1 List
    function renderUser1List(users, searchTerm = '') {
        let filtered = searchTerm ? users.filter(u => u.name.toLowerCase().includes(searchTerm)) : users;
        
        if (filtered.length === 0) {
            $('#user1List').html('<div class="empty-state"><i class="bx bx-search-alt"></i> No users found</div>');
            return;
        }
        
        let html = '';
        for (let user of filtered) {
            let roleClass = getRoleClass(user.role_id);
            let isSelected = selectedUser1 && selectedUser1.uid === user.uid;
            
            html += `
                <div class="user-item ${isSelected ? 'selected' : ''}" data-user='${JSON.stringify(user)}'>
                    <img class="user-avatar" src="${user.avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'}" onerror="this.src='{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'">
                    <div class="user-info">
                        <div class="user-name">${escapeHtml(user.name)}</div>
                        <div class="user-role ${roleClass}">${getRoleName(user.role_id)}</div>
                    </div>
                </div>
            `;
        }
        
        $('#user1List').html(html);
        
        // Bind click events
        $('#user1List .user-item').off('click').on('click', function() {
            let user = $(this).data('user');
            selectUser1(user);
        });
    }
    
    // Select User 1
    function selectUser1(user) {
        selectedUser1 = user;
        selectedUser2 = null;
        
        // Update UI
        renderUser1List(allUsers, $('#searchUser1').val().toLowerCase());
        
        // Show second user panel
        $('#secondUserPanel').show();
        
        // Render User 2 list (exclude selected user 1)
        let remainingUsers = allUsers.filter(u => u.uid !== user.uid);
        renderUser2List(remainingUsers);
        
        // Hide conversation panel until both selected
        $('#conversationPanel').hide();
    }
    
    // Render User 2 List
    function renderUser2List(users, searchTerm = '') {
        let filtered = searchTerm ? users.filter(u => u.name.toLowerCase().includes(searchTerm)) : users;
        
        if (filtered.length === 0) {
            $('#user2List').html('<div class="empty-state"><i class="bx bx-search-alt"></i> No users found</div>');
            return;
        }
        
        let html = '';
        for (let user of filtered) {
            let roleClass = getRoleClass(user.role_id);
            let isSelected = selectedUser2 && selectedUser2.uid === user.uid;
            
            html += `
                <div class="user-item ${isSelected ? 'selected' : ''}" data-user='${JSON.stringify(user)}'>
                    <img class="user-avatar" src="${user.avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'}" onerror="this.src='{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'">
                    <div class="user-info">
                        <div class="user-name">${escapeHtml(user.name)}</div>
                        <div class="user-role ${roleClass}">${getRoleName(user.role_id)}</div>
                    </div>
                </div>
            `;
        }
        
        $('#user2List').html(html);
        
        // Bind click events
        $('#user2List .user-item').off('click').on('click', function() {
            let user = $(this).data('user');
            selectUser2(user);
        });
    }
    
    // Select User 2
    function selectUser2(user) {
        selectedUser2 = user;
        
        // Update UI
        let remainingUsers = allUsers.filter(u => u.uid !== selectedUser1.uid);
        renderUser2List(remainingUsers, $('#searchUser2').val().toLowerCase());
        
        // Load conversation between the two users
        loadConversation();
    }
    
    // Load conversation between selected users
    async function loadConversation() {
        if (!selectedUser1 || !selectedUser2 || !isReady) return;
        
        // Show conversation panel
        $('#conversationPanel').show();
        
        // Update header
        updateConversationHeader();
        
        // Load messages
        await fetchConversationMessages();
        
        // Setup real-time listener for new messages
        setupMessageListener();
    }
    
    // Update conversation header
    function updateConversationHeader() {
        let html = `
            <div class="participants-info">
                <div class="participant">
                    <img class="participant-avatar" src="${selectedUser1.avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'}" onerror="this.src='{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'">
                    <div class="participant-details">
                        <span class="participant-name">${escapeHtml(selectedUser1.name)}</span>
                        <span class="participant-role">${getRoleName(selectedUser1.role_id)}</span>
                    </div>
                </div>
                <div class="vs-badge">VS</div>
                <div class="participant">
                    <img class="participant-avatar" src="${selectedUser2.avatar || '{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'}" onerror="this.src='{{ asset("admin-assets/assets/images/default-avatar.jpg") }}'">
                    <div class="participant-details">
                        <span class="participant-name">${escapeHtml(selectedUser2.name)}</span>
                        <span class="participant-role">${getRoleName(selectedUser2.role_id)}</span>
                    </div>
                </div>
            </div>
        `;
        $('#conversationHeader .participants-info').html(html);
    }
    
    // Setup real-time message listener
    function setupMessageListener() {
        // Remove old listener
        if (currentMessageListener) {
            cometChat.removeMessageListener(currentMessageListener);
        }
        
        currentMessageListener = "monitor_" + Date.now();
        cometChat.addMessageListener(currentMessageListener, {
            onTextMessageReceived: (message) => {
                // Check if message is between our selected users
                const isBetweenSelectedUsers = (
                    (message.sender.uid === selectedUser1.uid && message.receiver.uid === selectedUser2.uid) ||
                    (message.sender.uid === selectedUser2.uid && message.receiver.uid === selectedUser1.uid)
                );
                
                if (isBetweenSelectedUsers) {
                    appendSingleMessage(message);
                }
            },
            onMediaMessageReceived: (message) => {
                const isBetweenSelectedUsers = (
                    (message.sender.uid === selectedUser1.uid && message.receiver.uid === selectedUser2.uid) ||
                    (message.sender.uid === selectedUser2.uid && message.receiver.uid === selectedUser1.uid)
                );
                
                if (isBetweenSelectedUsers) {
                    appendSingleMessage(message);
                }
            }
        });
    }
    
    // Fetch conversation messages between two users
    async function fetchConversationMessages() {
        if (!cometChat || !isReady) return;
        
        $('#messagesContainer').html('<div class="loading"><i class="bx bx-loader-alt bx-spin"></i> Loading conversation...</div>');
        
        try {
            // Use the UID of user2 to get conversation
            let messagesRequest = new cometChat.MessagesRequestBuilder()
                .setUID(selectedUser2.uid)
                .setLimit(50)
                .build();
            
            let messages = await messagesRequest.fetchPrevious();
            renderConversationMessages(messages);
            
        } catch (error) {
            console.error('Load conversation error:', error);
            
            let errorMsg = '';
            if (error.code === 'ERR_UID_NOT_FOUND') {
                errorMsg = 'User not found in CometChat. Please run sync command: php artisan cometchat:sync-users';
            } else {
                errorMsg = error.message || 'Error loading conversation';
            }
            
            $('#messagesContainer').html(`
                <div class="empty-state">
                    <i class="bx bx-error-circle"></i>
                    <h4>Error loading conversation</h4>
                    <p>${escapeHtml(errorMsg)}</p>
                    <small style="margin-top: 10px; display: block;">Make sure both users have CometChat accounts created.</small>
                </div>
            `);
        }
    }
    
    // Render conversation messages
    function renderConversationMessages(messages) {
        let container = $('#messagesContainer');
        container.empty();
        
        if (!messages || messages.length === 0) {
            container.html('<div class="empty-state"><i class="bx bx-chat"></i> No messages between these users yet</div>');
            return;
        }
        
        let lastDate = '';
        for (let i = messages.length - 1; i >= 0; i--) {
            let msg = messages[i];
            let msgDate = formatDateDivider(msg.getSentAt());
            
            if (msgDate !== lastDate) {
                container.append(`<div class="date-divider"><span>${msgDate}</span></div>`);
                lastDate = msgDate;
            }
            
            appendSingleMessage(msg, false);
        }
        
        // Scroll to bottom
        container.scrollTop(container[0].scrollHeight);
    }
    
    // Append a single message
    function appendSingleMessage(msg, scrollToBottom = true) {
        // Determine if message is from User 1 or User 2
        let isFromUser1 = msg.sender.uid === selectedUser1.uid;
        let sender = isFromUser1 ? selectedUser1 : selectedUser2;
        let alignment = isFromUser1 ? 'left' : 'right';
        let content = '';
        let type = msg.getType();
        
        if (type === 'image') {
            let url = msg.getData().url;
            content = `<img src="${escapeHtml(url)}" class="attachment-image" onclick="showImagePreview('${escapeHtml(url)}')">`;
        } else if (type === 'audio') {
            let url = msg.getData().url;
            content = `<div class="attachment-audio"><audio controls src="${escapeHtml(url)}"></audio></div>`;
        } else if (type === 'file') {
            let url = msg.getData().url;
            let name = msg.getData().name || 'File';
            content = `<a href="${escapeHtml(url)}" class="attachment-file" target="_blank" download>📎 ${escapeHtml(name)}</a>`;
        } else {
            content = `<div class="message-text">${escapeHtml(msg.getText())}</div>`;
        }
        
        let messageHtml = `
            <div class="message-wrapper ${alignment}">
                <div class="message-bubble">
                    <div class="message-sender">${escapeHtml(sender.name)} (${getRoleName(sender.role_id)})</div>
                    ${content}
                    <div class="message-time">${formatTime(msg.getSentAt())}</div>
                </div>
            </div>
        `;
        
        let container = $('#messagesContainer');
        container.append(messageHtml);
        
        if (scrollToBottom) {
            container.scrollTop(container[0].scrollHeight);
        }
    }
    
    // Search functionality
    $('#searchUser1').on('input', function() {
        let searchTerm = $(this).val().toLowerCase();
        renderUser1List(allUsers, searchTerm);
    });
    
    $('#searchUser2').on('input', function() {
        if (selectedUser1) {
            let remainingUsers = allUsers.filter(u => u.uid !== selectedUser1.uid);
            let searchTerm = $(this).val().toLowerCase();
            renderUser2List(remainingUsers, searchTerm);
        }
    });
    
    // Refresh conversation
    $('#refreshConversationBtn').on('click', function() {
        if (selectedUser1 && selectedUser2) {
            fetchConversationMessages();
        }
    });
    
    // Image preview
    window.showImagePreview = function(url) {
        let modal = $(`<div class="image-preview-modal"><img src="${url}"></div>`);
        $('body').append(modal);
        modal.on('click', function() { $(this).remove(); });
    };
    
    // Auto-refresh conversation every 10 seconds if both users selected
    let refreshInterval = null;
    
    function startAutoRefresh() {
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(async () => {
            if (isReady && selectedUser1 && selectedUser2 && $('#conversationPanel').is(':visible')) {
                await fetchConversationMessages();
            }
        }, 10000);
    }
    
    startAutoRefresh();
    
    // Initialize
    initCometChat();
});
</script>
@endsection