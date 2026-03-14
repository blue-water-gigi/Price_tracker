CREATE TYPE alert_type AS ENUM ('percent', 'absolute');
CREATE TABLE alerts (
    alert_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(user_id),
    product_id INT NOT NULL REFERENCES products(product_id),
    type alert_type NOT NULL,
    threshold_value NUMERIC(10, 2),
    notification_channels JSONB,
    is_active BOOLEAN DEFAULT TRUE,
    last_triggered_at TIMESTAMP
);