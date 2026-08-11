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
    if ($state['result'] !== null) {
        $state['result'] = null;
        $state['expression'] = [$selectedKey];
        return $state;
    }

    if ($state['expression'] === []) {
        $state['expression'][] = $selectedKey;
        return $state;
    }

    $expression = $state['expression'];
    $lastIndex = array_key_last($expression);

    if ($expression[$lastIndex] === ')')
        return $state;

    if (count($expression) === 1 && $expression[$lastIndex] === '0') {
        $state['expression'][$lastIndex] = $selectedKey;
        return $state;
    }

    $numberTokens = ['.', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    if ($expression[$lastIndex] === '0' && !in_array($expression[$lastIndex - 1], $numberTokens, true)) {
        $state['expression'][$lastIndex] = $selectedKey;
        return $state;
    }

    $state['expression'][] = $selectedKey;
    return $state;
}

function handleOperator($selectedKey, $state)
{
    if ($state['result'] !== null) {
        $state['expression'] = [$state['result']];
        $state['result'] = null;
        $state['expression'][] = $selectedKey;
        return $state;
    }

    if ($state['expression'] === [])
        return $state;

    $lastIndex = array_key_last($state['expression']);

    if (in_array($state['expression'][$lastIndex], ['+', '-', '*', '/', '^'], true)) {
        $state['expression'][$lastIndex] = $selectedKey;
        return $state;
    }

    if (in_array($state['expression'][$lastIndex], ['(', '.', 'sqrt'], true))
        return $state;

    $state['expression'][] = $selectedKey;
    return $state;
}

function handleDecimalPoint($selectedKey, $state)
{
    if ($state['result'] !== null) {
        $state['result'] = null;
        $state['expression'] = ['0', $selectedKey];
        return $state;
    }

    if ($state['expression'] === []) {
        $state['expression'] = ['0', $selectedKey];
        return $state;
    }

    $expressionLastToken = $state['expression'][array_key_last($state['expression'])];
    if (in_array($expressionLastToken, ['.', ')'], true))
        return $state;

    $numberBoundaries = ['+', '-', '*', '/', '^', 'sqrt', '('];

    if (in_array($expressionLastToken, $numberBoundaries, true)) {
        $state['expression'][] = '0';
        $state['expression'][] = $selectedKey;
        return $state;
    }

    $lastDecimalPointIndex = -1;
    $lastNumberBoundaryIndex = -1;
    foreach ($state['expression'] as $index => $token)
        if ($token === '.')
            $lastDecimalPointIndex = $index;
        elseif (in_array($token, $numberBoundaries, true))
            $lastNumberBoundaryIndex = $index;

    if ($lastNumberBoundaryIndex < $lastDecimalPointIndex)
        return $state;

    $state['expression'][] = $selectedKey;
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
    $state['expression'] = [];
    $state['result'] = null;
    return $state;
}

function handleBackspace($selectedKey, $state)
{
    if ($state['expression'] === [])
        return $state;

    if ($state['result'] !== null) {
        $state['expression'] = [];
        return $state;
    }

    array_pop($state['expression']);

    return $state;
}

function handleEquals($selectedKey, $state)
{
    return $state;
}