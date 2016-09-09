# Direct Image Manipulation Application

This application allows for direct image manipulation of a supplied image on the fly.

## Install the Application

Run this command from the directory in which you want to install the application.

    composer install
    
* Copy settings.php.default to settings.php
* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

##Settings

allowed_hosts - Array of allowed image source domains

watermark - image_location - Path to image to be used as a watermark

valid_options - Disable methods

##Current Methods

Resize - default method for use with application
Watermark - Allows the addition of a custom watermark to the provided image.

###Usage

Once installed point your brower to: domain.com/{width}/{height}/{method[-method]}/{url}

Example:

mattpeck.uk/500/500/resize/http://mattpeck.uk/img/me.png
mattpeck.uk/500/500/resize-watermark/http://mattpeck.uk/img/me.png