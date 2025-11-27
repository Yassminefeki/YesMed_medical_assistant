# YesMed Medical Assistant

YesMed is a web-based medical assistant designed to help users manage their health-related activities. It provides a centralized platform for tracking medications, scheduling appointments, managing medical contacts, and uploading prescriptions. The application also features an AI-powered chatbot to answer medical questions.

## ✨ Features

*   **User Authentication:** Secure user registration and login system.
*   **Profile Management:** Users can upload a profile photo and manage their personal information.
*   **Medication Management:** Add, view, edit, and delete medications from a personal list.
*   **Appointment Scheduling:** A calendar-based system to add, view, and manage medical appointments.
*   **Prescription Uploads:** Users can upload and store images of their medical prescriptions.
*   **Contact Management:** Keep a list of important medical contacts.
*   **AI Medical Assistant:** A chatbot integrated with the Gemini API to provide answers to medical queries.
*   **Reminders:** Set up notifications for medication intake and appointments.

## 🛠️ Technology Stack

*   **Backend:** PHP
*   **Database:** MySQL
*   **Frontend:** HTML, CSS, JavaScript (with AJAX for dynamic content)
*   **AI Integration:** Google Gemini API
*   **Dependencies:** PHPMailer (for email notifications)

## 📜 Project Report

A comprehensive report detailing the project's architecture, database design, interfaces, and various functionalities is available.

[Download the Project Report (PDF)](./report.pdf)


## 🚀 Setup and Installation Guide

Follow these steps to set up the project on your local machine.

### Prerequisites

*   **Web Server:** A local server environment like [XAMPP](https://www.apachefriends.org/index.html) or WAMP.
*   **Database:** MySQL (included with XAMPP/WAMP).
*   **PHP:** Version 7.4 or higher.
*   **Composer:** A dependency manager for PHP. [Install Composer](https://getcomposer.org/download/).
*   **Git:** A version control system. [Install Git](https://git-scm.com/downloads).
*   **Gemini API Key:** An API key from [Google AI Studio](https://aistudio.google.com/app/apikey).

### Installation Steps

1.  **Clone the Repository:**
    Open your terminal or command prompt, navigate to your web server's root directory (e.g., `C:/xampp/htdocs`), and run the following command:
    ```bash
    git clone https://github.com/Yassminefeki/YesMed_medical_assistant.git
    cd YesMed_medical_assistant
    ```

2.  **Install PHP Dependencies:**
    Run Composer to install the required libraries (like PHPMailer).
    ```bash
    composer install
    ```

3.  **Set Up the Database:**
    *   Start your Apache and MySQL services from the XAMPP/WAMP control panel.
    *   Open `phpMyAdmin` by navigating to `http://localhost/phpmyadmin`.
    *   Create a new database named `YesMed_db`.
    *   Select the `YesMed_db` database and go to the "Import" tab.
    *   Click "Choose File" and select the `YesMed_bd.sql` file located in the project's root directory.
    *   Click "Go" to import the database structure and data.

4.  **Configure Environment Variables:**
    *   Rename the `.env.example` file to `.env`. (If `.env.example` does not exist, create a new file named `.env`).
    *   Open the `.env` file and fill in your details:
    ```plaintext
    # --- Database Configuration ---
    DB_HOST=localhost
    DB_NAME=YesMed_db
    DB_USER=root
    DB_PASS=your_database_password # Replace with your XAMPP/WAMP MySQL password (often empty by default)

    # --- API Keys ---
    GEMINI_API_KEY="YOUR_GEMINI_API_KEY_HERE" # Replace with your actual Gemini API key
    ```

5.  **Update Database Connection:**
    Ensure the database credentials in `config/bd.php` match your local setup. The current file is set up to use variables, but you may need to adjust it if you are not using an environment variable loader.

    *Current `config/bd.php` settings:*
    ```php
    $host = 'localhost';
    $db   = 'YesMed_db';
    $user = 'root';
    $pass = 'yesmine123456'; // Make sure this matches your DB password
    ```
    *It's recommended to update this file to use the `.env` variables for better security.*

### Running the Application

Once you have completed the setup, open your web browser and navigate to:
`http://localhost/YesMed_medical_assistant/`

You should see the login page. You can register a new account or use an existing one if the imported database already contains user data.

## 📂 Project Structure

```
.
├── api/          # Handles API requests (AI chat, updates)
├── assets/       # CSS, JavaScript, images, and other static files
├── config/       # Database connection and configuration
├── contacts/     # PHP scripts for contact management
├── includes/     # Reusable components like header and footer
├── medicament/   # Scripts for medication management
├── ordonnances/  # Scripts for prescription management
├── rendezvous/   # Scripts for appointment scheduling
├── vendor/       # Composer dependencies
├── .env          # Environment variables (DB credentials, API keys) - NEVER COMMIT
├── .gitignore    # Specifies files and folders for Git to ignore
├── index.php     # Main entry point of the application
└── login.php     # User login page
```
