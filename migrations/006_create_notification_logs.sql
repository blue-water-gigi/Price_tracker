CREATE TABLE notification_logs (
    notification_log_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(user_id),
    product_id INT NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    alert_id INT NOT NULL REFERENCES alerts(alert_id),
    notified_at TIMESTAMP DEFAULT NOW(),
    notification_channels JSONB,
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL
);