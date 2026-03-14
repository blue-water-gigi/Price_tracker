CREATE TABLE price_history (
    price_history_id SERIAL PRIMARY KEY,
    price NUMERIC(10, 2) NOT NULL,
    checked_at TIMESTAMP NOT NULL DEFAULT NOW(),
    product_id INT NOT NULL REFERENCES products(product_id)
);
CREATE INDEX idx_price_history_product ON price_history(product_id, checked_at);