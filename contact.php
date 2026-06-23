<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Solutions - Contact Us</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">

</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Contact Us Heading - TOP CENTER -->
    <section class="contact-header">
        <div class="container">
            <h1 class="contact-title">Contact Us</h1>
        </div>
    </section>

    <!-- Display Success Message -->
    <?php if (isset($_SESSION['contact_success'])): ?>
        <div class="alert alert-success" style="width: 90%; margin: 20px auto;">
            <?php 
                echo $_SESSION['contact_success'];
                unset($_SESSION['contact_success']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            
            <!-- Left Side: Contact Details & Map -->
            <div class="contact-info">
                <h2 class="info-title">Contact Details</h2>
                <div class="info-details">
                    <div class="info-item">
                        <span class="info-icon">📍</span>
                        <p>Sunderland, United Kingdom</p>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">📞</span>
                        <p>+1 (555) 123-4567</p>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">✉️</span>
                        <p>info@ai-solution.com</p>
                    </div>
                </div>

                <!-- Map -->
                <div class="map-container">
                    <h2 class="info-title">Map</h2>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d270687.0028717076!2d-1.2620173666215613!3d51.96488953945949!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4876513a23136c7b%3A0x9ee6664ffb1f1415!2sAi%20Solutions%20Ltd!5e0!3m2!1sen!2snp!4v1779098199114!5m2!1sen!2snp" width="600" height="450" style="border:0; border-radius: 16px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Right Side: Contact Form - SINGLE COLUMN LAYOUT -->
            <div class="contact-form-wrapper">
                <h2 class="form-title">Contact Form</h2>
                <form id="contactForm" class="contact-form" action="submit_contact.php" method="POST">
                    
                    <div class="form-group">
                        <label for="name">Name: <span class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" 
                            value="<?php echo isset($_SESSION['contact_data']['name']) ? htmlspecialchars($_SESSION['contact_data']['name']) : ''; ?>"
                            required>
                        <?php if (isset($_SESSION['contact_errors']['name'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['name']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address: <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email address" 
                            value="<?php echo isset($_SESSION['contact_data']['email']) ? htmlspecialchars($_SESSION['contact_data']['email']) : ''; ?>"
                            required>
                        <?php if (isset($_SESSION['contact_errors']['email'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number: <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                            value="<?php echo isset($_SESSION['contact_data']['phone']) ? htmlspecialchars($_SESSION['contact_data']['phone']) : ''; ?>"
                            required>
                        <?php if (isset($_SESSION['contact_errors']['phone'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['phone']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Company Name: <span class="required">*</span></label>
                        <input type="text" id="company" name="company" placeholder="Enter your company name"
                            value="<?php echo isset($_SESSION['contact_data']['company']) ? htmlspecialchars($_SESSION['contact_data']['company']) : ''; ?>"
                            required>
                        <?php if (isset($_SESSION['contact_errors']['company'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['company']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="country">Country: <span class="required">*</span></label>
                        <select id="country" name="country" required>
                            <option value="">Select your country</option>
                            <?php
                            $countries = [
                                "Afghanistan", "Albania", "Algeria", "Argentina", "Australia", "Austria",
                                "Bangladesh", "Belgium", "Brazil", "Canada", "China", "Denmark", "Egypt",
                                "Finland", "France", "Germany", "Greece", "India", "Indonesia", "Iran",
                                "Ireland", "Israel", "Italy", "Japan", "Malaysia", "Mexico", "Nepal",
                                "Netherlands", "New Zealand", "Nigeria", "Norway", "Pakistan", "Philippines",
                                "Poland", "Portugal", "Russia", "Saudi Arabia", "Singapore", "South Africa",
                                "South Korea", "Spain", "Sri Lanka", "Sweden", "Switzerland", "Thailand",
                                "Turkey", "United Arab Emirates", "United Kingdom", "United States", "Vietnam"
                            ];
                            foreach ($countries as $country):
                                $selected = (isset($_SESSION['contact_data']['country']) && $_SESSION['contact_data']['country'] == $country) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($_SESSION['contact_errors']['country'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['country']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="job_title">Job Title: <span class="required">*</span></label>
                        <input type="text" id="job_title" name="job_title" placeholder="Enter your job title"
                            value="<?php echo isset($_SESSION['contact_data']['job_title']) ? htmlspecialchars($_SESSION['contact_data']['job_title']) : ''; ?>"
                            required>
                        <?php if (isset($_SESSION['contact_errors']['job_title'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['job_title']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="job_details">Job Details: <span class="required">*</span></label>
                        <textarea id="job_details" name="job_details" rows="4" placeholder="Describe your role and responsibilities..." required><?php echo isset($_SESSION['contact_data']['job_details']) ? htmlspecialchars($_SESSION['contact_data']['job_details']) : ''; ?></textarea>
                        <?php if (isset($_SESSION['contact_errors']['job_details'])): ?>
                            <span class="field-error"><?php echo $_SESSION['contact_errors']['job_details']; ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <?php include 'cta.php'; ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    
    <?php 
    // Clear session data after displaying
    if (isset($_SESSION['contact_errors'])) {
        unset($_SESSION['contact_errors']);
    }
    if (isset($_SESSION['contact_data'])) {
        unset($_SESSION['contact_data']);
    }
    ?>

</body>

</html>