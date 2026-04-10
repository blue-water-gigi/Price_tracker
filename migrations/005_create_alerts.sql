CREATE TYPE alert_type AS ENUM ('percent', 'absolute');
CREATE TABLE alerts (
    alert_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(user_id),
    product_id INT NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    type alert_type NOT NULL,
    threshold_value NUMERIC(10, 2) CHECK (threshold_value >= 0),
    notification_channel VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    last_triggered_at TIMESTAMPTZ,
    target_price NUMERIC(10, 2) CHECK (target_price >= 0),
    check_interval INTERVAL CHECK (
        check_interval >= INTERVAL '30 minutes'
        AND check_interval <= INTERVAL '24 hours'
    ),
    last_checked_at TIMESTAMPTZ,
    CONSTRAINT unique_user_product_alert UNIQUE(user_id, product_id)
);