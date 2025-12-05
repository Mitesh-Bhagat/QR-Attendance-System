## Folder Details
**`CSV for Browser`**
    CSV file contains usernames and passwords and is imported in the browser, so teachers and students don't need to type their IDs and passwords (9 teachers, 50 students).

**`Excel File`**
    Add 50 Student in bulk.

### Rest you can understand by the name itself.

## Files Details
Two-line description for each file in the project:

### UI & Layout Files

1.  **`header.php`**
    Initializes the HTML structure, session, and navigation bar; contains logic to dynamically fetch the user's profile picture and controls the sidebar collapse function.
2.  **`footer.php`**
    Closes the main HTML tags, includes necessary JavaScript libraries, and renders the sticky footer and "Back to Top" functionality.
3.  **`index.php`**
    Serves as the main dashboard for all user roles, displaying a welcome message and user-specific profile details upon successful login.
4.  **`login.php`**
    Presents the multi-role login form (Admin, Teacher, Student) and processes credentials against the database to start the user session.
5.  **`logout.php`**
    Immediately terminates the current user session by destroying all session variables and redirects the user back to the login page.
6.  **`settings.php`**
    Admin view for displaying the current campus location configuration, including GPS coordinates and the allowed geofence radius.
7.  **`profile_stud.php`**
    Student-specific page for viewing and updating their profile details, including the ability to upload a new profile picture.

### Attendance & Workflow Views

8.  **`give_attend.php`**
    Student interface that initializes the camera and geolocation services, preparing the student's device for QR code scanning.
9.  **`take_attendance.php`**
    Teacher interface used to filter and select the current class (Semester/Batch) before proceeding to dynamic QR code generation.
10. **`take_attend.php`**
    Teacher interface that displays the dynamic, time-sensitive QR code for students to scan and initiates the real-time attendance logging system.
11. **`manual_attend.php`**
    Admin interface used to select class criteria (Timetable view) for manually marking attendance when QR scanning is not possible.
12. **`give_manual_attend.php`**
    Form used by the Admin to manually submit attendance records for specific students, including inputs for date, time, and IP address.

### Data Management (CRUD Views)

13. **`stud_details.php`**
    Admin panel for viewing, adding (single/bulk), editing, and deleting student records in the master database.
14. **`teacher_details.php`**
    Admin panel for viewing, adding, editing, and deleting teacher records, including credentials and academic affiliations.
15. **`subject_details.php`**
    Admin panel for managing subjects, including assigning codes, descriptions, and mapping subjects to specific teachers.
16. **`timetable.php`**
    Admin interface used to set up or delete the weekly class schedule matrix based on branch, semester, batch, and academic year.
17. **`allocation.php`**
    Admin reporting tool that dynamically maps and lists all students assigned to each specific teacher based on subject and semester linkages.
18. **`settings_update.php`** (Likely `api_settings_update.php` was intended)
    Frontend form displayed to the Admin for modifying the system's global configuration parameters, such as campus GPS coordinates and coverage radius.
19. **`view_admin_attend.php`**
    Admin reporting view to display the complete attendance history for all classes, often including powerful filtering options.
20. **`view_stud_attend.php`**
    Student reporting view that fetches and displays the attendance history only for the currently logged-in student.
21. **`view_teacher_attend.php`**
    Teacher reporting view that allows filtering and viewing the attendance records specifically for the classes they teach.

### API & Backend Utilities

22. **`conn.php`**
    Contains the crucial PHP logic to safely parse environment variables from the `.env` file and establish the database connection to MySQL.
23. **`api_give_attend.php`**
    Core API endpoint that validates the student's scan data, verifies geolocation and IP integrity, and commits the attendance record to the database.
24. **`api_manual_attend.php`**
    Backend processing script that receives submitted manual attendance data from the Admin form and inserts multiple records into the attendance table.
25. **`api_noti.php`**
    AJAX endpoint used by the Teacher dashboard to fetch, display, and delete real-time attendance notifications in the log window.
26. **`api_self_stud_update.php`**
    Handles the submission of the student's profile updates and manages the secure file upload of the profile picture to the server.
27. **`api_settings.php`**
    Backend script that processes updates to the system's global configuration parameters (e.g., location, radius) submitted via the settings update form.
28. **`api_stud.php`**
    Backend script managing CRUD operations for student records, including processing new student entries and bulk import functionality via Excel files.
29. **`api_stud_update.php`**
    View file displaying the pre-populated form used by the Admin to modify the details of an existing student record.
30. **`api_subject.php`**
    Backend script managing the CRUD operations for subject records, including linking the subject code to a specific teaching faculty.
31. **`api_subject_update.php`**
    View file displaying the pre-populated form used by the Admin to modify the details of an existing subject record.
32. **`api_teacher.php`**
    Backend script managing the CRUD operations for teacher records, handling the addition, deletion, and modification of faculty profiles.
33. **`api_teacher_update.php`**
    View file displaying the pre-populated form used by the Admin to modify the details of an existing teacher record.
34. **`api_timetable.php`**
    Backend script used for setting up or deleting large batches of records in the `timetable` database table based on semester and batch criteria.
35. **`Project Report.txt`**
    A comprehensive document detailing the system architecture, security mechanisms, module structure, and technology stack of the entire project.