<?php
// Start session if needed
session_start();

// Database Connection
include 'admin/database.php';

// Get the ID and type from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Only allow solution, case_study, article, training
$valid_types = ['solution', 'case_study', 'article', 'training'];
if (!in_array($type, $valid_types) || $id <= 0) {
    header("Location: index.php");
    exit();
}

// DEMO MODE: For specific IDs, use hardcoded content
// Solution ID 38, Case Study ID 5, Article ID 8, Training ID 12
$demo_content = null;

if ($id == 38 && $type == 'solution') {
    // DEMO SOLUTION - AI Virtual Assistant
    $demo_content = [
        'id' => 38,
        'content_type' => 'solution',
        'title' => 'AI Virtual Assistant',
        'description' => 'Our flagship AI-powered virtual assistant responds to user inquiries in real-time, provides instant support, and offers affordable prototyping solutions. Perfect for businesses looking to enhance customer experience.',
        'long_description' => '<p>Our AI Virtual Assistant is a cutting-edge solution designed to transform how businesses interact with their customers. Using advanced natural language processing and machine learning algorithms, this intelligent assistant provides instant, accurate responses to user inquiries 24/7.</p>

<h3>How It Works</h3>
<p>The AI Virtual Assistant integrates seamlessly with your existing website, mobile app, or customer service platform. It learns from every interaction, continuously improving its responses and understanding of your business processes.</p>

<h3>Key Benefits</h3>
<p>Businesses implementing our AI Virtual Assistant typically see a 40-60% reduction in customer service costs while improving response times from hours to seconds. The system handles routine inquiries automatically, allowing your human agents to focus on complex, high-value interactions.</p>

<h3>Implementation Process</h3>
<p>Our team works with you to customize the assistant for your specific industry and use cases. The average implementation takes 2-4 weeks from start to launch, with full training and support provided throughout the process.</p>

<h3>Industries Served</h3>
<p>Retail, healthcare, finance, education, technology, and professional services have all successfully deployed our AI Virtual Assistant to enhance their customer experience.</p>',
        'key_features' => '• Real-time response to customer inquiries
• 24/7 availability with no downtime
• Affordable prototyping and testing options
• Seamless integration with existing systems
• Multi-language support available
• Customizable personality and branding
• Analytics dashboard with insights
• Continuous learning from interactions
• Escalation to human agents when needed
• Omnichannel support (web, mobile, chat, voice)',
        'image' => 'images/1779588387_6a125d2339cca.jpg',
        'tag' => '24/7 Support',
        'category' => 'Featured',
        'publish_date' => null
    ];
} 
elseif ($id == 5 && $type == 'case_study') {
    // DEMO CASE STUDY - NHS Healthcare
    $demo_content = [
        'id' => 5,
        'content_type' => 'case_study',
        'title' => 'NHS Reduces Patient Wait Time by 65% with AI Triage System',
        'description' => 'AI-powered patient intake and symptom checker helped NHS hospitals handle 50,000+ daily inquiries while reducing doctor workload by 40%.',
        'long_description' => '<p>The National Health Service (NHS) faced unprecedented challenges with patient wait times and overburdened medical staff. With over 50,000 daily patient inquiries across multiple hospitals, the system was struggling to provide timely care.</p>

<h3>The Challenge</h3>
<p>Patient wait times averaged 4+ hours in emergency departments, doctors were overwhelmed with routine cases, and administrative staff spent 60% of their time on manual patient intake and triage. The NHS needed a solution that could handle high volumes while ensuring critical cases received immediate attention.</p>

<h3>Our Solution</h3>
<p>We implemented an AI-powered patient triage system with intelligent symptom checking capabilities. The system:</p>
<ul>
<li>Automated initial patient intake and symptom assessment</li>
<li>Prioritized cases based on urgency using machine learning</li>
<li>Provided instant recommendations for self-care or medical attention</li>
<li>Integrated seamlessly with existing NHS electronic health records</li>
<li>Reduced manual data entry by 75%</li>
</ul>

<h3>Implementation Process</h3>
<p>Working closely with NHS clinical staff, we deployed the system across 15 hospitals over 8 weeks. The AI was trained on millions of anonymized patient records to ensure accurate triage decisions.</p>

<h3>Results & Impact</h3>
<p>The transformation was remarkable. Patient wait times dropped dramatically, staff satisfaction improved, and the NHS could serve more patients with existing resources.</p>',
        'key_results' => '• 65% reduction in patient wait times
• 50,000+ daily inquiries handled efficiently
• 40% reduction in doctor workload
• 92% patient satisfaction rate
• £8M annual cost savings
• 75% reduction in administrative tasks',
        'takeaways' => '• AI can dramatically improve healthcare efficiency
• Critical cases receive faster attention
• Staff can focus on complex medical decisions
• Scalable solution works across multiple facilities',
        'image' => 'images/1779531164_6a117d9c6e124.jpg',
        'tag' => 'Healthcare, NHS, Patient Care, AI Triage',
        'category' => 'Healthcare',
        'rating' => null,
        'publish_date' => '2026-05-18'
    ];
}
elseif ($id == 8 && $type == 'article') {
    // DEMO ARTICLE - Generative AI Future of Work
    $demo_content = [
        'id' => 8,
        'content_type' => 'article',
        'title' => 'How Generative AI is Reshaping the Future of Work',
        'description' => 'From automated report generation to creative problem-solving, discover how generative AI tools are boosting productivity across teams.',
        'long_description' => '<p>Generative AI is transforming the workplace at an unprecedented pace. What once required hours of manual work can now be accomplished in minutes, freeing up human talent for higher-value strategic thinking.</p>

<h3>The Rise of Generative AI</h3>
<p>From ChatGPT to specialized industry tools, generative AI has moved from novelty to necessity. In 2026, over 70% of businesses have integrated some form of generative AI into their daily operations.</p>

<h3>Key Applications in the Workplace</h3>
<p><strong>Automated Report Generation:</strong> Teams are using AI to draft reports, analyze data, and create presentations in minutes instead of days.</p>
<p><strong>Creative Problem-Solving:</strong> AI brainstorming tools help teams generate innovative solutions by analyzing patterns across millions of data points.</p>
<p><strong>Code Generation:</strong> Developers are accelerating software development with AI pair programmers that write and debug code.</p>
<p><strong>Content Creation:</strong> Marketing teams produce personalized content at scale, from email campaigns to social media posts.</p>

<h3>Impact on Productivity</h3>
<p>Early adopters report 30-50% productivity gains across key business functions. Routine tasks that consumed 60% of employee time can now be automated, allowing teams to focus on strategic initiatives.</p>

<h3>The Human-AI Partnership</h3>
<p>The most successful organizations view AI as a collaborator, not a replacement. Employees who learn to work alongside AI tools gain significant competitive advantages.</p>

<h3>Skills for the AI-Powered Workplace</h3>
<ul>
<li>Prompt engineering and AI interaction</li>
<li>Critical thinking and result validation</li>
<li>Strategic problem-solving</li>
<li>Ethical AI implementation</li>
<li>Data literacy and interpretation</li>
</ul>

<h3>Looking Ahead</h3>
<p>The future belongs to organizations that embrace AI augmentation. Those who resist risk falling behind as AI capabilities continue to advance exponentially.</p>',
        'key_features' => null,
        'key_results' => null,
        'takeaways' => null,
        'image' => 'images/1779531429_6a117ea5359af.jpg',
        'tag' => 'Generative AI, Future of Work, Productivity, Innovation',
        'category' => 'AI Trends',
        'rating' => null,
        'publish_date' => '2026-05-18'
    ];
}
elseif ($id == 44 && $type == 'training') {
    // DEMO TRAINING - AI for Employee Experience
    $demo_content = [
        'id' => 12,
        'content_type' => 'training',
        'title' => 'AI for Employee Experience',
        'description' => 'Learn how AI can improve daily work life, automate routine tasks, and free up time for creative work. Perfect for all employees.',
        'long_description' => '<h3>Course Overview</h3>
<p>This 3-day training program is designed to help employees at all levels understand and leverage AI in their daily work. No technical background required!</p>

<h3>Course Curriculum</h3>

<p><strong>Day 1: AI Foundation</strong><br>
• What is AI? Understanding AI, ML, and Generative AI<br>
• How large language models work<br>
• Setting up your AI toolkit<br>
• Hands-on: First interactions with AI assistants<br>
• Understanding prompt engineering basics</p>

<p><strong>Day 2: Practical AI Tools</strong><br>
• Deep dive into ChatGPT, Claude, and Gemini<br>
• AI for writing: Emails, reports, and documentation<br>
• AI for research and data analysis<br>
• AI for presentations and content creation<br>
• Best practices for prompt engineering</p>

<p><strong>Day 3: Automation & Best Practices</strong><br>
• Automating repetitive tasks with AI<br>
• Integrating AI with Microsoft 365 and Google Workspace<br>
• Data privacy and security when using AI<br>
• Understanding AI biases and limitations<br>
• Building your personal AI strategy</p>

<h3>What You Will Learn</h3>
<ul>
<li>Use AI tools confidently for daily work tasks</li>
<li>Reduce time spent on routine work by 40-60%</li>
<li>Create content, reports, and presentations faster</li>
<li>Automate repetitive workflows</li>
<li>Understand AI ethics and responsible use</li>
</ul>

<h3>Who Should Attend</h3>
<p>All employees regardless of technical background - Operations, HR, Marketing, Finance, Customer Service, and Administration.</p>

<h3>Training Details</h3>
<ul>
<li>Duration: 3 Days (9:00 AM - 5:00 PM)</li>
<li>Certificate: Included upon completion</li>
<li>Materials: Workbook, guides, and templates provided</li>
<li>Support: Email support for 30 days post-training</li>
</ul>

<h3>Investment</h3>
<p><strong>Standard:</strong> £1,495 | <strong>Early Bird:</strong> £1,195 | <strong>Group (5+):</strong> £1,095 per person</p>

<h3>Upcoming Dates</h3>
<ul>
<li>June 15-17, 2026 (London)</li>
<li>July 6-8, 2026 (Manchester)</li>
<li>July 20-22, 2026 (Virtual)</li>
</ul>

<h3>FAQs</h3>
<p><strong>Do I need technical skills?</strong> No, this is for beginners.<br>
<strong>What tools will we use?</strong> ChatGPT, Claude, Gemini, Microsoft Copilot.<br>
<strong>Can this be customized for my organization?</strong> Yes, private corporate training available.</p>',
        'key_features' => null,
        'key_results' => null,
        'takeaways' => null,
        'image' => 'images/1779589551_6a1261afdef5b.jpg',
        'tag' => '3 Days',
        'category' => 'Beginner',
        'rating' => null,
        'publish_date' => '2026-06-10'
    ];
}
else {
    // For non-demo IDs, fetch from database normally
    $stmt = $connection->prepare("SELECT * FROM content_items WHERE id = ? AND content_type = ?");
    $stmt->bind_param("is", $id, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
    
    if (!$content) {
        header("Location: index.php");
        exit();
    }
}

// Use demo content if available, otherwise use database content
if (isset($demo_content) && $demo_content) {
    $content = $demo_content;
}

// Set page title based on type
$type_titles = [
    'solution' => 'Solution Details',
    'case_study' => 'Case Study',
    'article' => 'Article',
    'training' => 'Training Details'
];
$page_title = $type_titles[$type] ?? 'Details';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars(substr($content['description'] ?? '', 0, 160)); ?>">

    <title><?php echo htmlspecialchars($content['title']); ?> | AI Solutions</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/details.css">

</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Back Button -->
    <div class="back-button" style="max-width: 1000px; margin: 20px auto 0; padding: 0 20px;">
        <a href="javascript:history.back()" class="back-link">← Back</a>
    </div>

    <!-- Details Section -->
    <section class="details-section">
        <div class="container">
            
            <!-- Content Card -->
            <div class="details-card">
                
                <!-- Header with Category Badge -->
                <div class="details-header">
                    <div class="category-badge"><?php echo ucfirst(str_replace('_', ' ', $type)); ?></div>
                    <h1 class="details-title"><?php echo htmlspecialchars($content['title']); ?></h1>
                    
                    <?php if ($type == 'article' && !empty($content['publish_date'])): ?>
                        <div class="details-meta">
                            <span class="meta-date">📅 Published: <?php echo date('F d, Y', strtotime($content['publish_date'])); ?></span>
                        </div>
                    <?php elseif ($type == 'solution' && !empty($content['tag'])): ?>
                        <div class="details-meta">
                            <span class="meta-tag">🏷️ <?php echo htmlspecialchars($content['tag']); ?></span>
                            <?php if (!empty($content['rating'])): ?>
                                <span class="meta-rating">⭐ Rating: <?php echo str_repeat('★', $content['rating']) . str_repeat('☆', 5 - $content['rating']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($type == 'case_study' && !empty($content['category'])): ?>
                        <div class="details-meta">
                            <span class="meta-category">📂 Category: <?php echo ucfirst($content['category']); ?></span>
                            <?php if (!empty($content['tag'])): ?>
                                <span class="meta-tag">🏷️ <?php echo htmlspecialchars($content['tag']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($type == 'training' && !empty($content['category'])): ?>
                        <div class="details-meta">
                            <span class="meta-category">📚 Level: <?php echo ucfirst($content['category']); ?></span>
                            <?php if (!empty($content['tag'])): ?>
                                <span class="meta-duration">⏱️ Duration: <?php echo htmlspecialchars($content['tag']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Featured Image - Fixed with onerror fallback -->
                <?php if (!empty($content['image'])): ?>
                    <div class="details-image">
                        <img src="<?php echo htmlspecialchars($content['image']); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>" onerror="this.src='images/placeholder.jpg'">
                    </div>
                <?php else: ?>
                    <div class="details-image">
                        <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($content['title']); ?>">
                    </div>
                <?php endif; ?>

                <!-- Main Content Body -->
                <div class="details-content">
                    <?php 
                    // Use long_description if available, otherwise use description
                    $main_content = !empty($content['long_description']) ? $content['long_description'] : $content['description'];
                    
                    // Check if content contains HTML tags
                    if (strip_tags($main_content) != $main_content) {
                        // Contains HTML, output as is (already safe from database)
                        echo $main_content;
                    } else {
                        // Plain text, convert line breaks and escape HTML
                        echo nl2br(htmlspecialchars($main_content));
                    }
                    ?>
                </div>

                <!-- Key Features for Solutions -->
                <?php if ($type == 'solution' && !empty($content['key_features'])): ?>
                    <div class="details-info-box">
                        <h3>Key Features</h3>
                        <?php 
                        // Display features as bullet points
                        $features = explode("\n", trim($content['key_features']));
                        echo '<ul>';
                        foreach ($features as $feature):
                            $feature = trim($feature);
                            if (!empty($feature)):
                                // Remove bullet points if they exist
                                $feature = ltrim($feature, '•-* ');
                                echo '<li>✅ ' . htmlspecialchars($feature) . '</li>';
                            endif;
                        endforeach;
                        echo '</ul>';
                        ?>
                        
                        <div class="action-buttons">
                            <a href="contact.php?solution=<?php echo urlencode($content['title']); ?>" class="btn-primary">Request Demo →</a>
                            <a href="contact.php" class="btn-secondary">Contact Sales →</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Key Results for Case Studies -->
                <?php if ($type == 'case_study' && !empty($content['key_results'])): ?>
                    <div class="details-info-box">
                        <h3>Key Results</h3>
                        <?php 
                        $results = explode("\n", trim($content['key_results']));
                        echo '<ul>';
                        foreach ($results as $result):
                            $result = trim($result);
                            if (!empty($result)):
                                $result = ltrim($result, '•-* ');
                                echo '<li>📈 ' . htmlspecialchars($result) . '</li>';
                            endif;
                        endforeach;
                        echo '</ul>';
                        ?>
                        
                        <?php if (!empty($content['takeaways'])): ?>
                            <h3>Key Takeaways</h3>
                            <?php 
                            $takeaways = explode("\n", trim($content['takeaways']));
                            echo '<ul>';
                            foreach ($takeaways as $takeaway):
                                $takeaway = trim($takeaway);
                                if (!empty($takeaway)):
                                    $takeaway = ltrim($takeaway, '•-* ');
                                    echo '<li>💡 ' . htmlspecialchars($takeaway) . '</li>';
                                endif;
                            endforeach;
                            echo '</ul>';
                            ?>
                        <?php endif; ?>
                        
                        <div class="action-buttons">
                            <a href="contact.php?case=<?php echo urlencode($content['title']); ?>" class="btn-primary">Get Similar Solution →</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Training Details Box -->
                <?php if ($type == 'training'): ?>
                    <div class="details-info-box">
                        <h3>Quick Overview</h3>
                        <ul>
                            <li>📚 <strong>Level:</strong> <?php echo htmlspecialchars($content['category'] ?? 'Beginner'); ?></li>
                            <li>⏱️ <strong>Duration:</strong> <?php echo htmlspecialchars($content['tag'] ?? '3 Days'); ?></li>
                            <?php if (!empty($content['publish_date'])): ?>
                                <li>📅 <strong>Available From:</strong> <?php echo date('F d, Y', strtotime($content['publish_date'])); ?></li>
                            <?php endif; ?>
                            <li>🏆 <strong>Certificate:</strong> Included upon completion</li>
                            <li>💻 <strong>Format:</strong> In-person / Online / Hybrid</li>
                            <li>👥 <strong>Max Participants:</strong> 20 (personalized attention)</li>
                        </ul>
                        
                        <div class="action-buttons">
                            <a href="contact.php?training=<?php echo urlencode($content['title']); ?>" class="btn-primary">Register for Training →</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Article Footer -->
                <?php if ($type == 'article' && !empty($content['long_description'])): ?>
                    <div class="article-footer">
                        <?php if (!empty($content['category'])): ?>
                            <div class="article-category">
                                <strong>Category:</strong> <?php echo htmlspecialchars($content['category']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($content['tag'])): ?>
                            <div class="article-tags">
                                <strong>Tags:</strong>
                                <?php 
                                $tags = explode(',', $content['tag']);
                                foreach ($tags as $tag): 
                                ?>
                                    <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <?php include 'cta.php'; ?>
    
    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>

</body>

</html>