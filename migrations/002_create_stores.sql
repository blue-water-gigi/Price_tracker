CREATE TABLE stores (
    store_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    parser_class VARCHAR(150) NOT NULL
);