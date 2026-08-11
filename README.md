# PHP Calculator

A responsive web-based calculator built with **PHP, HTML, and CSS**.

It supports standard arithmetic operations, parentheses, exponentiation, square roots, decimals, sign toggling, and mathematical error handling — without JavaScript.

## Features

- Addition, subtraction, multiplication, and division
- Decimal numbers
- Parentheses
- Exponentiation
- Square root
- Positive/negative sign toggle
- Clear and backspace controls
- Standard operator precedence
- Right-associative exponentiation
- Error handling for division by zero, negative square roots, and `0^0`
- Responsive two-line display
- Session-based state management
- No JavaScript
- No `eval()`

## Technologies

- PHP
- HTML5
- CSS3
- PHP Sessions

## Project Structure

```text
php-calculator/
├── index.php
├── calculator.php
├── style.css
├── README.md
├── LICENSE
└── .gitignore
```

`index.php` handles form submissions, session state, and rendering.  
`calculator.php` contains the calculator handlers and expression evaluation logic.  
`style.css` contains the responsive interface styling.

## Getting Started

### XAMPP

Place the project inside:

```text
C:\xampp\htdocs\php-calculator
```

Start Apache and open:

```text
http://localhost/php-calculator/
```

### PHP Built-in Server

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000
```

## How It Works

The calculator stores its state in a PHP session and processes each button press through an HTML form submission.

Expressions are evaluated with this precedence:

1. Parentheses and square roots
2. Exponentiation
3. Multiplication and division
4. Addition and subtraction

Examples:

```text
2 + 3 × 4 = 14
(2 + 3) × 4 = 20
2 ^ 3 ^ 2 = 512
```

## License

Licensed under the [MIT License](LICENSE).

## Author

**Alan Shahoei** — [@Alan-Shahoei](https://github.com/Alan-Shahoei)