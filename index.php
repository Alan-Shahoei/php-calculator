<?php

require_once 'calculator.php';

$validKeys = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.',
        '+', '-', '*', '/', '^', '(', ')', '=', 'clear',
        'backspace', 'sqrt', 'toggle-sign'
];

$selectedKey = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key']) && in_array($_POST['key'], $validKeys, true))
        $selectedKey = $_POST['key'];

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta
                name="viewport"
                content="width=device-width, initial-scale=1.0"
        >

        <title>PHP Calculator</title>

        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <main class="calculator">

            <div class="calculator-display">
                <div class="expression"></div>
                <div class="result"></div>
            </div>

            <form method="post" class="calculator-keypad">

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value="clear"
                >
                    C
                </button>

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value="("
                >
                    (
                </button>

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value=")"
                >
                    )
                </button>

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value="backspace"
                        aria-label="Backspace"
                >
                    <svg
                            class="key-icon backspace-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M21 5H9L3 12L9 19H21V5Z"/>
                        <path d="M11 9L17 15"/>
                        <path d="M17 9L11 15"/>
                    </svg>
                </button>

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value="sqrt"
                        aria-label="Square root"
                >
                    <svg
                            class="root-icon"
                            viewBox="0 0 48 30"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path
                                class="root-index"
                                d="
                                        M4 6
                                        C4 3.8 5.6 2.5 7.8 2.5
                                        C10 2.5 11.5 3.8 11.5 5.8
                                        C11.5 7.3 10.7 8.4 9.4 9.5
                                        L4.5 13.5
                                        H12
                                    "
                        />

                        <path
                                class="root-radical"
                                d="
                                        M12.5 18
                                        L16 22
                                        L21.5 8
                                        H43
                                    "
                        />

                        <path
                                class="root-variable"
                                d="M27 14L35 22"
                        />

                        <path
                                class="root-variable"
                                d="M35 14L27 22"
                        />
                    </svg>
                </button>

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value="^"
                        aria-label="Power"
                >
                    <svg
                            class="key-icon power-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M7 15L12 8L17 15"/>
                    </svg>
                </button>

                <button
                        class="key"
                        type="submit"
                        name="key"
                        value="toggle-sign"
                        aria-label="Toggle positive or negative"
                >
                    <svg
                            class="sign-icon"
                            viewBox="0 0 32 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M4 7H12"/>
                        <path d="M8 3V11"/>
                        <path d="M11 20L21 4"/>
                        <path d="M20 17H28"/>
                    </svg>
                </button>

                <button
                        class="key key-operator"
                        type="submit"
                        name="key"
                        value="/"
                        aria-label="Divide"
                >
                    <svg
                            class="key-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <circle cx="12" cy="6" r="1.25"/>
                        <path d="M5 12H19"/>
                        <circle cx="12" cy="18" r="1.25"/>
                    </svg>
                </button>

                <button class="key" type="submit" name="key" value="7">7</button>
                <button class="key" type="submit" name="key" value="8">8</button>
                <button class="key" type="submit" name="key" value="9">9</button>

                <button
                        class="key key-operator"
                        type="submit"
                        name="key"
                        value="*"
                        aria-label="Multiply"
                >
                    <svg
                            class="key-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M6 6L18 18"/>
                        <path d="M18 6L6 18"/>
                    </svg>
                </button>

                <button class="key" type="submit" name="key" value="4">4</button>
                <button class="key" type="submit" name="key" value="5">5</button>
                <button class="key" type="submit" name="key" value="6">6</button>

                <button
                        class="key key-operator"
                        type="submit"
                        name="key"
                        value="-"
                        aria-label="Subtract"
                >
                    <svg
                            class="key-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M5 12H19"/>
                    </svg>
                </button>

                <button class="key" type="submit" name="key" value="1">1</button>
                <button class="key" type="submit" name="key" value="2">2</button>
                <button class="key" type="submit" name="key" value="3">3</button>

                <button
                        class="key key-operator"
                        type="submit"
                        name="key"
                        value="+"
                        aria-label="Add"
                >
                    <svg
                            class="key-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M5 12H19"/>
                        <path d="M12 5V19"/>
                    </svg>
                </button>

                <button class="key" type="submit" name="key" value=".">.</button>
                <button class="key" type="submit" name="key" value="0">0</button>

                <button
                        class="key key-equals"
                        type="submit"
                        name="key"
                        value="="
                        aria-label="Equals"
                >
                    <svg
                            class="key-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                    >
                        <path d="M5 9H19"/>
                        <path d="M5 15H19"/>
                    </svg>
                </button>

            </form>

        </main>

    </body>

</html>