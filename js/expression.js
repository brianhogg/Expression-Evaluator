
function ExpressionResult(result) {
    this.result = result;
    this.view = $('#result-template').clone();
    this.view.removeAttr('id').find('.expression-result').html(result.expression + ' = ' + result.message);
    this.view.appendTo('#results');
    this.view.show();

    this.removeView = function() {
        this.unbindEvents();
        this.view.remove();
    }

    this.remove = function() {
        this.removeView();
        ExpressionEvaluator.removeResult(this);
    }

    this.edit = function() {
        var me = this;
        $('#expression').val( me.result.expression );
        ExpressionEvaluator.refocusExpressionInput();
        this.remove();
    }

    this.bindEvents = function() {
        var me = this;
        this.view.find('.remove').on('click', function() { me.remove() });
        this.view.find('.edit, .expression-result').on('click', function() { me.edit(); });
    }

    this.unbindEvents = function() {
        var me = this;
        this.view.find('.remove').off('click');
        this.view.find('.edit').off('click');
    }

    this.bindEvents();
}

var ExpressionEvaluator = {
    results: [],

    initialize: function() {
        var me = this;
        $('#main-form').submit( function(event) {
            event.preventDefault();
            me.evaluate( $('#main-form #expression').val() );
        } );

        $('#clearall').click( function(event) {
            event.preventDefault();
            for ( i = 0; i < me.results.length; i++ ) {
                me.results[i].removeView();
            }
            me.results = [];
        } );

        $(document).ajaxStart(function() {
            $('#evaluate').attr('disable', 'disable');
        });

        $(document).ajaxStop(function() {
            $('#evaluate').removeAttr('disable');
        });
    },

    evaluate: function(expression) {
        var me = this;
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
                        me.errorMessage(result.message);
                    else {
                        me.clearExpression();
                        me.successMessage();
                        me.addResult(new ExpressionResult(result));
                    }
                } catch (e) {
                    alert('Invalid result from server - ' + e);
                }
            },
            error: function(v, message) {
                me.errorMessage(message);
            }
        })
    },

    clearExpression: function() {
        $('#expression').val('');
    },

    addResult: function(result) {
        this.results.push(result);
    },

    removeResult: function(result) {
        // Will not work in IE less than 9
        var index = $.inArray(result, this.results);
        if ( index > -1 )
            this.results.splice(index, 1);
    },

    refocusExpressionInput: function() {
        $('#expression').focus();
    },

    errorMessage: function(message) {
        $('#alert-error').html(message).show();
        $('#alert-success').hide();
        this.refocusExpressionInput();
    },

    successMessage: function(result) {
        $('#alert-success').show();
        $('#alert-error').hide();
        this.refocusExpressionInput();
    }
};

$(function(){
    ExpressionEvaluator.initialize();
});
