<!-- header.php -->

<header class="header">

    <div class="container">

        <!-- Logo -->
        <div class="logo">

            <a href="index.php">

                <img src="images/logo.png" alt="Logo">

            </a>

        </div>

        <!-- Mobile Menu Button -->
        <div class="menu-toggle" id="menu-toggle">
            ☰
        </div>

        <!-- Navigation -->
        <nav class="navbar" id="navbar">

            <ul class="nav-links">

                <li>
                    <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                       href="index.php">
                        Home
                    </a>
                </li>

                <li>
                    <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'solutions.php' ? 'active' : ''; ?>"
                       href="solutions.php">
                        Solutions
                    </a>
                </li>

                <li>
                    <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'insights.php' ? 'active' : ''; ?>"
                       href="insights.php">
                        Insights
                    </a>
                </li>

                <li>
                    <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'testimonials.php' ? 'active' : ''; ?>"
                       href="testimonials.php">
                        Testimonials
                    </a>
                </li>

                <li>
                    <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>"
                       href="gallery.php">
                        Gallery
                    </a>
                </li>

                <li>
                    <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>"
                       href="contact.php">
                        Contact Us
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</header>

<style>

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body{
        overflow-x: hidden;
    }

    /* Header */

    .header{
        width: 100%;
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .container{
        width: 90%;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    /* Logo */

    .logo img{
        height: 80px;
        width: auto;
        display: block;
    }

    /* Navigation */

    .navbar .nav-links{
        display: flex;
        align-items: center;
        gap: 25px;
        list-style: none;
    }

    .navbar .nav-links li a{
        text-decoration: none;
        color: #333;
        font-size: 16px;
        font-weight: 500;
        transition: 0.3s;
        padding-bottom: 5px;
    }

    .navbar .nav-links li a:hover{
        color: #007bff;
    }

    .navbar .nav-links li a.active{
        color: #007bff;
        border-bottom: 2px solid #007bff;
    }

    /* Mobile Menu */

    .menu-toggle{
        display: none;
        font-size: 28px;
        cursor: pointer;
        color: #333;
    }

    /* Responsive */

    @media(max-width: 768px){

        .menu-toggle{
            display: block;
        }

        .navbar{
            position: absolute;
            top: 75px;
            left: 0;
            width: 100%;
            background-color: white;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .navbar.active{
            display: block;
        }

        .navbar .nav-links{
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            gap: 18px;
        }

    }

</style>

<script>

    const menuToggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');

    menuToggle.addEventListener('click', () => {

        navbar.classList.toggle('active');

    });

</script>