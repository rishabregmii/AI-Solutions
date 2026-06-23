<!-- footer.php - Complete Updated Version -->

<!-- AI Assistant Floating Widget -->
<div class="ai-assistant" id="aiAssistant">
    <div class="assistant-toggle" id="assistantToggle">
        <span class="assistant-icon">🤖</span>
        <span class="assistant-text">AI Assistant</span>
    </div>
    <div class="assistant-chat" id="assistantChat">
        <div class="chat-header">
            <h4>AI Assistant</h4>
            <button class="close-chat" id="closeChat">×</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-message bot">
                Hello! 👋 Welcome to AI-Solutions. How can I assist you today?
            </div>
            <div class="quick-actions">
                <button class="quick-btn" data-message="Tell me about your solutions">💡 Tell me about your solutions</button>
                <button class="quick-btn" data-message="I need help with a project">🛠️ I need help with a project</button>
                <button class="quick-btn" data-message="Request a demo">🎥 Request a demo</button>
                <button class="quick-btn" data-message="Pricing information">💰 Pricing information</button>
                <button class="quick-btn" data-message="Contact information">📞 Contact information</button>
            </div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chatInput" placeholder="Type your message...">
            <button id="sendChatBtn">Send</button>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <!-- Footer About -->
        <div class="footer-box">
            <div class="footer-logo">
                <img src="images/logo.png" alt="Logo">
                <h2>AI-Solutions</h2>
            </div>
            <p>
                Helping businesses improve productivity through smart
                AI-powered solutions and modern digital transformation.
            </p>
            <!-- Social Icons -->
            <div class="social-icons">
                <a href="#">
                    <img src="images/fb.png" alt="Facebook">
                </a>
                <a href="#">
                    <img src="images/ln.png" alt="LinkedIn">
                </a>
                <a href="#">
                    <img src="images/x.png" alt="Twitter">
                </a>
                <a href="#">
                    <img src="images/yt.png" alt="YouTube">
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="footer-box">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="solutions.php">Solutions</a></li>
                <li><a href="insights.php">Insights</a></li>
                <li><a href="testimonials.php">Testimonials</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>

        <!-- Solutions -->
        <div class="footer-box">
            <h3>Solutions</h3>
            <ul>
                <li><a href="solutions.php">AI Virtual Assistant</a></li>
                <li><a href="solutions.php">Rapid AI Prototyping</a></li>
                <li><a href="solutions.php">Smart Automation Suite</a></li>
                <li><a href="solutions.php">Predictive Analytics Engine</a></li>
                <li><a href="solutions.php">Workplace Intelligence Hub</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="footer-box">
            <h3>Contact Us</h3>
            <p>Email: info@aisolutions.com</p>
            <p>Phone: +1 (555) 123-4567</p>
            <p>Location: Sunderland, United Kingdom</p>
        </div>

        <!-- Newsletter -->
        <div class="footer-box">
            <h3>Newsletter</h3>
            <p>
                Subscribe for latest AI updates and technology news.
            </p>
            <form class="newsletter-form" id="newsletterForm">
                <input type="email" id="newsletterEmail" placeholder="Enter your email" required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <p>© 2026 AI-Solutions. All rights reserved.</p>
    </div>
</footer>

