

class AbstractView extends Backbone.View
    constructor: (opts) ->
        super(opts)


    initialize: (opts) ->
        @template = twig
            data: opts.template



    render: ->
        view = @template.render(@model.attributes)
        @$el.empty().html(view);





module.exports = AbstractView

