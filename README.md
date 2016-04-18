RingIcon - A Indenticon/Glyphicon Library
=========================================

This minimal library generates identifiers based on an input seed. It can be used to generate avatar images
or visualize other identifying info (like crypto keys).

It has no dependencies except the GD library extension for PHP.

![Multicolor with 3 Rings](sample.png)
![Monochrome with 5 Rings](mono.png)

Usage
-----

See the demo.php file for an example.

You can tune the following parameters:

- The size of the resulting image (via constructor)
- The number of rings to generate (via constructor)
- Multicolor versus monochrome image (via setMono() function)

The monochrome version uses a single color and variates the alpha transparency of the rings.