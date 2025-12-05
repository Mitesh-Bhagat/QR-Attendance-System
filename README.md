[Readme.md](https://github.com/user-attachments/files/23963403/Readme.md)
## QR Code Attendance System (QR-AS)

### 🌟 Project Overview

QR-AS is a modern, security-focused web solution designed to replace traditional paper-based attendance. It streamlines the student check-in process while enforcing physical presence through advanced validation techniques.
### For Complete Project (With Complet Dataset and PHP-Excel): https://mega.nz/file/TVpizBIA#Q4SXBnkHAD-ArRGXuEneQHOnVFxqhy6hD4vkBQUh2_g
---


### ✨ Key Features

* **Multi-Role Dashboard:** Separate portals for **Admin**, **Teacher**, and **Student** with role-based access control.

* **Dynamic UI:** Features a high-contrast **Minimalist Corporate Design** for optimal readability and a professional user experience.

* **Real-Time Monitoring:** Teachers view instant log updates as students scan, ensuring active class supervision.

* **Comprehensive Admin Panel:** Full CRUD (Create, Read, Update, Delete) management for Students, Teachers, Subjects, and the Master Time Table.

---

### 🛡️ Advanced Security Framework

The system prevents proxy attendance through a multi-layered verification process:

* **Geolocation Geofencing:** Uses the **Haversine formula** to calculate and validate the student's distance from the campus GPS coordinates, blocking attendance if they are outside the set radius.

* **Dynamic & Encrypted QR Codes:** QR codes are time-sensitive (expire after a set period) and contain encrypted session data, preventing reuse or sharing.

* **IP/Network Validation:** Automatically checks the user's IP address to **block proxy, VPN usage, and non-local submissions** during the scanning process.

---

### 💻 Technology Stack

| Category          | Technology                | Purpose                                                                      |
| :------------------ | :---------------------- | :--------------------------------------------------------------------------- |
| **Backend Logic** | PHP (Core)                | Server-side processing, session management, and logic execution.              |
| **Database**      | MySQL                     | Secure storage for all user data, schedules, and attendance logs.             |
| **Frontend**      | Bootstrap 5 + Custom CSS  | Responsive layout and high-contrast Minimalist corporate aesthetic.           |
| **Functionality** | jQuery & Instascan.js     | Dynamic DOM manipulation, AJAX communication, and client-side camera access.  |

***

## 🛠️ Final Installation Summary

### 1. Acquire and Setup Database

1.  **Clone the Repository:** Download the project files.
2.  **Create DB:** Create a new MySQL database named `qrattendance`.
3.  **Import Tables:** Import the provided SQL file into the database.

### 2. Configure and Deploy

1.  **Configure `.env`:** Create a `.env` file and add your MySQL connection details.
2.  **Deploy Files:** Place the project folder into your web server's root directory (`htdocs`).

### 3. Access and Documentation

* **App URL:** Access the application via your browser.
* **Admin Credentials:** Username: `admin`, Password: `123`
* **Documentation:** Refer to the **'About project' folder** for the detailed report and explanations.

### 4. Additional Documentation

* **About Project** Check the folder for more detail.
