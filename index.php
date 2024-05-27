<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="assets/img/logo2.png" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: white;
        }
        .logo-image {
            max-width: 15%;
            height: auto;
            position: absolute;
            top: 40px;
            left: 110px;
        }

        .get-started-btn {
            display: inline-block;
            padding: 10px 20px; /* Adjust padding as needed */
            border-radius: 5px; /* Add rounded corners */
            background-color: #ff1493;
            color: #fff; /* Set text color to white */
            font-size: 16px; /* Adjust font size as needed */
            font-weight: bold; /* Make text bold */
            text-align: center;
            text-decoration: none;
            width: 100px; /* Adjust the width as needed */
            transition: background-color 0.3s ease; /* Add smooth transition for hover effect */
        }

        .get-started-btn:hover {
            background-color: #ff69b4; /* Change background color on hover */
            text-decoration: none; /* Remove underline on hover */
        }

        .cover-image {
            flex: 1;
            height: 100vh;
            background: url('assets/img/bgimg.png') no-repeat center center;
            background-size: cover;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            text-align: left;
        }
        h3 {
            font-size: 24px;
            color: #A020F0;
            text-shadow: 1px 1px 2px palevioletred;
        }
        p {
            font-size: 1rem;
            color: #ff1493;
            line-height: 1.5;
            margin: 20px 0;
            padding-right: 1in; /* Add 1-inch space on the right side */
        }

        .get-started-btn {
           
            text-decoration: none;
        }
        .get-started-btn:hover {
            background-color: #ff69b4;
        }
        .error {
            color: red;
            font-size: 0.8rem;
            text-align: left;
            margin-top: -0.5rem;
            margin-bottom: 0.5rem;
        }
        a {
            color: #800080;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .cover-image {
                height: 50vh;
                width: 100%;
            }
            .content {
                height: 50vh;
                width: 100%;
                padding: 20px;
                text-align: center; /* Center the text on small screens */
            }
            .content h3, .content p {
                text-align: center; /* Center the text on small screens */
            }
            .get-started-btn {
                margin-left: auto; /* Center the button on small screens */
                margin-right: auto; /* Center the button on small screens */
            }
        }
        /* Define the animation */
        @keyframes fadeInWords {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Apply the animation to each word */
        h3 span {
            opacity: 0; /* Initially hide all words */
            animation: fadeInWords 2s forwards; /* Apply animation to each word */
        }

        /* Define animation delay for each word */
        h3 span:nth-child(1) { animation-delay: 0s; }
        h3 span:nth-child(2) { animation-delay: 1s; }
        h3 span:nth-child(3) { animation-delay: 2s; }
        h3 span:nth-child(4) { animation-delay: 3s; }
        h3 span:nth-child(5) { animation-delay: 4s; }
        h3 span:nth-child(6) { animation-delay: 5s; }
        h3 span:nth-child(7) { animation-delay: 6s; }
        h3 span:nth-child(8) { animation-delay: 7s; }
        h3 span:nth-child(9) { animation-delay: 8s; }
        h3 span:nth-child(10) { animation-delay: 9s; }
        h3 span:nth-child(11) { animation-delay: 10s; }
        h3 span:nth-child(12) { animation-delay: 11s; }
        h3 span:nth-child(13) { animation-delay: 12s; }
        h3 span:nth-child(14) { animation-delay: 13s; }
        h3 span:nth-child(15) { animation-delay: 14s; }
        h3 span:nth-child(16) { animation-delay: 15s; }
        h3 span:nth-child(17) { animation-delay: 16s; }
        h3 span:nth-child(18) { animation-delay: 17s; }
        h3 span:nth-child(19) { animation-delay: 18s; }
        h3 span:nth-child(20) { animation-delay: 19s; }
    </style>
</head>
<body>
    <img src="assets/img/logo1.png" alt="Logo" class="logo-image">

    <div class="cover-image"></div>

    <div class="content">
        <h3><b>
            <span>Find Your Match:</span>
            <span>Swipe,</span>
            <span>Chat,</span>
            <span>and</span>
            <span>Connect!</span>
        </b>
        </h3>
        <p>Welcome to our dating app, where finding your perfect match is just a swipe away. Engage in exciting conversations, meet new people, and build meaningful connections. Our platform is designed to help you discover like-minded individuals who share your interests and values. Whether you're looking for a serious relationship or just a fun chat, our app provides the perfect space for you to explore and connect. Join us today and start your journey towards finding love and companionship.</p>
        <br><a href="signin.php" class="get-started-btn">Get Started!</a>

    </div>


</body>
</html>
