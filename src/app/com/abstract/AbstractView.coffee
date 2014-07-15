

class AbstractView extends Backbone.View
    constructor: (opts) ->
        super(opts)


    getTemplate: ->
        @template = twig
            id: @model.get("id")
            href: "/"+@model.get("view")
            async:false



    render: ->
        @getTemplate()
        view = @template.render(@model.attributes)
        @$el.empty().html(view);





module.exports = AbstractView

