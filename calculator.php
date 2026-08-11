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

    $expressionLastIndex = array_key_last($state['expression']);
    $expressionLastToken = $state['expression'][$expressionLastIndex];

    if ($expressionLastToken === ')')
        return $state;

    if ($expressionLastToken === '0') {
        $state['expression'][$expressionLastIndex] = $selectedKey;
        return $state;
    }

    if (is_numeric($expressionLastToken)) {
        $number = $expressionLastToken;
        $number .= $selectedKey;
        $state['expression'][$expressionLastIndex] = $number;
        return $state;
    }

    $state['expression'][] = $selectedKey;
    return $state;
}

function handleOperator($selectedKey, $state)
{
    if ($state['result'] !== null) {
        $state['expression'] = [(string) $state['result']];
        $state['result'] = null;
        $state['expression'][] = $selectedKey;
        return $state;
    }

    if ($state['expression'] === [])
        return $state;

    $lastIndex = array_key_last($state['expression']);
    $lastToken = $state['expression'][$lastIndex];

    if (in_array($lastToken, ['(', 'sqrt'], true))
        return $state;

    if (str_ends_with($lastToken, '.'))
        return $state;

    if (in_array($lastToken, ['+', '-', '*', '/', '^'], true)) {
        $state['expression'][$lastIndex] = $selectedKey;
        return $state;
    }

    $state['expression'][] = $selectedKey;
    return $state;
}

function handleDecimalPoint($selectedKey, $state)
{
    if ($state['result'] !== null) {
        $state['result'] = null;
        $state['expression'] = ['0.'];
        return $state;
    }

    if ($state['expression'] === []) {
        $state['expression'] = ['0.'];
        return $state;
    }

    $expressionLastIndex = array_key_last($state['expression']);
    $expressionLastToken = $state['expression'][$expressionLastIndex];

    if ($expressionLastToken === ')')
        return $state;

    if (str_contains($expressionLastToken, '.'))
        return $state;

    $numberBoundaries = ['+', '-', '*', '/', '^', 'sqrt', '('];
    if (in_array($expressionLastToken, $numberBoundaries, true)) {
        $state['expression'][] = '0.';
        return $state;
    }

    if (is_numeric($expressionLastToken)) {
        $number = $expressionLastToken;
        $number .= $selectedKey;
        $state['expression'][$expressionLastIndex] = $number;
        return $state;
    }

    return $state;
}

function handleOpeningParenthesis($selectedKey, $state)
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

    $expressionLastToken = $state['expression'][array_key_last($state['expression'])];

    if ($expressionLastToken === ')')
        return $state;

    if (is_numeric($expressionLastToken))
        return $state;

    $state['expression'][] = $selectedKey;
    return $state;
}

function handleClosingParenthesis($selectedKey, $state)
{
    if ($state['result'] !== null || $state['expression'] === [])
        return $state;

    $expressionLastToken = $state['expression'][array_key_last($state['expression'])];

    if (str_ends_with($expressionLastToken, '.'))
        return $state;

    if (is_numeric($expressionLastToken) || $expressionLastToken === ')') {
        $openingParenthesisCount = 0;
        $closingParenthesisCount = 0;

        foreach ($state['expression'] as $token)
            if ($token === '(')
                $openingParenthesisCount++;
            elseif ($token === ')')
                $closingParenthesisCount++;

        if ($openingParenthesisCount > $closingParenthesisCount) {
            $state['expression'][] = $selectedKey;
            return $state;
        }
    }

    return $state;
}

function handleSquareRoot($selectedKey, $state)
{
    if ($state['result'] !== null) {
        $state['expression'] = [$selectedKey, '(', (string) $state['result'], ')'];
        $state['result'] = null;
        return $state;
    }

    if ($state['expression'] === []) {
        $state['expression'][] = $selectedKey;
        return $state;
    }

    $expressionLastToken = $state['expression'][array_key_last($state['expression'])];
    if (in_array($expressionLastToken, ['+', '-', '*', '/', '^', '(', 'sqrt'], true)) {
        $state['expression'][] = $selectedKey;
        return $state;
    }

    return $state;
}

function handleToggleSign($selectedKey, $state)
{
    if ($state['result'] !== null) {
        if ((float) $state['result'] !== 0.0)
            $state['result'] *= -1;

        return $state;
    }

    if ($state['expression'] === [])
        return $state;

    $expressionLastIndex = array_key_last($state['expression']);
    $expressionLastToken = $state['expression'][$expressionLastIndex];

    if (!is_numeric($expressionLastToken) && (float)$expressionLastToken === 0.0)
        return $state;

    if (str_starts_with($expressionLastToken, '-'))
        $state['expression'][$expressionLastIndex] = substr($expressionLastToken, 1);
    else
        $state['expression'][$expressionLastIndex] = '-' . $expressionLastToken;

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

    $expressionLastIndex = array_key_last($state['expression']);
    $expressionLastToken = $state['expression'][$expressionLastIndex];

    if (!is_numeric($expressionLastToken)) {
        array_pop($state['expression']);
        return $state;
    }

    if (strlen($expressionLastToken) === 1) {
        array_pop($state['expression']);
        return $state;
    }

    if (strlen($expressionLastToken) === 2 && str_starts_with($expressionLastToken, '-')) {
        array_pop($state['expression']);
        return $state;
    }

    $updatedToken = substr($expressionLastToken, 0, -1);

    if ($updatedToken === '-0')
        $updatedToken = '0';

    $state['expression'][$expressionLastIndex] = $updatedToken;

    return $state;
}

