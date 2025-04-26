SELECT date, health, study, grocery, clothing, housing, others, total 
FROM expenses 
WHERE user_id = ? AND date >= CURDATE() - INTERVAL ? DAY
ORDER BY date DESC;
