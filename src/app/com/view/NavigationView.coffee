AbstractViewMediator = require "../abstract/AbstractViewMediator.coffee"

class NavigationView extends AbstractViewMediator

    start: ->


    gotoView: (view) ->


    events:
        "click li" : "onNavClick"


    onNavClick: (e) =>
        e.preventDefault()
        $el = $(e.target).closest("li")

        href =  $el.find("> a").attr('href')

        subMenu = $el.find("ul")
        if subMenu?
            if(!subMenu.data('active'))
                subMenu.data('active', true)
                TweenMax.to subMenu.find("li") , .3 ,
                    autoAlpha:1
            else
                subMenu.data('active', false)
                TweenMax.to subMenu.find("li") , .3 ,
                    autoAlpha:0




        if href
            Backbone.history.navigate href ,
                trigger: true




module.exports = NavigationView