# Student Result Management System

A simple and efficient **Student Result Management System** built with **PHP** and **MySQL** that allows admins to upload student results via CSV files and students to view their results online with downloadable PDF reports.

---

## Features

- 🔐 **Admin Authentication**: Secure admin login to manage the system.
- 📤 **Bulk Result Upload**: Admin can upload multiple student results at once using CSV file import.
- 🧾 **Result Viewing**: Students can view their results by entering their Roll or Registration number.
- 📄 **PDF Result Sheet**: Generate and download detailed student result sheets as PDF files with:
  - University header and logo
  - Student details (Name, Roll, Registration, Father's Name)
  - Subject-wise grades table
  - CGPA and credit summary
  - Grading system reference table
  - QR code containing student info for verification
  - Signature and watermark for authenticity
- 📊 **Grading System Display**: Clear grading system chart included in the PDF.
- 💻 **Responsive Design**: Clean and user-friendly interface using Bootstrap.
- 🔄 **Session Management**: Proper login sessions and security for the admin panel.
- 🛠️ **Easy Database Integration**: MySQL backend with simple table structure for student results.
- 🚀 **Lightweight and Fast**: Minimal dependencies, fast loading and generation.

---

## Tech Stack

- PHP (Backend)
- MySQL (Database)
- TCPDF (PDF generation library)
- Bootstrap 4 (Frontend styling)
- HTML / CSS / JavaScript

---

## How to Use

1. Clone the repository.
2. Setup a local server (XAMPP / WAMP / LAMP) or deploy on a PHP supported hosting.
3. Import the `database.sql` file to create the required database and tables.
4. Configure database connection in `db.php`.
5. Login as admin to upload results via CSV.
6. Students can view and download their results from the main page.

---

## CSV File Format

Your CSV should include the following columns in order:


---

## Screenshots

## Screenshots

### Main Page
![Main Page](./Image/Screenshot%202025-06-01%20022255.png)



### Admin Dashboard
![Screenshot 1](./Image/Screenshot%202025-06-01%20022309.png)  

![Screenshot 4](./Image/Screenshot%202025-06-01%20022322.png)  


### PDF Result Sheet
![Screenshot 2](./Image/Screenshot%202025-06-01%20022405.png)  
![Screenshot 3](./Image/Screenshot%202025-06-01%20022337.png)  


---

## License

This project is open source and available under the MIT License.

---

**Made with ❤️ by Md Rifat Hossen**

