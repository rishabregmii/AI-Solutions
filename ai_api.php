<?php
// ai_api.php - Enhanced AI Assistant API endpoint with intelligent responses

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get the user message from POST request
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = isset($input['message']) ? trim($input['message']) : '';

if (empty($userMessage)) {
    echo json_encode(['response' => 'Please type a message.']);
    exit;
}

// Configuration
$useOpenAI = false;
$openAIApiKey = 'YOUR_OPENAI_API_KEY_HERE';

function getAIReply($message) {
    global $useOpenAI, $openAIApiKey;
    
    if ($useOpenAI && !empty($openAIApiKey) && $openAIApiKey != 'YOUR_OPENAI_API_KEY_HERE') {
        $aiResponse = callOpenAI($message, $openAIApiKey);
        if ($aiResponse) {
            return $aiResponse;
        }
    }
    
    return getIntelligentResponse($message);
}

function callOpenAI($message, $apiKey) {
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    
    $systemPrompt = "You are an AI assistant for AI-Solutions. Help users with information about our services, training, events, pricing, and company.";
    
    $postData = json_encode([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $message]
        ],
        'temperature' => 0.7,
        'max_tokens' => 500
    ]);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? null;
    }
    
    return null;
}

function getIntelligentResponse($message) {
    $msg = strtolower(trim($message));
    
    // Company info
    $companyInfo = [
        'name' => 'AI-Solutions',
        'location' => 'Sunderland, United Kingdom',
        'email' => 'info@aisolutions.com',
        'phone' => '+1 (555) 123-4567',
        'founded' => '2018',
        'mission' => 'Helping businesses improve productivity through smart AI-powered solutions'
    ];
    
    // What We Offer - Shortened
    $services = [
        'ai_virtual_assistant' => 'AI Virtual Assistant - 24/7 real-time customer support and productivity',
        'digital_employee_experience' => 'Digital Employee Experience Platform - Monitors and improves workplace engagement',
        'rapid_prototyping' => 'Rapid AI Prototyping - Turn ideas into working prototypes in days',
        'smart_automation' => 'Smart Automation Suite - Automate repetitive tasks and workflows',
        'predictive_analytics' => 'Predictive Analytics Engine - Forecast trends and make data-driven decisions',
        'workplace_intelligence' => 'Workplace Intelligence Hub - Real-time insights on productivity and engagement'
    ];
    
    // Training We Offer
    $training = [
        'ai_employee_experience' => 'AI for Employee Experience - Improve daily work with AI automation',
        'digital_workplace_tools' => 'Digital Workplace AI Tools - Hands-on AI for communication & collaboration',
        'ai_problem_solving' => 'AI-Driven Problem Solving - Rapid issue identification and solution design',
        'ai_employee_engagement' => 'AI for Employee Engagement - Analyze feedback and predict trends',
        'ai_innovation_leadership' => 'AI Innovation Leadership - Lead AI transformation initiatives',
        'future_digital_workplace' => 'Future of Digital Workplace - Strategic AI planning'
    ];
    
    // Future Events
    $events = [
        'webinar' => 'Digital Workplace Transformation Webinar - Free webinar on AI employee experience (Jul 20, 2020, 11:00 AM)',
        'workshop' => 'Rapid Prototyping Workshop - Build a working AI prototype in one day (Aug 10, 2020, 9:00 AM)',
        'meetup' => 'AI Solutions Annual Meetup - Network with team and community (Sep 10, 2020, 12:00 PM)',
        'masterclass' => 'Predictive Analytics Masterclass - Deep dive for business leaders (Oct 05, 2025, 11:00 AM)',
        'conference' => 'Innovation Employee Experience Conference - Future of work & AI in HR (Nov 12, 2025, 12:00 AM)',
        'summit' => 'AI-Solutions Launch Summit 2026 - Launch of our AI platforms (Dec 06, 2025, 12:00 AM)'
    ];
    
    // 1. Greetings
    if (preg_match('/^(hi|hello|hey|good morning|good afternoon|good evening|hola|greetings)$/i', $msg)) {
        return "Hello! 👋 Welcome to AI-Solutions. I can help you with our services, training, events, pricing, and more. What would you like to know?";
    }
    
    // 2. What We Offer
    if (preg_match('/what (services|solutions|products|do you offer|what we offer|offerings)/i', $msg)) {
        return "**✨ What We Offer**\n\n" .
               "🤖 **AI Virtual Assistant** - 24/7 real-time customer support\n" .
               "💻 **Digital Employee Experience Platform** - Improves workplace engagement\n" .
               "⚡ **Rapid AI Prototyping** - Working prototypes in days\n" .
               "🔧 **Smart Automation Suite** - Automate repetitive tasks\n" .
               "📊 **Predictive Analytics Engine** - Forecast trends & decisions\n" .
               "🏢 **Workplace Intelligence Hub** - Real-time productivity insights\n\n" .
               "Which one interests you? Type the name for more details!";
    }
    
    // 3. Specific service details - shortened
    if (preg_match('/virtual assistant|ai virtual/i', $msg)) {
        return "🤖 **AI Virtual Assistant** - Our flagship AI responds in real-time, provides instant support, and enhances customer experience. Benefits: 24/7 availability, 60% cost reduction, handles 1000+ conversations. Want a demo?";
    }
    
    if (preg_match('/digital employee experience|employee experience platform/i', $msg)) {
        return "💻 **Digital Employee Experience Platform** - Monitors, analyzes, and improves employee experiences. Speeds up decision-making, engagement, and innovation. Interested in seeing how it works?";
    }
    
    if (preg_match('/rapid prototyping|rapid ai/i', $msg)) {
        return "⚡ **Rapid AI Prototyping** - Turn ideas into working prototypes in days, not months. Perfect for testing AI concepts quickly with minimal investment.";
    }
    
    if (preg_match('/smart automation|automation suite/i', $msg)) {
        return "🔧 **Smart Automation Suite** - Automate repetitive tasks, streamline workflows, and reduce costs. Benefits: 80% reduction in manual work, 24/7 operation.";
    }
    
    if (preg_match('/predictive analytics|analytics engine/i', $msg)) {
        return "📊 **Predictive Analytics Engine** - Forecast trends, predict behavior, and make data-driven decisions. 95% forecast accuracy. Want to see a case study?";
    }
    
    if (preg_match('/workplace intelligence|intelligence hub/i', $msg)) {
        return "🏢 **Workplace Intelligence Hub** - Central dashboard for employee engagement, productivity metrics, and satisfaction insights.";
    }
    
    // 4. Training - shortened
    if (preg_match('/training|courses|learn|what training|training programs/i', $msg)) {
        return "**📚 Training Programs**\n\n" .
               "🤖 **AI for Employee Experience** - Improve daily work with AI\n" .
               "💻 **Digital Workplace AI Tools** - AI for communication & collaboration\n" .
               "🎯 **AI-Driven Problem Solving** - Rapid issue identification\n" .
               "📈 **AI for Employee Engagement** - Analyze feedback & trends\n" .
               "🚀 **AI Innovation Leadership** - Lead AI transformation\n" .
               "🔮 **Future of Digital Workplace** - Strategic AI planning\n\n" .
               "Type the training name for details or ask about registration!";
    }
    
    // 5. Specific training details
    if (preg_match('/employee experience training|ai for employee experience/i', $msg)) {
        return "🤖 **AI for Employee Experience** - Learn to automate routine tasks and free up time for creative work. Perfect for all employees. Hands-on workshop available.";
    }
    
    if (preg_match('/digital workplace tools|workplace ai tools/i', $msg)) {
        return "💻 **Digital Workplace AI Tools** - Hands-on training for communication, collaboration, and productivity tools. Practical exercises included.";
    }
    
    if (preg_match('/problem solving|ai driven problem/i', $msg)) {
        return "🎯 **AI-Driven Problem Solving** - Learn rapid issue identification, root cause analysis, and AI-powered solution design for workplace scenarios.";
    }
    
    if (preg_match('/employee engagement training|ai for employee engagement/i', $msg)) {
        return "📈 **AI for Employee Engagement** - Use AI to analyze feedback, predict engagement trends, and create better workplace experiences.";
    }
    
    if (preg_match('/innovation leadership|ai innovation/i', $msg)) {
        return "🚀 **AI Innovation Leadership** - For managers and team leaders to lead AI initiatives that transform digital employee experiences.";
    }
    
    if (preg_match('/future digital workplace|future of workplace/i', $msg)) {
        return "🔮 **Future of Digital Workplace** - Strategic planning for AI-powered employee experiences. Design and innovate faster with AI.";
    }
    
    // 6. Events - shortened
    if (preg_match('/events|webinar|workshop|meetup|conference|summit|future events|upcoming events/i', $msg)) {
        return "**📅 Upcoming Events**\n\n" .
               "🎓 **Digital Workplace Webinar** - Jul 20, 2020, 11:00 AM (Free)\n" .
               "🔧 **Rapid Prototyping Workshop** - Aug 10, 2020, 9:00 AM\n" .
               "🤝 **Annual Meetup** - Sep 10, 2020, 12:00 PM\n" .
               "📊 **Predictive Analytics Masterclass** - Oct 05, 2025, 11:00 AM\n" .
               "💡 **Innovation Conference** - Nov 12, 2025\n" .
               "🚀 **Launch Summit 2026** - Dec 06, 2025\n\n" .
               "Type an event name for details or ask to register!";
    }
    
    // 7. Specific event details
    if (preg_match('/digital workplace transformation|webinar/i', $msg) && !preg_match('/masterclass|conference|summit/i', $msg)) {
        return "🎓 **Digital Workplace Transformation Webinar**\n📅 Jul 20, 2020 at 11:00 AM\nFree webinar on transforming employee experience with AI. Learn from industry experts. Register now!";
    }
    
    if (preg_match('/rapid prototyping workshop|prototyping workshop/i', $msg)) {
        return "🔧 **Rapid Prototyping Workshop**\n📅 Aug 10, 2020 at 9:00 AM\nHands-on workshop - build a working AI prototype in one day. Perfect for product managers and founders.";
    }
    
    if (preg_match('/annual meetup|solutions annual/i', $msg)) {
        return "🤝 **AI Solutions Annual Meetup**\n📅 Sep 10, 2020 at 12:00 PM\nConnect with our team, demo new features, network with peers. Special guests from Sunderland Software City!";
    }
    
    if (preg_match('/predictive analytics masterclass|analytics masterclass/i', $msg)) {
        return "📊 **Predictive Analytics Masterclass**\n📅 Oct 05, 2025 at 11:00 AM\nDeep dive for business leaders. Case studies from healthcare, finance, and retail industries.";
    }
    
    if (preg_match('/innovation conference|employee experience conference/i', $msg)) {
        return "💡 **Innovation Employee Experience Conference**\n📅 Nov 12, 2025\nTwo-day conference on future of work, AI in HR, and digital employee experience trends.";
    }
    
    if (preg_match('/launch summit|summit 2026/i', $msg)) {
        return "🚀 **AI-Solutions Launch Summit 2026**\n📅 Dec 06, 2025\nOfficial launch of AI Virtual Assistant and Digital Employee Experience Platform. Keynotes, live demo, networking!";
    }
    
    // 8. Register for events
    if (preg_match('/register|sign up|join|attend/i', $msg)) {
        return "📝 To register for any event, please email us at info@aisolutions.com or call +1 (555) 123-4567. Our team will send you the registration link. Which event interests you?";
    }
    
    // 9. Pricing
    if (preg_match('/price|cost|pricing|how much|fee|charge|budget|quote/i', $msg)) {
        return "💰 **Pricing Plans**\n\n" .
               "**Startup**: $499/month - Core features, 10k API calls\n" .
               "**Professional**: $1,499/month - Advanced features, 100k API calls\n" .
               "**Enterprise**: Custom pricing - Full suite, unlimited calls\n\n" .
               "All plans include free 14-day trial. Want a custom quote?";
    }
    
    // 10. Demo
    if (preg_match('/demo|see it|live preview|presentation|walkthrough/i', $msg)) {
        return "🎥 **Free Demo Available!**\n\nSchedule a personalized demo:\n📧 Email: info@aisolutions.com\n📞 Call: +1 (555) 123-4567\n\nWhat solution would you like to see?";
    }
    
    // 11. Contact
    if (preg_match('/contact|reach|email|phone|call|get in touch|support/i', $msg)) {
        return "📬 **Contact Us**\n\n📧 Email: info@aisolutions.com\n📞 Phone: +1 (555) 123-4567\n📍 Location: Sunderland, United Kingdom\n\nHours: Mon-Fri, 9 AM - 6 PM GMT";
    }
    
    // 12. Company info
    if (preg_match('/who are you|about company|company background|what do you do|tell me about/i', $msg)) {
        return "🤖 I'm AI-Assistant of AI-Solutions! We provide AI-powered digital workplace solutions from Sunderland, UK. Founded in 2018, our mission is helping businesses improve productivity through smart AI. We offer 6 solutions, 6 training programs, and regular events. What would you like to explore?";
    }
    
    // 13. Case studies / success stories
    if (preg_match('/case study|success|story|client|testimonial|results/i', $msg)) {
        return "📈 **Success Stories**\n\n• Healthcare: 40% reduced wait times\n• Finance: 70% automated inquiries\n• Retail: 25% increased sales\n• Manufacturing: 30% cost reduction\n\nWant specific industry examples?";
    }
    
    // 14. Industries
    if (preg_match('/industry|sector|which industry|healthcare|finance|retail/i', $msg)) {
        return "🏢 **Industries We Serve**\n\nHealthcare, Banking/Finance, Retail, Manufacturing, Education, Technology, Government.\n\nWhich industry are you in?";
    }
    
    // 15. Comparison
    if (preg_match('/better than|compare|competitor|vs|difference|unique/i', $msg)) {
        return "🔍 **What Makes Us Unique**\n\n✓ Customized solutions\n✓ 2-4 week deployment\n✓ Transparent pricing\n✓ UK-based support\n✓ 300% average ROI\n\nWant to compare with others?";
    }
    
    // 16. Technical
    if (preg_match('/technical|api|integration|how does it work|technology|stack/i', $msg)) {
        return "🔧 **Technical**\n\nBuilt on TensorFlow, PyTorch. RESTful APIs. Cloud-native (AWS/Azure/GCP). Enterprise security. 2-3 week integration. Need technical docs?";
    }
    
    // 17. Timeline
    if (preg_match('/how long|timeline|implementation|setup|deploy|when can/i', $msg)) {
        return "⏱️ **Implementation Timeline**\n\n• Planning: 1-2 weeks\n• Development: 2-4 weeks\n• Testing: 1 week\n• Deployment: 1 week\n\nTotal: 4-8 weeks. Need faster? Ask about accelerated program!";
    }
    
    // 18. Support
    if (preg_match('/support|maintenance|help after|update/i', $msg)) {
        return "🛠️ **Support & Maintenance**\n\n• 24/7 critical support\n• Regular updates\n• Dedicated account manager\n• Monthly reviews\n• Team training\n\nSupport plans available for all needs.";
    }
    
    // 19. Thank you
    if (preg_match('/thank|thanks|appreciate|awesome|great/i', $msg)) {
        return "You're welcome! 😊 Happy to help. Anything else you'd like to know about our AI solutions, training, or events?";
    }
    
    // 20. Goodbye
    if (preg_match('/bye|goodbye|see you|farewell|exit|quit/i', $msg)) {
        return "Thank you for visiting AI-Solutions! 👋 Have a great day. Come back anytime for AI assistance!";
    }
    
    // 21. AI/ML questions
    if (preg_match('/what is ai|artificial intelligence|machine learning|explain ai/i', $msg)) {
        return "🤖 **AI Explained**\n\nAI enables machines to learn, reason, and decide. At AI-Solutions, we specialize in Machine Learning, Natural Language Processing, Computer Vision, and Predictive Analytics. Want to see business applications?";
    }
    
    // 22. ROI/Benefits
    if (preg_match('/benefit|advantage|roi|value|why should i|improvement/i', $msg)) {
        return "💎 **Key Benefits**\n\n• 40-60% cost reduction\n• 3x faster decisions\n• 24/7 automation\n• 99% accuracy\n• 300% average ROI\n\nWant ROI calculation for your business?";
    }
    
    // 23. Resources
    if (preg_match('/resource|guide|whitepaper|download|free/i', $msg)) {
        return "📚 **Free Resources**\n\n• AI Readiness Assessment\n• ROI Calculator\n• Implementation Guide\n• Case Study Library\n• AI Strategy Workbook\n\nProvide your email to receive these!";
    }
    
    // 24. About AI-Solutions (specific)
    if (preg_match('/ai-solutions|your company|about you/i', $msg)) {
        return "🏢 **AI-Solutions**\n\nLeading provider of AI-powered digital workplace solutions based in Sunderland, UK. We help businesses improve productivity through intelligent automation and smart decision-making tools since 2018. Our team of 50+ AI experts is ready to help!";
    }
    
    // 25. Partners/Collaborations
    if (preg_match('/partner|collaboration|work with|sunderland software/i', $msg)) {
        return "🤝 **Partners**\n\nWe collaborate with Sunderland Software City, local tech hubs, and industry leaders. Interested in partnership opportunities? Contact our team!";
    }
    
    // Default fallback
    return "🤔 I can help with:\n\n✅ Our AI solutions & services\n✅ Training programs\n✅ Upcoming events & registration\n✅ Pricing & demos\n✅ Company information\n✅ Case studies & industries\n✅ Technical details\n\nWhat would you like to know more about? Just ask!";
}

// Send the response
$response = getAIReply($userMessage);
echo json_encode(['response' => $response]);
?>