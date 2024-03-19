CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(32) NOT NULL,
    email VARCHAR(127) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(32) NOT NULL DEFAULT 'user'
);
CREATE TABLE movies (
    movie_id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    release_year INTEGER
);
CREATE TABLE movie_genre (
    movie_id INTEGER REFERENCES movies(movie_id),
    genre VARCHAR(127),
    PRIMARY KEY (movie_id, genre)
);
CREATE TABLE reviews (
    review_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id),
    movie_id INTEGER REFERENCES movies(movie_id),
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);