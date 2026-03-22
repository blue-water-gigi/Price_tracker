CREATE TABLE products(
    product_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    store_id INT NOT NULL REFERENCES stores(store_id),
    user_id INT NOT NULL REFERENCES users(user_id),
    url TEXT NOT NULL,
    current_price NUMERIC(10, 2) NOT NULL,
    image_url TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    currency VARCHAR(10) DEFAULT 'RUB',
    CONSTRAINT unique_user_url UNIQUE(user_id, url)
);