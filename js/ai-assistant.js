// js/ai-assistant.js - Complete Updated Version

(function() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAIAssistant);
    } else {
        initAIAssistant();
    }
    
    function initAIAssistant() {
        // Get DOM elements
        const aiAssistant = document.getElementById('aiAssistant');
        const assistantToggle = document.getElementById('assistantToggle');
        const assistantChat = document.getElementById('assistantChat');
        const closeChat = document.getElementById('closeChat');
        const chatBody = document.getElementById('chatBody');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendChatBtn');
        
        // Check if elements exist
        if (!aiAssistant || !assistantToggle || !assistantChat || !chatBody) {
            console.error('AI Assistant elements not found');
            return;
        }
        
        // Function to update the chat-open class on the main container
        function updateAIAssistantClass() {
            if (assistantChat.classList.contains('active')) {
                aiAssistant.classList.add('chat-open');
            } else {
                aiAssistant.classList.remove('chat-open');
            }
        }
        
        // Toggle chat window
        assistantToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            assistantChat.classList.toggle('active');
            updateAIAssistantClass();
            
            // Focus on input when opened
            if (assistantChat.classList.contains('active') && chatInput) {
                setTimeout(() => chatInput.focus(), 200);
            }
        });
        
        // Close chat with close button
        if (closeChat) {
            closeChat.addEventListener('click', function() {
                assistantChat.classList.remove('active');
                updateAIAssistantClass();
            });
        }
        
        // Close chat when clicking outside
        document.addEventListener('click', function(e) {
            if (assistantChat.classList.contains('active')) {
                if (!aiAssistant.contains(e.target)) {
                    assistantChat.classList.remove('active');
                    updateAIAssistantClass();
                }
            }
        });
        
        // Prevent clicks inside chat from closing it
        assistantChat.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Function to add message to chat
        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${isUser ? 'user' : 'bot'}`;
            messageDiv.textContent = text;
            chatBody.appendChild(messageDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
            return messageDiv;
        }
        
        // Function to show typing indicator
        function showTypingIndicator() {
            // Remove existing typing indicator
            const existing = document.getElementById('typingIndicator');
            if (existing) existing.remove();
            
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chat-message bot typing-indicator';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = 'Thinking<span>.</span><span>.</span><span>.</span>';
            chatBody.appendChild(typingDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
            return typingDiv;
        }
        
        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        }
        
        // Send message to API
        async function sendMessageToAPI(message) {
            try {
                const response = await fetch('ai_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                return data.response || "Sorry, I didn't get a proper response.";
            } catch (error) {
                console.error('API Error:', error);
                return "Sorry, I'm having trouble connecting. Please try again later. If the problem persists, please email us at info@aisolutions.com";
            }
        }
        
        // Send message function
        let isSending = false;
        
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (message === '' || isSending) return;
            
            isSending = true;
            
            // Disable send button while processing
            if (sendBtn) {
                sendBtn.disabled = true;
            }
            
            // Add user message
            addMessage(message, true);
            chatInput.value = '';
            
            // Show typing indicator
            const typingIndicator = showTypingIndicator();
            
            try {
                // Get bot response from API
                const botResponse = await sendMessageToAPI(message);
                
                // Remove typing indicator and add bot response
                removeTypingIndicator();
                addMessage(botResponse, false);
            } catch (error) {
                removeTypingIndicator();
                addMessage("Sorry, an unexpected error occurred. Please try again.", false);
            } finally {
                isSending = false;
                if (sendBtn) {
                    sendBtn.disabled = false;
                }
                // Re-focus input
                chatInput.focus();
            }
        }
        
        // Send button click
        if (sendBtn) {
            sendBtn.addEventListener('click', sendMessage);
        }
        
        // Enter key press
        if (chatInput) {
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
        
        // Quick action buttons
        const quickBtns = document.querySelectorAll('.quick-btn');
        quickBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const message = this.getAttribute('data-message') || this.textContent;
                if (chatInput) {
                    // Remove emoji and extra text to clean up the message
                    let cleanMessage = message.replace(/[💡🛠️🎥💰📞]/g, '').trim();
                    chatInput.value = cleanMessage;
                    sendMessage();
                }
            });
        });
        
        // Initialize state (ensure chat is closed initially)
        if (!assistantChat.classList.contains('active')) {
            updateAIAssistantClass();
        }
        
        console.log('AI Assistant initialized successfully');
    }
})();