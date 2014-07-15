AbstractViewMediator = require "../abstract/AbstractViewMediator.coffee"

class NavigationView extends AbstractViewMediator

    start: ->


    gotoView: (view) ->


    events:
        "click a" : "onNavClick"


    onNavClick: (e) =>
        e.preventDefault()
        $el = $(e.target)
        href =  $el.attr('href')

        Backbone.history.navigate href ,
            trigger: true




module.exports = NavigationView