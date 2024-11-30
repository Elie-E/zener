# Lyric Quote Sharing Platform

A modern web application for sharing and discovering song lyrics quotes, built with PHP and MySQL. Users can share their favorite song lyrics, engage with others through comments and likes, and discover new music through shared quotes.

## Features

### Core Functionality
- Share song lyrics with artist and song title
- View all shared quotes in a chronological feed
- Individual quote pages with detailed view
- Like/unlike quotes
- Nested comment system
- User authentication (register, login, logout)

### Technical Features
- MVC architecture
- PDO database abstraction with connection retry mechanism
- Singleton pattern for database connections
- Docker containerization
- Responsive design with Tailwind CSS
- AJAX interactions for dynamic updates
- SQL injection protection
- XSS protection through proper escaping
- Password hashing for security

## Prerequisites

- Docker
- Docker Compose
- PHP 8.2 or higher
- Composer (PHP package manager)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd lyric-quotes
```

2. Create a `.env` file in the root directory:
```env
DB_HOST=db
DB_USER=lyrics_user
DB_PASSWORD=lyrics_password
DB_NAME=lyrics_db
```

3. Start the Docker containers:
```bash
docker-compose up -d
```

4. Install PHP dependencies:
```bash
composer install
```

5. The application will be available at:
```
http://localhost:8080
```

## Project Structure

```
lyric-quotes/
├── docker/
│   ├── mysql/
│   │   └── init.sql       # Database initialization
│   └── php/
│       └── Dockerfile     # PHP container configuration
├── public/
│   └── index.php         # Application entry point
├── src/
│   ├── Controllers/      # Application controllers
│   ├── Models/           # Database models
│   └── Database.php      # Database connection manager
├── views/
│   ├── auth/            # Authentication views
│   ├── layouts/         # Layout templates
│   └── quotes/          # Quote-related views
├── docker-compose.yml   # Docker services configuration
├── composer.json        # PHP dependencies
└── README.md           # Project documentation
```

## Database Schema

### Users Table
- id (INT, PRIMARY KEY)
- username (VARCHAR)
- password (VARCHAR, hashed)
- created_at (TIMESTAMP)

### Quotes Table
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- quote_text (TEXT)
- artist (VARCHAR)
- song_title (VARCHAR)
- created_at (TIMESTAMP)

### Comments Table
- id (INT, PRIMARY KEY)
- quote_id (INT, FOREIGN KEY)
- user_id (INT, FOREIGN KEY)
- parent_id (INT, FOREIGN KEY, nullable)
- content (TEXT)
- created_at (TIMESTAMP)

### Likes Table
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- quote_id (INT, FOREIGN KEY)
- created_at (TIMESTAMP)
- UNIQUE KEY (user_id, quote_id)

## API Endpoints

### Authentication
- GET `/login` - Display login form
- POST `/auth/process-login` - Process login
- GET `/register` - Display registration form
- POST `/auth/process-register` - Process registration
- GET `/logout` - Log out user

### Quotes
- GET `/` - Display all quotes
- GET `/quote/view` - View single quote
- GET `/quote/create` - Display quote creation form
- POST `/quote/store` - Create new quote

### Comments
- POST `/comment/store` - Create new comment

### Likes
- POST `/like/toggle` - Toggle like status on quote

## Security

- Passwords are hashed using PHP's password_hash()
- SQL injection prevention through prepared statements
- XSS prevention through proper HTML escaping
- CSRF protection on forms
- Secure session handling
- Input validation and sanitization

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Future Enhancements

- OAuth integration for social login
- Advanced search functionality
- User profiles with statistics
- Quote collections/playlists
- Social sharing features
- Email notifications
- Trending quotes section
- Music streaming service integration
- Mobile application

## Acknowledgments

- PHP community
- Tailwind CSS team
- Docker team
- All contributors and users of the platform
