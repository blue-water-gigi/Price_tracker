CREATE TABLE products(
    product_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    store_id INT NOT NULL REFERENCES stores(store_id),
    user_id INT NOT NULL REFERENCES users(user_id),
    url VARCHAR(255) NOT NULL,
    current_price NUMERIC(10, 2) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP,
    CONSTRAINT unique_user_url UNIQUE(user_id, url)
);