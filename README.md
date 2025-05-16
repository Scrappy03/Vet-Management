# VetCare - Veterinary Practice Management System

A comprehensive web-based system for managing veterinary clinics, including patient records, staff schedules, and appointments.

## Features

- **Dashboard**: Overview of clinic activities and statistics
- **Patient Management**: Track pet profiles and medical histories
- **Calendar System**: Schedule and manage appointments
- **Staff Management**: Organize staff information and schedules
- **User Authentication**: Secure login system

## Technology Stack

- **Backend**: PHP
- **Frontend**: HTML, SCSS/CSS, JavaScript
- **Templating**: Smarty
- **Build Tools**: Grunt
- **Package Management**: Composer, Yarn

## Getting Started

### Prerequisites

- PHP 7.4 or higher
- Composer
- Node.js and Yarn
- Web server (Apache/Nginx)

### Installation

1. Clone the repository:

   ```
   git clone https://github.com/Scrappy03/Vet-Management
   ```

2. Install PHP dependencies:

   ```
   composer install
   ```

3. Install Node.js dependencies:

   ```
   yarn install
   ```

4. Build frontend assets:
   ```
   grunt build
   ```

### Development

To start development with automatic rebuilding:

```
grunt
```

This will watch for changes in SCSS files and rebuild CSS automatically.

## Project Structure

- `/controllers` - PHP controllers handling business logic
- `/css` - Compiled CSS files
- `/fonts` - Custom fonts including BellotaText
- `/images` - Image assets
- `/includes` - PHP includes and utilities
- `/js` - JavaScript files
- `/scss` - SCSS source files
- `/smarty` - Smarty templates
- `/vendor` - Composer dependencies
- `/views` - View templates

## Building Assets

- `grunt sass:main` - Compiles SCSS to CSS
- `grunt cssmin` - Minifies CSS files
- `grunt uglify:main` - Minifies JavaScript files
- `grunt build` - Runs all build tasks
- `grunt vendors` - Builds vendor JavaScript files

## License

This project is licensed under the MIT License

```

Note: The BellotaText font is licensed under the SIL Open Font License.

```
## Portfolio Information

This project was created as part of my web development portfolio to showcase my skills in:

- PHP backend development
- Frontend development with SCSS and JavaScript
- MVC architecture implementation
- User interface design for practical applications
- Build system configuration using Grunt
