CREATE TABLE tbl_weekly_time_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    week_start_date DATE NOT NULL,
    monday_time FLOAT DEFAULT 0,
    tuesday_time FLOAT DEFAULT 0,
    wednesday_time FLOAT DEFAULT 0,
    thursday_time FLOAT DEFAULT 0,
    friday_time FLOAT DEFAULT 0,
    student_fullname VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    section VARCHAR(50) NOT NULL,
    date_created DATETIME DEFAULT CURRENT_TIMESTAMP
);