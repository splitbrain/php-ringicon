RingIcon - A Indenticon/Glyphicon Library
=========================================

This minimal library generates identifiers based on an input seed. It can be
used to generate avatar images or visualize other identifying info (like
crypto keys). The result is a PNG or SVG image.

It has no dependencies except the GD library extension for PHP for PNG images.

![Multicolor with 3 Rings](sample.png)
![Monochrome with 5 Rings](mono.png)

Usage
-----

See the ``demo.html``/``demo.php`` files for examples.

You can tune the following parameters:

- The size of the resulting image (via constructor)
- The number of rings to generate (via constructor)
- Multicolor versus monochrome image (via ``setMono()`` method)

The monochrome version uses a single color and variates the alpha transparency of the rings.
