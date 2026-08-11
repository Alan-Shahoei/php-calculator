<?php
function processSelectedKey ($selectedKey, $state)
{
    $handlers = [
        '0' => 'handleDigit',
        '1' => 'handleDigit',
        '2' => 'handleDigit',
        '3' => 'handleDigit',
        '4' => 'handleDigit',
        '5' => 'handleDigit',
        '6' => 'handleDigit',
        '7' => 'handleDigit',
        '8' => 'handleDigit',
        '9' => 'handleDigit',

        '+' => 'handleOperator',
        '-' => 'handleOperator',
        '*' => 'handleOperator',
        '/' => 'handleOperator',
        '^' => 'handleOperator',

        '.' => 'handleDecimalPoint',
        '(' => 'handleOpeningParenthesis',
        ')' => 'handleClosingParenthesis',
        'sqrt' => 'handleSquareRoot',
        'toggle-sign' => 'handleToggleSign',
        'clear' => 'handleClear',
        'backspace' => 'handleBackspace',
        '=' => 'handleEquals'
    ];

    if ($selectedKey === null || !isset($handlers[$selectedKey]))
        return $state;

    return $handlers[$selectedKey]($selectedKey, $state);
}

function handleDigit($selectedKey, $state)
{
    return $state;
}

function handleOperator($selectedKey, $state)
{
    return $state;
}

function handleDecimalPoint($selectedKey, $state)
{
    return $state;
}

function handleOpeningParenthesis($selectedKey, $state)
{
    return $state;
}

function handleClosingParenthesis($selectedKey, $state)
{
    return $state;
}

function handleSquareRoot($selectedKey, $state)
{
    return $state;
}

function handleToggleSign($selectedKey, $state)
{
    return $state;
}

function handleClear($selectedKey, $state)
{
    return $state;
}

function handleBackspace($selectedKey, $state)
{
    return $state;
}

function handleEquals($selectedKey, $state)
{
    return $state;
}