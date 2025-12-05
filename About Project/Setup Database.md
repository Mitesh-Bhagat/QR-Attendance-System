## ⚙️ Final Installation Steps

This procedure assumes you have already installed XAMPP and started the Apache and MySQL services.

### 1\. Acquire and Setup Database

1.  **Create DB:** Create a new MySQL database Code `CREATE DATABASE qrattendance;`. 
2.  **Import Data:** Import the provided SQL file (located in the `Database/qrattendance.sql` directory) into the new database to set up tables.

-----

### 2\. Configure and Deploy

1.  **Configure `.env`:** Create a `.env` file in the project's root directory and add your MySQL connection details, including `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME`.
2.  **Deploy Files:** Place the project folder into your web server's root directory (e.g., `htdocs`).
3.  **Access App:** Access the application in your browser at the local server path.

-----

### 3\. Initial Login

  * **Admin Username:** `admin`
  * **Admin Password:** `123`