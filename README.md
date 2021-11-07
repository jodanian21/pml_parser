<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Pizza Markup Language Parser

## About PML Parser

This PML parser allows the user to convert custom PML tags to Pizza Model objects which will be processed
as a Pizza Order request.

## Interfaces

- File upload - the system will allow user to upload PML files as long as the file is `text/plain` MIME type
- Manual Input - the system will allow user to input PML manually

## Methods

- parse() - this method accepts string value of the PML file or input to be converted as PML Object
- convertToXML() - this method will convert the PML format to XML object

## Live demo
[Click Here](https://floating-everglades-15176.herokuapp.com/)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

Jodan Ian Gallego
