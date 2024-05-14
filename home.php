<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Collaboration Platform</title>
<link rel="stylesheet" href="styles.css">
<style>
/* Additional CSS for the Home Page */
body {
  background-color: #f0f8ff; /* Light blue background color */
}

.hero {
  background-image: url('background.jpg'); /* Background image for the hero section */
  background-size: cover;
  background-position: center;
  color: white;
  text-align: center;
  padding: 100px 0;
}

.hero h1 {
  font-size: 3rem;
  margin-bottom: 20px;
}

.hero p {
  font-size: 1.2rem;
  margin-bottom: 40px;
}

.features {
  display: flex;
  justify-content: space-around;
  margin-top: 50px;
}

.feature {
  text-align: center;
  width: 30%;
  padding: 20px;
  background-color: #fff; /* White background color for feature boxes */
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.feature i {
  font-size: 2rem;
  margin-bottom: 10px;
  color: #007bff; /* Blue color for icons */
}

.feature h3 {
  font-size: 1.5rem;
  margin-bottom: 10px;
  color: #333; /* Dark text color for headings */
}

.feature p {
  font-size: 1rem;
  color: #666; /* Medium text color for paragraphs */
}

.cta {
  text-align: center;
  margin-top: 50px;
}

.cta a {
  display: inline-block;
  padding: 10px 20px;
  background-color: #007bff; /* Blue color for button */
  color: white;
  text-decoration: none;
  border-radius: 5px;
}

.cta a:hover {
  background-color: #0056b3; /* Darker blue color on hover */
}
</style>
</head>
<body>
<div class="hero">
  <h1>Welcome to the Student Collaboration Platform</h1>
  <p>Collaborate with your peers, join courses, work on projects, and manage your groups.</p>
</div>
<div class="features">
  <div class="feature">
    <i class="fas fa-users"></i>
    <h3>Collaborate</h3>
    <p>Work together with other students on projects, assignments, and group activities.</p>
  </div>
  <div class="feature">
    <i class="fas fa-chalkboard-teacher"></i>
    <h3>Join Courses</h3>
    <p>Enroll in courses to expand your knowledge and skills in various subjects.</p>
  </div>
  <div class="feature">
    <i class="fas fa-project-diagram"></i>
    <h3>Manage Projects</h3>
    <p>Create and manage projects, track progress, and collaborate with your team.</p>
  </div>
</div>
<div class="cta">
  <a href="signup.php">Get Started</a>
</div>
<footer>
  <p>&copy; 2024 Student Collaboration Platform. All rights reserved.</p>
</footer>
</body>
</html>
