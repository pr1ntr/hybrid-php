AbstractView = require "../abstract/AbstractView.coffee"

class NavigationView extends AbstractView

    initialize: (opts) ->
        super(opts)

        ###window.addEventListener("scroll" , @onScroll)
        @onScroll();###



    onScroll: =>
        console.log "scroll"
        if window.scrollY > @$el.height()
            @$el.addClass("black")
        else
            @$el.removeClass("black")


    events:
        "click li" : "onNavClick"


    onNavClick: (e) =>
        e.preventDefault()
        $el = $(e.target).closest("li")

        href =  $el.find("> a").attr('href')

        if href
            @navigate href
        else
            sub = $el.find("ul")
            li = sub.find("li").first()
            href = sub.data("route")+li.data('route')
            @navigate href

    navigate: (href) ->
        Backbone.history.navigate href ,
            trigger: true



    showSubMenu: (dataRoute) ->
        subMenu = @$el.find("ul[data-route='#{dataRoute}']").parent()
        if subMenu?
            subMenu.data("active", true)
            TweenMax.to subMenu ,.3 ,
                autoAlpha:1
                overwrite:"all"


    hideSubMenus: () ->
        subMenu = @$el.find("ul.link-list").parent()
        console.log(subMenu)
        if subMenu?
            subMenu.each ->
                if $(@).data("active")
                    $(@).data("active", false)
                    TweenMax.to @ ,.3 ,
                        autoAlpha:0





    activateItem: (dataRoute) ->
        li = @$el.find("li[data-route='#{dataRoute}']")
        li.addClass("active")

    deactivateItems: () ->
        lis = @$el.find("li.nav-item")
        lis.removeClass("active")




    updateUI: (path) ->

        paths = path.split("/")
        console.log path
        @hideSubMenus()
        @deactivateItems()


        for p in paths
            @showSubMenu "/#{p}"
            @activateItem "/#{p}"






        ###subMenu = $el.find("ul")
        if subMenu?
            if(!subMenu.data('active'))
                subMenu.data('active', true)
                TweenMax.to subMenu.find("li") , .3 ,
                    autoAlpha:1
            else
                subMenu.data('active', false)
                TweenMax.to subMenu.find("li") , .3 ,
                    autoAlpha:0###



module.exports = NavigationView