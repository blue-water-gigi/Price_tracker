CREATE TYPE alert_type AS ENUM ('percent', 'absolute');
CREATE TABLE alerts (
    alert_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(user_id),
    product_id INT NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    type alert_type NOT NULL,
    threshold_value NUMERIC(10, 2),
    notification_channel VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    last_triggered_at TIMESTAMP,
    target_price NUMERIC(10, 2),
    check_interval INTERVAL,
    last_checked_at TIMESTAMP,
    CONSTRAINT unique_user_product_alert UNIQUE(user_id, product_id)
);