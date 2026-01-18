<script>
$(function() {
    // Override AdminLTE Treeview expand to NOT close other menus
    var originalExpand = $.fn.Treeview ? $.fn.Treeview.Constructor.prototype.expand : null;

    if (originalExpand) {
        $.fn.Treeview.Constructor.prototype.expand = function(treeviewMenu, parentLi) {
            // Skip accordion behavior - don't close siblings
            var expandedEvent = $.Event('expanded.lte.treeview');
            parentLi.addClass('menu-is-opening');
            treeviewMenu.stop().slideDown(this._config.animationSpeed, function() {
                parentLi.addClass('menu-open');
                parentLi.removeClass('menu-is-opening');
            });
        };
    }

    // Restore menu state from localStorage
    // Priority: localStorage > server-side (menu-open class)
    var menuState = JSON.parse(localStorage.getItem('sidebarMenuState') || '{}');

    $('.nav-sidebar .nav-item.has-treeview').each(function() {
        var $item = $(this);
        var $treeview = $item.find('> .nav-treeview');
        if ($treeview.length > 0) {
            var menuId = $item.find('> .nav-link p').first().text().trim();
            
            // Check if user has explicitly set this menu state
            if (menuState.hasOwnProperty(menuId)) {
                // User has set preference - use localStorage value
                if (menuState[menuId] === true) {
                    $item.addClass('menu-open');
                    $treeview.css('display', 'block');
                } else {
                    // User explicitly closed this menu - respect that
                    $item.removeClass('menu-open');
                    $treeview.css('display', 'none');
                }
            }
            // If not in localStorage, keep server-side state (menu-open from Blade)
        }
    });

    // Save state on menu click
    $(document).on('click', '.nav-sidebar .nav-item.has-treeview > .nav-link', function(e) {
        var $parent = $(this).parent();
        var $treeview = $parent.find('> .nav-treeview');

        if ($treeview.length > 0) {
            var menuId = $(this).find('p').first().text().trim();
            setTimeout(function() {
                menuState[menuId] = $parent.hasClass('menu-open');
                localStorage.setItem('sidebarMenuState', JSON.stringify(menuState));
            }, 300);
        }
    });
});
