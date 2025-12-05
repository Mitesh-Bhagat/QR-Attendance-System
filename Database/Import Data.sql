-- 1. Create Default Admin (Password: 123)
-- Note: This hash is for '123'.
INSERT INTO `users` (`username`, `password`, `user_type`, `status`) VALUES
('admin', '$2y$10$m62rGFcy.z6I0xvDBp4qSu88SQatgUSTGr6UpuD9wxrOefLoMcRQ.', 'ADMIN', 1);
-- (If login fails, use the 'register' page or generate a new hash for '123')

-- 2. System Settings (GPS: Rajkot)
INSERT INTO `settings` (`id`, `location`, `lat`, `lon`, `covarage`) VALUES
(1, 'Campus', '22.3039', '70.8022', '5.0'); 

-- 3. Create 1 Teacher (Password: 123)
INSERT INTO `teachers` (`id`, `name`, `branch`, `password`, `education`, `designation`) VALUES
(1, 'Test Teacher', 'Computer', '123', 'M.Tech', 'Professor');

-- 4. Create 1 Student (Password: 123)
INSERT INTO `students` (`enrollment_no`, `name`, `branch`, `semester`, `batch`, `password`, `roll_no`) VALUES
(101, 'Test Student', 'Computer', 1, 'A1', '123', 1);

-- 5. Create 1 Subject (Mapped to Test Teacher)
INSERT INTO `subjects` (`subject_code`, `name`, `branch`, `semester`, `teacher_id`, `abbreviation`) VALUES
(1001, 'Test Subject', 'Computer', 1, 1, 'TS');

-- 6. Create Time Table (For Today - Friday)
INSERT INTO `timetable` (`branch`, `semester`, `batch`, `day`, `slot`, `slotlabel`, `subject_code`, `academic_year`) VALUES
('Computer', 1, 'A1', 'Friday', 1, '10:00 - 11:00', 1001, '2024-25'),
('Computer', 1, 'A1', 'Saturday', 1, '10:00 - 11:00', 1001, '2024-25');