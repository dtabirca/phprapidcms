## What's this?
- A starting point, a flexible light base for plain websites, where a more complex solution is not justified.
- It relies on Composer, PDO, Twig, PHPMailer, Parsedown, Bootstrap, Lightbox, jQuery
- The main configuration is stored in one file. All the content is stored in the database, in a single table.
- The project can be easily extended with new plugins and themes by following the existing structure and logic.

## Usage

### Install

#### Prerequisites:
- Some basic understanding of the used technologies
- Have a domain name in place pointing to a public folder inside your web server
- Have Apache, Mysql and PHP 7+ installed.

#### Then: 
- Download the project in that folder
- Run `composer install` to add depencencies and generate autoload files
- Access /install.php to create database and insert dummy data.

### Configure

#### The config file lets you define:
- A name for the website, a slogan, an app key
- The backend entrance path, an admin username and a pass
- A SMTP server for contact us forms
- The modules to include
- The theme to be used
- The navigation tree (pages and menus)
- The database connection.

### Manage

#### The content(pages) can be managed using the backend interface:
- At this point there is one basic page type which can be easily become a photo album, a catalog list or a blog entry by adding the right elements to it (catalog list, contact form, comments box, publishing date, featured image, thumbnails list).
- Page slugs are not automatically generated!
- Paths are an important piece, as on front side they are used to identify the requested page and its relation with other pages. The only rule for a page to be displayed is to use the same paths as defined in the configuration file.
- The body of the page can contain both Markdown and HTML. Use your prefered external editor to format this part before adding it to the page.  
- Distinct meta descripton and keywords can be added for each page.

### Extend
- Plugins should be placed in the `_plug` folder
- Each plug-in has an init file and two folders, action and display
- Themes are being kept in the `web/schemes` folder.

## What's next? (TODO)
- Test with flat files, SQLite, PostgreSQL
- Import pages from document files
- Build more plug-ins, more themes.

## Screenshots

![screenshot](https://github.com/dtabirca/phprapidcms/blob/master/_upls/demo/manage-content.png "Backend interface for page management")


![screenshot](https://github.com/dtabirca/phprapidcms/blob/master/_upls/demo/blog-entry.png "Page demo 1")


![screenshot](https://github.com/dtabirca/phprapidcms/blob/master/_upls/demo/catalog-list.png "Page demo 2")


![screenshot](https://github.com/dtabirca/phprapidcms/blob/master/_upls/demo/contact-form.png "Page demo 3")