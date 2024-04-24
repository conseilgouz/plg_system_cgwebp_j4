# plg_system_cgwebp_j4
CG WebP - convert images to WEBP using <b>GD for image processing</b>, without any external library.

CG WebP plugin helps you optimize your page load.

When a page is displayed, all images from jpg and/or png formats are converted to WEBP format and stored in <b>media/plg_system_cgwebp directory</b> or in original images directory.

When images are updated, their webp images is updated as well. Webp images have a version based on original image hash, so, on change, they will be reloaded by web browser.

Storing created images in media directory has the double advantages to easily locate those images and not to double save them (by making media/plg_system_cgwebp an exception in Akeeba Backup)

On your pages, webp images will be displayed, replacing original images.

Note : to be compatible with DJ-WebP plugin, directory parameter is set to "Same".

<b>Important</b> : If you were using DJ-WebP plugin, don't forget to disable it.

![cgwebp](https://github.com/conseilgouz/plg_system_cgwebp_j4/assets/19435246/55d0bbe7-36c1-47f8-a278-50533f6796e5)

Click on <b>Check WebP support</b> button to make sure your server handles WebP conversion.

Note : Version 1.1.0 introduces a new <b>Destroy Webp</b> images button (enabled if WebP directory is set to Media).
