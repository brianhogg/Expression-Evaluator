<!DOCTYPE html>
<html>
<head>
    <title>Expression Evaluator by Brian Hogg</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/expression.css" rel="stylesheet" media="screen">
</head>
<body>
    <h1>Expression Evaluator</h1>
    <p class="lead">
        Enter a basic mathematical expression below to evaluate.  Supports <strong>basic operators</strong> (*, /, +, -), <strong>exponential</strong> (^) and <strong>brackets</strong>.
    </p>
    <div id="alert-error" class="alert alert-block alert-error">
    </div>
    <div id="alert-success" class="alert alert-block alert-success">
        Expression evaluated successfully
    </div>
    <form id="main-form">
        <fieldset>
            <label>Expression</label>
            <input id="expression" placeholder="Enter an expression..." type="text" />
            <span class="help-block">Examples include: <em>1 + 1</em>, <em>2 + ( 3 * 7 )</em>, <em>3 + ( 2 * ( 2 / 2 ) )</em></span>
            <input id="evaluate" class="btn btn-primary" type="submit" value="Evaluate" />
            <button id="clearall" class="btn">Clear All</button>
        </fieldset>
    </form>
    <ul id="results" class="inline">
    </ul>
    <li id="result-template">
        <div class="btn-group">
            <button class="btn expression-result"></button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li class="edit">Edit</li>
                <li class="divider"></li>
                <li class="remove">Remove</li>
            </ul>
        </div>
    </li>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/expression.js"></script>
</body>
</html>