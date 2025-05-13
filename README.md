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

This project is licensed under the MIT License - see below for details:

```
MIT License

Copyright (c) 2025 Callum

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

Note: The BellotaText font is licensed under the SIL Open Font License.

## Portfolio Information

This project was created as part of my web development portfolio to showcase my skills in:

- PHP backend development
- Frontend development with SCSS and JavaScript
- MVC architecture implementation
- User interface design for practical applications
- Build system configuration using Grunt
