$(function(){
    Expression = {
        evaluate: function(expression) {
            $.ajax({
                url: 'ajax/evaluate.php',
                type: 'POST',
                data: {
                    expression: expression
                },
                success: function(result) {
                    try {
                        result = JSON.parse(result);
                        if ( ! result.success )
                            Expression.errorMessage(result.message);
                        else {
                            Expression.clearExpression();
                            Expression.addResult(result.expression, result.message);
                        }
                    } catch (e) {
                        Expression.errorMessage('Invalid result from server');
                    }
                },
                error: function(v, message) {
                    Expression.errorMessage(message);
                }
            })
        },

        clearExpression: function() {
            $('#expression').val('');
        },

        errorMessage: function(message) {
            $('#alert-error').html(message).show();
            $('#alert-success').hide();
        },

        addResult: function(expression, result) {
            $('#alert-success').show();
            $('#results').append('<li>' + expression + ' = ' + result + '</li>')
            $('#alert-error').hide();
        }
    }

    $('#main-form').submit( function(event) {
        event.preventDefault();
        Expression.evaluate( $('#expression').val() );
    } );
});