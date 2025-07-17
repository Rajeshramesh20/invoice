<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Billing Management</title>
  <style>
    *{
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }
    body {
      font-family: Arial;
      background-color: #f4f4f4;
    }

    .wrapper {
      max-width: 1500px;
      margin: 0 auto;
      /* padding: 20px; */
    }

    header {
      padding: 10px 0;
      /* background-color: navy; */
    }

    .twigikImage {
      margin-left: 10px;
      max-width: 250px;
    }

    .container {
      position: relative;
      background-image: url("images/invoice.jpg");
      background-repeat: no-repeat;
      background-size: contain;
      background-position: center center;
      height: 500px;
      border-radius: 8px;
    }

    .container .content {
      position: absolute;
      left: 50px;
      top: 50%;
      transform: translateY(-50%);
      color: #fff;
      padding: 20px 25px;
      border-radius: 8px;
      max-width: 500px;
    }

    .container .content h1 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    .container .content p {
      margin-bottom: 20px;
    }

    .container .content ul {
      padding-left: 20px;
      margin-bottom: 40px;
    }

    .container .content ul li {
      margin-bottom: 15px;
    }

    .loginPage {
        background-color: #b50436;
        padding: 12px;
        text-align: center;
        color: white;
        text-decoration: none;
        border-radius: 25px;
    }

    .footer {
      background-color: #222;
      color: #ccc;
      padding: 40px 20px;
      text-align: center;
      border-radius: 8px;
      /* margin-top: 40px; */
    }

    .footer-title {
      font-size: 18px;
      margin-bottom: 10px;
    }

    .footer p {
      margin-bottom: 8px;
    }

    .footer-divider {
      margin: 30px 0;
      border: none;
      border-top: 1px solid #444;
    }

    .footer-copy {
          font-size: 14px;
          color: #888;
        }
    
  </style>
</head>
<body>



  <div class="wrapper">
    <header>
      <img src="images/twigik.png" class="twigikImage" alt="Twigik Logo">
    </header>
    <div class="container">
      <div class="content">
        <h1>Billing Management Software</h1>
        <p>Simplify billing and supercharge productivity with our DR Infosoft’s billing management software. Why us?</p>
        <ul>
          <li>Improved operational efficiency and customer experience.</li>
          <li>Increased revenue and profitability.</li>
          <li>Reduced errors and discrepancies in billing and inventory.</li>
        </ul>
        <a href="/api/login" class="loginPage">Get Started Today!</a>
      </div>
    </div>

    <footer class="footer">
            <div class="footer-content">
                  <p class="footer-title"><strong>Twigik Technologies Pvt. Ltd.</strong></p>
                  <p>Empowering businesses with intelligent billing and inventory solutions.</p>
                  <p> 🔎Location:Plot No 69, 3rd Floor, 11th Cross Street,
                    Sai Ganesh Nagar, Pallikaranai,
                    Chennai - 600100 </p>
                  <p>📞 Phone: +91-6383707076</p>
                  <p>✉️ Email:info@twigik.com</p>
                  <hr class="footer-divider">
                  <p class="footer-copy">&copy; 2025 Twigik. All rights reserved.</p>
             </div>
    </footer>
  </div>
</body>
</html>

{{-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Billing Management</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f4f4f4;
    }

    .twigikImage {
      max-width: 250px;
    }

    .hero-container {
      position: relative;
      background-image: url("images/invoice.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center center;
      height: 500px;
      border-radius: 8px;
    }

    .hero-content {
      position: absolute;
      top: 50%;
      left: 50px;
      transform: translateY(-50%);
      color: white;
      background-color: rgba(0, 0, 0, 0.4); /* slight overlay for readability */
      padding: 30px;
      border-radius: 8px;
      max-width: 500px;
    }

    .loginPage {
      background-color: #b50436;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      border-radius: 25px;
      display: inline-block;
      font-weight: 500;
    }

    .footer {
      background-color: #222;
      color: #ccc;
      padding: 40px 20px;
      text-align: center;
      border-radius: 8px;
    }

    .footer-divider {
      border-top: 1px solid #444;
    }

    .footer-copy {
      font-size: 14px;
      color: #888;
    }
  </style>
</head>
<body>

  <!-- Wrapper Container -->
  <div class="container-fluid py-4 px-0">

    <!-- Header -->
    <header class="pb-4">
      <div class="d-flex justify-content-start">
        <img src="images/twigik.png" class="twigikImage" alt="Twigik Logo">
      </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-container rounded-0">
      <div class="hero-content">
        <h1 class="mb-3">Billing Management Software</h1>
        <p>Simplify billing and supercharge productivity with our DR Infosoft’s billing management software. Why us?</p>
        <ul class="mb-4">
          <li>Improved operational efficiency and customer experience.</li>
          <li>Increased revenue and profitability.</li>
          <li>Reduced errors and discrepancies in billing and inventory.</li>
        </ul>
        <a href="/api/login" class="loginPage">Get Started Today!</a>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer rounded-0">
      <div class="footer-content">
        <p class="fw-bold h5 mb-2">Twigik Technologies Pvt. Ltd.</p>
        <p>Empowering businesses with intelligent billing and inventory solutions.</p>
        <p>🔎 <strong>Location:</strong> Plot No 69, 3rd Floor, 11th Cross Street,<br>
          Sai Ganesh Nagar, Pallikaranai, Chennai - 600100</p>
        <p>📞 <strong>Phone:</strong> +91-6383707076</p>
        <p>✉️ <strong>Email:</strong> info@twigik.com</p>
        <hr class="footer-divider my-4">
        <p class="footer-copy">© 2025 Twigik. All rights reserved.</p>
      </div>
    </footer>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 --}}
