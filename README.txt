STUDENT REGISTRATION WEBSITE - PHP + MySQL (XAMPP)

SETUP
1. Extract the StudentRegistration_PHP folder into C:\xampp\htdocs\
   The final path should be C:\xampp\htdocs\StudentRegistration_PHP\
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin/
4. Select the SQL tab and run the full contents of database.sql.
5. Open http://localhost/StudentRegistration_PHP/signup.html
6. Create an account, then log in.

IMPORTANT
- Keep all files in this same folder. Do not put the HTML pages in a separate public folder.
- Use the localhost URL above, not Live Server and not file://.
- This is a college practical/demo project. The forgot-password page directly resets the password after checking the email; a real website must verify ownership through a secure email token.
- Uploaded files are stored in the uploads folder. Only logged-in users can upload.