function handleEquals($selectedKey, $state)
{
    if ($state['result'] !== null)
        return $state;

    if ($state['expression'] === [])
        return $state;

    $expressionLastToken = $state['expression'][array_key_last($state['expression'])];

    if (str_ends_with($expressionLastToken, '.'))
        return $state;

    if (!is_numeric($expressionLastToken) && $expressionLastToken !== ')')
        return $state;

    $openingParenthesisCount = 0;
    $closingParenthesisCount = 0;

    foreach ($state['expression'] as $token)
        if ($token === '(')
            $openingParenthesisCount++;
        elseif ($token === ')')
            $closingParenthesisCount++;

    if ($openingParenthesisCount > $closingParenthesisCount) {
        $missingClosingParentheses = $openingParenthesisCount - $closingParenthesisCount;
        for ($i = 0; $i < $missingClosingParentheses; $i++)
            $state['expression'][] = ')';
    }

    try {
        $state['result'] = evaluateExpression($state['expression']);
        $state['error'] = null;
    } catch (DomainException | DivisionByZeroError $exception) {
        $state['result'] = null;
        $state['error'] = $exception->getMessage();
    }

    return $state;
}

function evaluateExpression($expression): float
{
    while (in_array('(', $expression, true)) {
        $lastOpenPIndex = -1;
        $firstClosePIndex = -1;

        foreach ($expression as $index => $token)
            if ($token === '(')
                $lastOpenPIndex = $index;

        for ($i = $lastOpenPIndex + 1; $i < count($expression); $i++)
            if ($expression[$i] === ')') {
                $firstClosePIndex = $i;
                break;
            }

        $innerExpression = array_slice($expression, $lastOpenPIndex + 1, $firstClosePIndex - $lastOpenPIndex - 1);

        $innerResult = evaluateFlatExpression($innerExpression);

        if ($lastOpenPIndex > 0 && $expression[$lastOpenPIndex - 1] === 'sqrt') {
            if ($innerResult < 0)
                throw new DomainException('Square root of a negative number is undefined.');

            $innerResult = sqrt($innerResult);

            $replaceStartIndex = $lastOpenPIndex - 1;
            $replaceLength = $firstClosePIndex - $replaceStartIndex + 1;
        } else {
            $replaceStartIndex = $lastOpenPIndex;
            $replaceLength = $firstClosePIndex - $lastOpenPIndex + 1;
        }

        array_splice($expression, $replaceStartIndex, $replaceLength, [(string) $innerResult]);
    }

    return evaluateFlatExpression($expression);
}

function evaluateFlatExpression($expression): float
{
    while (in_array('sqrt', $expression, true)) {
        $operatorIndex = array_search('sqrt', array_reverse($expression, true), true);

        $operand = (float) $expression[$operatorIndex + 1];

        if ($operand < 0) throw new DomainException('Square root of a negative number is undefined.');

        $result = sqrt($operand);

        array_splice($expression, $operatorIndex, 2, [(string) $result]);
    }

    while (in_array('^', $expression, true)) {
        $operatorIndex = array_search('^', array_reverse($expression, true), true);

        $leftOperand = (float) $expression[$operatorIndex - 1];
        $rightOperand = (float) $expression[$operatorIndex + 1];

        if ($leftOperand === 0.0 && $rightOperand === 0.0)
            throw new DomainException('0^0 is undefined.');

        $result = $leftOperand ** $rightOperand;

        array_splice($expression, $operatorIndex - 1, 3, [(string) $result]);
    }

    while (in_array('*', $expression, true) || in_array('/', $expression, true)) {
        $operatorIndex = null;

        foreach ($expression as $index => $token)
            if ($token === '*' || $token === '/') {
                $operatorIndex = $index;
                break;
            }

        $leftOperand = (float) $expression[$operatorIndex - 1];
        $rightOperand = (float) $expression[$operatorIndex + 1];

        if ($expression[$operatorIndex] === '/') {
            if ($rightOperand === 0.0)
                throw new DivisionByZeroError('Division by zero.');

            $result = $leftOperand / $rightOperand;
        } else
            $result = $leftOperand * $rightOperand;

        array_splice($expression, $operatorIndex - 1, 3, [(string) $result]);
    }

    while (in_array('+', $expression, true) || in_array('-', $expression, true)) {
        $operatorIndex = null;

        foreach ($expression as $index => $token)
            if ($token === '+' || $token === '-') {
                $operatorIndex = $index;
                break;
            }

        $leftOperand = (float) $expression[$operatorIndex - 1];
        $rightOperand = (float) $expression[$operatorIndex + 1];

        if ($expression[$operatorIndex] === '+')
            $result = $leftOperand + $rightOperand;
        else
            $result = $leftOperand - $rightOperand;

        array_splice($expression, $operatorIndex - 1, 3, [(string) $result]);
    }

    return (float) $expression[0];
}