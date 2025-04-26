CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL, -- Matching user_id type from users table
    date DATE NOT NULL,
    health DOUBLE DEFAULT 0,
    study DOUBLE DEFAULT 0,
    grocery DOUBLE DEFAULT 0,
    clothing DOUBLE DEFAULT 0,
    housing DOUBLE DEFAULT 0,
    others DOUBLE DEFAULT 0,
    total DOUBLE DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_date_user (user_id, date) -- Prevent duplicate entries per user per day
);
