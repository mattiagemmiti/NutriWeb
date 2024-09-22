# NutriWeb

NutriWeb is a web application designed to help nutritionists and users manage nutrition plans and track dietary habits efficiently.

## Features:
- User registration and login system.
- Dashboard for nutritionists to manage clients and track progress.
- Forms for users to log and monitor their dietary intake.
- Integration with databases to store user and nutrition data.

## Usage:
- Visit the homepage to either log in or register as a new user.
- Nutritionists can access the dashboard to view client information and manage nutrition plans.
- Users can log in to monitor their dietary habits and view personalized recommendations.

## Technologies Used:
- PHP
- HTML/CSS/JavaScript
- MySQL for database management

# NutriWeb - Local Installation Instructions:

## Prerequisites:
To host and run the "NutriWeb" project locally on your machine, you need to install the **XAMPP** virtual server on a Windows machine.

### Step 1: Download and Install XAMPP
- Download XAMPP from the official website: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html).
- Open the downloaded `.exe` file and follow the installation instructions.
- Select all components during setup to ensure Apache, MySQL, PHP, and other necessary tools are installed.
- When prompted for the installation folder, use the default folder: `C:\xampp`.

### Step 2: Set Up NutriWeb Project
- After installing XAMPP, copy the **"NutriWeb-main"** folder (the project folder) into the following directory: `C:/xampp/htdocs/`.
- If the folder was downloaded from GitHub, unzip it and ensure you paste only the subfolder named "NutriWeb-main", which contains the project files.
- Ensure the folder is named "NutriWeb-main" and the path is C:/xampp/htdocs/NutriWeb-main to match the code references.
- This will allow the project to be hosted on the XAMPP virtual server.

### Step 3: Start Apache and MySQL
- Open the **XAMPP Control Panel** (you can find it in your Windows Start menu or in `C:\xampp\xampp-control.exe`).
- Click **Start** next to **Apache** and **MySQL** to start the web server and the database.

### Step 4: Access the Project
- Open a web browser (Chrome, Firefox, etc.).
- In the address bar, enter the following URL to access the project:
  - `http://localhost/NutriWeb-main`
  
  **OR:**
  - Click on the `open_project.bat` file.
  - Click on the `index.html` file.

## Automatic Database Initialization:
The database is automatically initialized when the application is started. There is no need to manually create the database or tables, as the system will handle it during the first run of the project.
