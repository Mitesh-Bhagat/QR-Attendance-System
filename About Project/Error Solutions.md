## Bulk Entry Solution
If the Excel file upload (**Bulk Entry**) fails with a "Class 'ZipArchive' not found" error, it means the necessary PHP extension is disabled. Follow these steps to enable it:

-----

### **1. Locate the `php.ini` Configuration File**

  * **XAMPP Control Panel:** Click the **Config** button next to the Apache module and select **PHP (php.ini)**.
  * *Alternatively, locate the file directly at the default path:* `C:\xampp\php\php.ini`.

-----

### **2. Enable the Zip Extension**

  * Open the `php.ini` file and press `Ctrl + F` to search.
  * Search for the following line:
    ```ini
    ;extension=zip
    ```
  * **Remove the semicolon (`;`)** at the beginning of the line to activate the module.
    ```ini
    extension=zip
    ```

-----

### **3. Save Changes and Restart Server**

  * **Save** the modified `php.ini` file.
  * Go back to the XAMPP Control Panel and click **Stop** then **Start** on the **Apache** module to load the new configuration.