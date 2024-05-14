<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Collaboration Platform - Contacts</title>
<link rel="stylesheet" href="styles.css">
<style>
/* Additional CSS for the Contacts Page */
.contact-info {
  margin-top: 20px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 5px;
}

.contact-info h2 {
  color: #004080;
}

.contact-info p {
  line-height: 1.6;
}

.contact-form {
  margin-top: 20px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 5px;
}

.contact-form label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}

.contact-form input[type="text"],
.contact-form textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.contact-form input[type="submit"] {
  padding: 10px 20px;
  background-color: #004080;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.contact-form input[type="submit"]:hover {
  background-color: #003366;
}
</style>
</head>
<body>
<div class="container">
  <h1>Contact Us</h1>
  <div class="contact-info">
    <h2>Contact Information</h2>
    <p>Email: blackman@gmail.com</p>
    <p>Phone: +254-715-9124-35</p>
    <p>Address: 123 mwembe tayari Street, mombasa, kenya</p>
  </div>
  <div class="contact-form">
    <h2>Send Us a Message</h2>
    <form action="#" method="post">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" required>
      <label for="message">Message:</label>
      <textarea id="message" name="message" rows="4" required></textarea>
      <input type="submit" value="Submit">
    </form>
  </div>
</div>
<footer>
  <p>&copy; 2024 Student Collaboration Platform. All rights reserved.</p>
</footer>
</body>
</html>
