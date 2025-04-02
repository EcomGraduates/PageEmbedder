# Page Embedder for FreeScout

This module allows you to embed external web pages directly into your FreeScout helpdesk system with custom navigation links and seamless integration options.

## Features

- Embed any external web page within FreeScout
- Create custom HTML/CSS/JavaScript content without external URLs
- Configure custom page titles for navigation
- Choose custom icons for menu and navbar items
- Place embedded pages in the main menu or top navbar
- Restrict access to admin users only
- Set up custom URL paths for embedded pages
- **Seamless integration** with options to:
  - Hide FreeScout headers for full-width integration
  - Hide page titles for more screen real estate
  - Customize spacing for a more native look
  - Forward URL parameters to embedded pages
  - Attempt auto-login for connected applications
  - Add custom CSS and JavaScript for enhanced integration
- Easy management through the admin settings interface

## Installation

1. Download the latest release
2. Extract the PageEmbedder folder to your FreeScout `Modules` directory
3. Go to FreeScout Admin → Modules and activate the Page Embedder module

## Usage

1. Go to Settings → Page Embedder
2. Add the pages you want to embed:
   - Enter a menu title (appears in the navigation)
   - Choose content type (External URL or Custom HTML)
   - For external URLs: Enter the URL to embed
   - For custom content: Enter your own HTML, CSS, and JavaScript
   - Define a custom path for the embedded page
   - Select an icon for the menu or navbar item
   - Choose where to display the link (main menu or top navbar)
   - Optionally restrict access to admin users only
   - Configure display and integration options:
     - Enable seamless mode for better visual integration
     - Hide the FreeScout header for full-width display
     - Hide the page title for more content space
     - Forward URL parameters to the embedded page
     - Enable auto-login attempts for compatible services
   - Add custom CSS and JavaScript for advanced customization
3. Save the settings
4. The pages will appear in the selected navigation location

## Content Options

The module provides two ways to create embedded pages:

- **External URL**: Embed an existing web page from any URL
- **Custom HTML Content**: Create your own HTML content directly within FreeScout
  - Add your own styling with CSS
  - Use JavaScript for interactive elements
  - Perfect for custom dashboards, documentation, or internal tools

## Access Control

You can control who can access your embedded pages:

- **Admin Only**: When enabled, only admin users can see and access the page
- **Public**: When disabled, all authenticated users can access the page

Admin-only pages will not appear in the navigation for regular users, and attempting to access them directly will result in a permission error.

## Integration Options

The module provides several options to make embedded pages appear more seamless:

- **Navigation Placement**: Choose between main menu or top navbar for each embedded page
- **Custom Icons**: Select from a variety of Bootstrap Glyphicons for menu items
- **Seamless Mode**: Reduces spacing and margins for a more integrated look
- **Hide FreeScout Header**: Removes the page title bar for full-width embedding
- **Hide Page Title**: Removes the title from the top of the embedded page for more screen space
- **Forward URL Parameters**: Passes any URL parameters to the embedded page
- **Auto-Login Attempt**: Tries to pass user credentials to the embedded application
- **Custom CSS/JS**: Add custom styling and scripting for advanced integrations

## Security

Embedded pages are loaded in an iframe with appropriate security restrictions via the sandbox attribute. The module uses modern iframe sandboxing to ensure proper isolation while allowing necessary functionality.

For custom HTML content, the module allows script execution within the context of your FreeScout installation. Use this feature responsibly.

#Screenshots 

<img width="2527" alt="image" src="https://github.com/user-attachments/assets/15387e5d-f653-4ba0-8bfd-7e19240aa47b" />
<img width="2527" alt="image" src="https://github.com/user-attachments/assets/cc2aaf23-7f2e-4276-92fb-06eccb32b411" />


## License

This module is licensed under the MIT License. 