<style>
    /* Footer Styles */
    .footer{
        background-color: #f8f9fa;
        margin-top: 60px;
        border-top: 1px solid #ddd;
    }

    .footer-container{
        width: 90%;
        margin: auto;
        padding: 50px 0;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 30px;
    }

    .footer-box{
        flex: 1;
        min-width: 220px;
    }

    .footer-logo{
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .footer-logo img{
        height: 80px;
        width: auto;
    }

    .footer-logo h2{
        font-size: 24px;
        color: #222;
    }

    .footer-box p{
        color: #555;
        line-height: 1.7;
        margin-bottom: 10px;
    }

    .footer-box h3{
        margin-bottom: 18px;
        color: #222;
    }

    .footer-box ul{
        list-style: none;
    }

    .footer-box ul li{
        margin-bottom: 12px;
    }

    .footer-box ul li a{
        text-decoration: none;
        color: #555;
        transition: 0.3s;
    }

    .footer-box ul li a:hover{
        color: #007bff;
    }

    .social-icons{
        display: flex;
        gap: 12px;
        margin-top: 15px;
    }

    .social-icons a img{
        width: 32px;
        height: 32px;
        transition: 0.3s;
    }

    .social-icons a img:hover{
        transform: scale(1.1);
    }

    .newsletter-form{
        display: flex;
        margin-top: 15px;
    }

    .newsletter-form input{
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        outline: none;
        border-radius: 4px 0 0 4px;
    }

    .newsletter-form button{
        padding: 10px 15px;
        border: none;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        transition: 0.3s;
        border-radius: 0 4px 4px 0;
    }

    .newsletter-form button:hover{
        background-color: #0056b3;
    }

    .footer-bottom{
        text-align: center;
        padding: 20px;
        border-top: 1px solid #ddd;
        color: #666;
        font-size: 14px;
    }

    @media(max-width: 768px){
        .footer-container{
            flex-direction: column;
        }

        .newsletter-form{
            flex-direction: column;
        }

        .newsletter-form input{
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .newsletter-form button{
            border-radius: 4px;
        }
    }
</style>

<!-- AI Assistant Styles -->
<style>
.ai-assistant {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.assistant-toggle {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 12px 12px;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    width: 48px;
    height: 48px;
    border-radius: 50%;
}

.assistant-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

/* When chat is open, expand the toggle to show text */
.ai-assistant.chat-open .assistant-toggle {
    padding: 12px 20px;
    gap: 10px;
    width: auto;
    height: auto;
    border-radius: 50px;
}

.ai-assistant.chat-open .assistant-text {
    display: inline;
}

.assistant-icon {
    font-size: 24px;
    transition: all 0.3s;
}

.assistant-text {
    display: none;
    font-size: 14px;
    transition: all 0.3s;
}

.assistant-chat {
    display: none;
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 350px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    animation: fadeIn 0.3s ease;
}

.assistant-chat.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h4 {
    margin: 0;
    font-size: 16px;
}

.close-chat {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: transform 0.2s;
}

.close-chat:hover {
    opacity: 0.8;
    transform: scale(1.1);
}

.chat-body {
    padding: 15px;
    min-height: 300px;
    max-height: 400px;
    overflow-y: auto;
    background: #f8f9fa;
}

.chat-message {
    padding: 10px 14px;
    border-radius: 18px;
    margin-bottom: 10px;
    max-width: 85%;
    word-wrap: break-word;
    white-space: pre-line;
    line-height: 1.5;
}

.chat-message.user {
    background: #007bff;
    color: white;
    margin-left: auto;
    text-align: right;
}

.chat-message.bot {
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 15px;
}

.quick-btn {
    background: white;
    border: 1px solid #ddd;
    padding: 10px 12px;
    border-radius: 25px;
    cursor: pointer;
    text-align: left;
    font-size: 13px;
    transition: all 0.3s;
}

.quick-btn:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateX(5px);
}

.chat-footer {
    display: flex;
    padding: 12px;
    background: white;
    border-top: 1px solid #eee;
    gap: 8px;
}

.chat-footer input {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 25px;
    outline: none;
    font-size: 13px;
    transition: border-color 0.3s;
}

.chat-footer input:focus {
    border-color: #007bff;
}

.chat-footer button {
    padding: 10px 18px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.3s;
}

.chat-footer button:hover {
    background: #0056b3;
    transform: scale(1.02);
}

.chat-footer button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Typing indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    align-items: center;
    padding: 10px 14px;
}

.typing-indicator span {
    animation: blink 1.4s infinite;
}

@keyframes blink {
    0%, 60%, 100% {
        opacity: 0.4;
    }
    30% {
        opacity: 1;
    }
}

/* Scrollbar styling */
.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.chat-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.chat-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

@media (max-width: 480px) {
    .ai-assistant {
        bottom: 15px;
        right: 15px;
    }
    
    .assistant-chat {
        width: 300px;
        right: -10px;
    }
    
    .assistant-toggle {
        width: 44px;
        height: 44px;
    }
    
    .assistant-icon {
        font-size: 22px;
    }
    
    /* On mobile, expand nicely when open */
    .ai-assistant.chat-open .assistant-toggle {
        padding: 10px 16px;
        gap: 8px;
    }
    
    .ai-assistant.chat-open .assistant-text {
        font-size: 13px;
    }
}
</style>

<!-- Include AI Assistant JS -->
<script src="js/ai-assistant.js"></script>

<!-- Newsletter Form Handling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('newsletterEmail').value;
            if (email) {
                alert('Thank you for subscribing! We\'ll send you the latest AI updates.');
                this.reset();
            }
        });
    }
});
</script>